<?php

namespace App\Services;
use DateTime;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\TaskPause;
use App\Http\AppCache\TasksCache;
use App\Models\AllowedEditingDate;
use Facades\App\Http\Helpers\TaskHelper;
use Facades\App\Http\Helpers\TimeElapsedHelper;

class TasksServices
{
    public function load($status)
    {
        $datastorage = [];
        $status = strtolower($status);
        $agent_id = auth()->user()->id;

        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:id,email,fullname',
                'theroleactivity:id,name,sla'
            ])
            ->where('agent_id', $agent_id);

        // filter by status
        if(in_array($status,(['all'])))
        {
            $tasks = $tasks->get();
        }
        else
        {
            $tasks = $tasks->where('status',$status)->get();
        }

        foreach($tasks as $value) {
            if ($value->status == 'In Progress') {
                $status = '<span class="text-success"><strong>'.$value->status.'</strong></span>';
                $action = '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                    <button type="button" class="btn btn-info btn-sm waves-effect waves-light" title="Pause Task: On Hold" onclick=TASK.show_pause('.$value->id.')><i class="fas fa-pause"></i></button>
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Stop Task: Complete" onclick=TASK.show_stop('.$value->id.')><i class="fas fa-stop"></i></button>';
            }
            else if ($value->status == 'On Hold') {
                $status = '<span class="text-warning"><strong>'.$value->status.'</strong></span>';
                $action = '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-success btn-sm waves-effect waves-light" title="Resume Task" onclick=TASK.show_resume('.$value->id.')><i class="fas fa-play"></i></button>';
            }
            else if ($value->status == 'Completed') {
                $status = '<span class="text-primary"><strong>'.$value->status.'</strong></span>';
            }

            if($value->status == "Completed")
            {
                $allowed_daterange =  AllowedEditingDate::first();
                $date_from = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_from));
                $date_to = date('Y-m-d H:i:s', strtotime($allowed_daterange->allowed_date_to));
                $shift_date = date('Y-m-d H:i:s', strtotime($value->shift_date));

                $is_allowed_to_edit = ($shift_date >= $date_from && $shift_date <= $date_to) ? 1 : 0;
                $action = $is_allowed_to_edit ? '<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit Task" onclick=TASK.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>' : '-';
            }

            $employee_name = $value->theagent ? $value->theagent->fullname : "";
            $shift_date = date("m/d/Y",strtotime($value->shift_date));
            $date_received = date("m/d/Y",strtotime($value->date_received));
            $cluster = $value->thecluster->name;
            $client = $value->theclient->name;
            $role_activity = $value->theroleactivity->name;
            $sla = TimeElapsedHelper::convertTime($value->theroleactivity->sla);
            $description = $value->description;
            $start_date = date("m/d/Y h:i:s a",strtotime($value->start_date));
            $end_date = $value->end_date ? date("m/d/Y h:i:s a",strtotime($value->end_date)) : '-';
            $date_completed = $value->status == "On Hold" ? '-' : ($value->end_date ? date("m/d/Y",strtotime($value->end_date)) : '-');

            // START OF ACTUAL HANDLING TIME
            $now = Carbon::now();
            $actual_handling_timer = $value->start_date->diff($now)->format('%D:%H:%I:%S');
            if($value->status <> "Completed")
            {
                $start_at = $value->start_date;
                $end_at = $now->format('Y-m-d H:i:s');
                $shift_start = '00:00:00';
                $shift_end = '23:59:59';
                $pauses = [];
                $events = []; //retain as empty array since there is no events module in the system

                $pauses = $this->getTaskPauses($value->id);
                $working_hours = TimeElapsedHelper::calculateWorkingTime($start_at, $end_at, $shift_start, $shift_end, $pauses, $events);
                $actual_handling_time = TimeElapsedHelper::convertTime($working_hours);

                $sla_missed = $working_hours > $value->theroleactivity->sla ? '<span class="text-danger">Yes</span>' : '<span class="text-success">No</span>';
            }
            else
            {
                $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : $actual_handling_timer;
                $sla_missed = $value->sla_missed ? '<span class="text-danger">Yes</span>' : '<span class="text-success">No</span>';
            }
            // END OF ACTUAL HANDLING TIME

            $volume = $value->volume == 0 ? '0' : $value->volume;
            $remarks = $value->remarks ? $value->remarks : '';

            $datastorage[] = [
                'id' => $value->id,
                'status' => $status,
                'action' => $action,
                'employee_name' => $employee_name,
                'shift_date' => $shift_date,
                'date_received' => $date_received,
                'cluster' => $cluster,
                'client' => $client,
                'role_activity' => $role_activity,
                'sla' => $sla,
                'description' => $description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'date_completed' => $date_completed,
                'actual_handling_time' => $actual_handling_time,
                'sla_missed' => $sla_missed,
                'volume' => $volume,
                'remarks' => $remarks,
            ];
        }

        return $datastorage;
    }

    public function loadTasklists($status)
    {
        $datastorage = [];
        $tasks = Task::query()
            ->with([
                'thecluster:id,name',
                'theclient:id,name',
                'theagent:id,email',
                'theagent:id,fullname',
                'theroleactivity:id,name,sla'
            ]);

        // admin
        if(auth()->user()->permission == 'admin')
        {
            $tasks = $tasks;
        }
        // operations manager
        elseif(auth()->user()->permission == 'operations manager')
        {
            $tasks = $tasks->OMPermission();
        }
        // team leader
        elseif(auth()->user()->permission == 'team leader')
        {
            $tasks = $tasks->TLPermission();
        }

        // filter by status
        if(in_array($status,(['','all'])))
        {
            $tasks = $tasks->get();
        }
        else
        {
            $tasks = $tasks->where('status',$status)->get();
        }

        foreach($tasks as $value) {
            if ($value->status == 'In Progress') {
                $status = '<span class="text-success"><strong>'.$value->status.'</strong></span>';
            }
            else if ($value->status == 'On Hold') {
                $status = '<span class="text-warning"><strong>'.$value->status.'</strong></span>';
            }
            else if ($value->status == 'Completed') {
                $status = '<span class="text-primary"><strong>'.$value->status.'</strong></span>';
            }

            $employee_name = $value->theagent->fullname;
            $shift_date = date("m/d/Y",strtotime($value->shift_date));
            $date_received = date("m/d/Y",strtotime($value->date_received));
            $cluster = $value->thecluster->name;
            $client = $value->theclient->name;
            $role_activity = $value->theroleactivity->name;
            $sla = TimeElapsedHelper::convertTime($value->theroleactivity->sla);
            $description = $value->description;
            $start_date = date("m/d/Y h:i:s a",strtotime($value->start_date));
            $end_date = $value->end_date ? date("m/d/Y h:i:s a",strtotime($value->end_date)) : '-';
            $date_completed = $value->status == "On Hold" ? '-' : ($value->end_date ? date("m/d/Y",strtotime($value->end_date)) : '-');

            // START OF ACTUAL HANDLING TIME
            $now = \Carbon\Carbon::now();
            $actual_handling_timer = $value->start_date->diff($now)->format('%D:%H:%I:%S');
            if($value->status <> "Completed")
            {
                $start_at = $value->start_date;
                $end_at = $now->format('Y-m-d H:i:s');
                $shift_start = '00:00:00';
                $shift_end = '23:59:59';
                $pauses = [];
                $events = []; //retain as empty array since there is no events module in the system

                $pauses = $this->getTaskPauses($value->id);
                $working_hours = TimeElapsedHelper::calculateWorkingTime($start_at, $end_at, $shift_start, $shift_end, $pauses, $events);
                $actual_handling_time = TimeElapsedHelper::convertTime($working_hours);

                $sla_missed = $working_hours > $value->theroleactivity->sla ? '<span class="text-danger">Yes</span>' : '<span class="text-success">No</span>';
            }
            else
            {
                $actual_handling_time = $value->actual_handling_time ? $value->actual_handling_time : $actual_handling_timer;
                $sla_missed = $value->sla_missed ? '<span class="text-danger">Yes</span>' : '<span class="text-success">No</span>';
            }
            // END OF ACTUAL HANDLING TIME

            $volume = $value->volume == 0 ? '0' : $value->volume;
            $remarks = $value->remarks ? $value->remarks : '';

            $datastorage[] = [
                'id' => $value->id,
                'status' => $status,
                'employee_name' => $employee_name,
                'shift_date' => $shift_date,
                'date_received' => $date_received,
                'cluster' => $cluster,
                'client' => $client,
                'role_activity' => $role_activity,
                'sla' => $sla,
                'description' => $description,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'date_completed' => $date_completed,
                'actual_handling_time' => $actual_handling_time,
                'sla_missed' => $sla_missed,
                'volume' => $volume,
                'remarks' => $remarks,
            ];
        }

        return $datastorage;
    }

    // get task pauses
    public function getTaskPauses($task_id) {
        $pauses = TaskPause::query()
            ->select('id','task_id','start','end')
            ->where('task_id', $task_id)
            ->get();

        if($pauses->count() > 0)
        {
            foreach($pauses as $value)
            {
                $datastorage[] = [
                    'start' => new DateTime($value->start),
                    'end' => new DateTime($value->end)
                ];
            }
            return $datastorage;
        }
        else
        {
            return [];
        }
    }
}

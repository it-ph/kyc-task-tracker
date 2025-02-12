<?php

namespace App\Services;

use App\Models\RoleActivity;

class RoleActivityServices
{
    public function load($role_id)
    {
        $datastorage = [];
        $activities = RoleActivity::query()
            ->where('role_id',$role_id)
            ->get();

        foreach($activities as $value) {
            $name = $value->name;
            $sla = $value->sla;
            $frequency = $value->frequency ? ucwords($value->frequency) : '';
            $schedule = $value->schedule ? ucwords($value->schedule) : '';
            $function = $value->function ? ucwords($value->function) : '';
            $updated_at = date("m/d/Y h:i:s a",strtotime($value->updated_at));
            $action ='<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit User" onclick=ACTIVITY.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Delete User" onclick=ACTIVITY.destroy('.$value->id.')><i class="fas fa-times"></i></button>';

            $datastorage[] = [
                'id' => $value->id,
                'name' => $name,
                'sla' => $sla,
                'frequency' => $frequency,
                'schedule' => $schedule,
                'function' => $function,
                'updated_at' => $updated_at,
                'action' => $action,
            ];
        }

        return $datastorage;
    }
}

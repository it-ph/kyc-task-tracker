<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PermissionsServices
{
    public function load()
    {
        $datastorage = [];
        $users = User::with([
            'thecluster:id,name',
            'theclient:id,name',
            'thetl:id,fullname',
            'theom:id,fullname',
        ])
        ->select('id','email','fullname','cluster_id','client_id','tl_id','om_id','role_id','permission','status')
        ->where('permission','<>','superadmin');

        // admin
        if(auth()->user()->permission == 'admin')
        {
            $users = $users->get();
        }
        // operations manager
        elseif(auth()->user()->permission == 'operations manager')
        {
            $users = $users->OMPermission()->get();
        }
        // team leader
        elseif(auth()->user()->permission == 'team leader')
        {
            $users = $users->TLPermission()->get();
        }


        dd($users);

        foreach($users as $value) {
            $employee_name = $value->theuser ? $value->theuser->fullname.' '.$value->theuser->last_name : "";
            $email_address = $value->theuser ? strtolower($value->theuser->email) : "";
            $cluster = $value->thecluster ? $value->thecluster->name : "";
            $client = $value->theclient ? $value->theclient->name : "";
            $team_leader = $value->thetl ? $value->thetl->theuser->fullname.' '.$value->thetl->theuser->last_name : "";
            $operations_manager = $value->theom ? $value->theom->theuser->fullname.' '.$value->theom->theuser->last_name : "";
            $permission = ucwords($value->permission);
            $employment_status = $value->theuser->employment_status == 'active' ? '<span class="text-success"><strong>Active</strong></span>' : '<label class="text-danger"><strong>Inactive</strong></label>';
            $action ='<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit User" onclick=PERMISSION.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>';
                    // <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Delete User" onclick=PERMISSION.destroy('.$value->id.')><i class="fas fa-times"></i></button>';

            $datastorage[] = [
                'id' => $value->id,
                'employee_name' => $employee_name,
                'email_address' => $email_address,
                'cluster' => $cluster,
                'client' => $client,
                'team_leader' => $team_leader,
                'operations_manager' => $operations_manager,
                'permission' => $permission,
                'employment_status' => $employment_status,
                'action' => $action,
            ];
        }

        return $datastorage;
    }
}

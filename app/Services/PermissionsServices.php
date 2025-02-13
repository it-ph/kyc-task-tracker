<?php

namespace App\Services;

use App\Models\User;

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
            'therole:id,name',
        ])
        ->select('id','email','fullname','cluster_id','client_id','tl_id','om_id','role_id','permission','status')
        ->where('permission','<>','superadmin');

        // Get user permission once
        $userPermission = auth()->user()->permission;

        // Filter users based on user permission
        switch ($userPermission) {
            case 'admin':
                // Admin: No filtering needed, get all users
                $users = $users->get();
                break;
            case 'operations manager':
                $users = $users->OMPermission()->get();
                break;
            case 'team leader':
                $users = $users->TLPermission()->get();
                break;
            default:
                // Optional: Handle any other case if needed
                break;
        }

        foreach($users as $value) {
            $employee_name = ucwords($value->fullname);
            $email_address = strtolower($value->email);
            $cluster = $value->thecluster ? $value->thecluster->name : "";
            $client = $value->theclient ? $value->theclient->name : "";
            $team_leader = $value->thetl ? $value->thetl->fullname : "";
            $operations_manager = $value->theom ? $value->theom->fullname : "";
            $role = $value->therole ? ucwords($value->therole->name) : "";
            $permission = ucwords($value->permission);
            $status = $value->status == 'active' ? '<span class="text-success"><strong>Active</strong></span>' : '<label class="text-danger"><strong>Inactive</strong></label>';
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
                'role' => $role,
                'permission' => $permission,
                'status' => $status,
                'action' => $action,
            ];
        }

        return $datastorage;
    }
}

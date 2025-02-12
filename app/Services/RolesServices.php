<?php

namespace App\Services;

use App\Models\Role;

class RolesServices
{
    public function load()
    {
        $datastorage = [];
        $roles = Role::query()
            ->withCount(['theroleactivities'])
            ->get();

        foreach($roles as $value) {
            $name = $value->name;
            $activity = '<a href="'.route('role-activities.view', ['id' => $value->id,'role' => $value->name]).'" title="View Role Activities">
                    <button class="btn btn-primary btn-sm"><span class="badge" style="background-color:#fff; color:#00599D">'.$value->theroleactivities_count.'</span> View Activities <i class="fa fa-chevron-right"></i></button>
                </a>';
            $action ='<button type="button" class="btn btn-warning btn-sm waves-effect waves-light" title="Edit User" onclick=ROLE.show('.$value->id.')><i class="fas fa-pencil-alt"></i></button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" title="Delete User" onclick=ROLE.destroy('.$value->id.')><i class="fas fa-times"></i></button>';

            $datastorage[] = [
                'id' => $value->id,
                'name' => $name,
                'activity' => $activity,
                'action' => $action,
            ];
        }

        return $datastorage;
    }
}

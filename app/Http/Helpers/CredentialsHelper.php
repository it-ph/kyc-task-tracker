<?php

namespace App\Http\Helpers;

use App\Models\User;

class CredentialsHelper {
    private $credentials;

    /** Getter and Setter */
    public function setCredentials()
    {
        $user = User::with([
            'theroles:id,user_id,name',
            'theclient:id,name'
        ])->findOrFail(auth()->user()->id);
        $roles = [];
        foreach ($user->theroles as $role) {
            array_push($roles, $role->name);
        }
        asort($roles);
        $theroles = '';
        foreach ($roles as $role) {
            $theroles .= !next($roles) ? $role : $role .' | ';
        }

        $userdata = [
                'id'        => $user->id,
                'full_name' => $user->full_name,
                'email'  => strtolower($user->email),
                'roles'     => $roles,
                'client'     => $user->theclient ? ucfirst($user->theclient->name) : '',
                'theroles' => ucwords($theroles)
        ];
        $this->credentials = $userdata;
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function get_set_credentials()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return $user;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\Cluster;
use App\Models\ClientActivity;
use App\Models\Role;
use Illuminate\Support\Facades\View;

class GlobalVariableController extends Controller
{
    public $clusters,$clients,$client_activities,$roles,$users,$permissions,$tls,$oms;

    public function __construct()
    {
        $this->clusters = Cluster::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();


        $this->clients = Client::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->client_activities = ClientActivity::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->roles = Role::query()
            ->select('id','name')
            ->orderBy('name', 'ASC')
            ->get();

        $this->users = User::query()
            ->select('id','email','fullname','cluster_id','client_id','tl_id','om_id','role_id','permission','status')
            ->where('status','active')
            ->orderBy('email', 'ASC')
            ->get();

        $this->permissions = User::with([
                'thecluster:id,name',
                'theclient:id,name',
                'thetl:id,fullname',
                'theom:id,fullname',
            ])
            ->select('id','email','fullname','cluster_id','client_id','tl_id','om_id','role_id','permission','status')
            ->where('permission','<>','superadmin')
            ->get();

        $permissions = User::query()
            ->whereIn('permission',['admin','team leader','operations manager'])
            ->orderBy('fullname')
            ->get();


        $this->tls = $permissions;
        $this->oms = $permissions;

        View::share('clusters', $this->clusters);
        View::share('clients', $this->clients);
        View::share('client_activities', $this->client_activities);
        View::share('roles', $this->roles);
        View::share('users', $this->users);
        View::share('permissions', $this->permissions);
        View::share('tls', $this->tls);
        View::share('oms', $this->oms);
    }
}

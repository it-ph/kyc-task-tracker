<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Models\ClientActivity;
use App\Models\RoleActivity;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalVariableController;

class PageController extends GlobalVariableController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Agent Permissions
     */
    public function showPermissions(Request $request)
    {
        return view('pages.admin.permissions.list');
    }

    /**
     * Clusters
     */
    public function showClusters()
    {
        return view('pages.admin.clusters.list');
    }

    /**
     * Roles
     */
    public function showRoles()
    {
        return view('pages.admin.roles.list');
    }

    /**
     * Role Activities
     */
    public function showRoleActivities(Request $request)
    {
        $role_id = ($request['id']);
        return view('pages.admin.roles.activities.list', compact('role_id'));
    }

    // ADMIN, TL, & OM ACCESS
    public function showAgentTaskLists(Request $request)
    {
        // accountant
        if(auth()->user()->permission == 'agent')
        {
            return redirect()->route('unauthorized');
        }

        $status = $request['status'];
        if(!in_array(strtolower($status),['','all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }

        return view('pages.admin.tasks.list');
    }

    // AGENT ACCESS
    public function showAgentTasks(Request $request)
    {
        $status = $request['status'];
        if(!in_array(strtolower($status),['','all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }

        $clients = auth()->user()->permission == 'admin' ? $clients = Client::with('thecluster')->get() : Client::with('thecluster')->cluster()->get();

        $user_client_activities = ClientActivity::query()
            ->select('id','agent_id','name')
            ->where('agent_id', auth()->user()->id)
            ->orderBy('name', 'ASC')
            ->get();

        $role_activities = RoleActivity::query()
            ->select('id','name','sla')
            ->where('role_id', auth()->user()->role_id)
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.agent.tasks.list', compact('status','clients','user_client_activities','role_activities'));
    }

    /**
     * Task Lists
     */
    public function AgentTasks(Request $request)
    {
        $status = $request['status'];

        // accountant
        if(Auth::user()->isAccountant())
        {
            return redirect()->route('unauthorized');
        }

        $status = $request['status'];
        if(!in_array(strtolower($status),['all','in progress','on hold','completed']))
        {
            return view('errors.404');
        }
        $clients = Auth::user()->isAdmin() ? $clients = Client::with('thecluster') : Client::with('thecluster')->cluster()->get();

        $user_client_activities = ClientActivity::query()
            ->select('id','agent_id','name')
            ->orderBy('name', 'ASC')
            ->get();

        return view('pages.admin.tasks.list', compact('status'));
    }
}

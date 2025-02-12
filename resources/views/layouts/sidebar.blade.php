<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                {{-- MAIN NAVIGATION --}}
                <li class="menu-title" key="t-menu">Main Navigation</li>

        {{-- Start of Active Users --}}
                <li>
                    <a href="{{ url('home') }}" class="waves-effect">
                        <i class="bx bxs-dashboard"></i>
                        <span key="t-home">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('activities') }}" class="waves-effect">
                        <i class="bx bx-task" @if(\Request::routeIs('activities')) style="color:#fff" @endif></i>
                        <span key="t-activities" @if(\Request::routeIs('activities')) style="color:#fff" @endif>My Activities</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('my-tasks/all') }}" class="waves-effect">
                        <i class="bx bx-task" @if(\Request::routeIs('my-tasks.index')) style="color:#fff" @endif></i>
                        <span key="t-tasks" @if(\Request::routeIs('my-tasks.index')) style="color:#fff" @endif>My Tasks</span>
                    </a>
                </li>

            @if(Auth::user()->isAccountant())
                {{--REPORTS --}}
                <li class="menu-title" key="t-menu">Reports</li>
                <li>
                    <a href="{{ url('reports') }}" class="waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-reports">Reports</span>
                    </a>
                </li>
                {{-- MANAGE --}}
                {{-- <li class="menu-title" key="t-apps">Manage</li> --}}
            @endif

        {{-- End of Active Users --}}

        {{-- Start of ADMIN / TL / OM --}}
            @if(Auth::user()->isTeamLeaderOrAdmin() || Auth::user()->isOperationsManagerOrAdmin())
                <li>
                    <a href="{{ url('tasks/?status=all') }}" class="waves-effect" @if(\Request::has('status')) style="color:#fff" @endif>
                        <i class="bx bx-task" @if(\Request::has('status')) style="color:#fff" @endif></i>
                        <span key="t-tasks-list">Task Lists</span>
                    </a>
                </li>

                {{--REPORTS --}}
                <li class="menu-title" key="t-menu">Reports</li>
                <li>
                    <a href="{{ url('reports') }}" class="waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-reports">Reports</span>
                    </a>
                </li>

                {{-- MANAGE --}}
                <li class="menu-title" key="t-apps">Manage</li>

                <li>
                    <a href="{{ url('permissions') }}" class="waves-effect">
                        <i class="bx bxs-user-detail"></i>
                        <span key="t-users">Users</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('clusters') }}" class="waves-effect">
                        <i class="bx bx-hive"></i>
                        <span key="t-clusters">Clusters</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('clients') }}" class="waves-effect">
                        <i class="bx bxs-group"></i>
                        <span key="t-clients">Clients</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('client-activities') }}@if(Auth::user()->isAccountant())/?user_id={{ Auth::user()->id }}&employeename=@isset(Auth::user()->employeeprofile){{ strtolower(Auth::user()->employeeprofile->fullname) }} {{ strtolower(Auth::user()->employeeprofile->last_name) }}@endisset @endif" class="waves-effect">
                        <i class="bx bx-list-ul" @if(\Request::has('employeename')) style="color:#fff" @endif></i>
                        <span key="t-client-activities" @if(\Request::has('employeename')) style="color:#fff" @endif>@if(Auth::user()->isTeamLeaderOrAdmin() || Auth::user()->isOperationsManagerOrAdmin()) Users' @endif Activities</span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('roles') }}" class="waves-effect">
                        <i class="bx bx-list-ul"></i>
                        <span key="t-clients">Role Activities</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->isAdmin())
                <li>
                    <a href="{{ url('settings') }}" class="waves-effect">
                        <i class="bx bxs-cog"></i>
                        <span key="t-settings">Settings</span>
                    </a>
                </li>
            @endif

            </ul>
        {{-- End of ADMIN / TL / OM --}}
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->

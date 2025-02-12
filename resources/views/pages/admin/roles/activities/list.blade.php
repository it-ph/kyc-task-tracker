@extends('layouts.master')

@section('title') Activities List @endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Activities @endslot
        @slot('title') @if(!Auth::user()->isAccountant()) {{ ucwords(\Request::get('role')) }}'s @endif Activity List @endslot
    @endcomponent

    <div class="row">
        <div class="col-md-12">
            @include('notifications.success')
            @include('notifications.error')
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="{{ url('roles') }}" class="btn btn-primary btn-sm waves-effect waves-light"><i class="fas fa-chevron-left"></i> Back</a>
                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addActivityModal"><i class="fas fa-plus"></i> Create</button>
                            <input type="hidden" id="role_id" value="{{ $role_id }}">
                        </div>
                    </div>

                    <table id="tbl_activity" class="table table-bordered table-striped table-sm nowrap w-100">
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>SLA (hours)</th>
                                {{-- <th>Frequency</th>
                                <th>Schedule</th>
                                <th>Function</th> --}}
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>

                    <div id="div-spinner" class="text-center mt-4 mb-4">
                        <span id="loader" style="font-size: 16px"><i class="fa fa-spinner fa-spin"></i> Please wait...</span>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

    @include('pages.admin.roles.activities.add-modal')
    @include('pages.admin.roles.activities.edit-modal')
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/pdfmake.min.js') }}"></script>
@endsection

@section('custom-js')
    <script src="{{asset('scripts/role-activities.js')}}"></script>
@endsection

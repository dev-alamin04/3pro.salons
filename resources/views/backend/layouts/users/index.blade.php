@extends('backend.layouts.app')
@section('title', ' || Admin')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="User Management"
            subtitle='Manage all users across the platform including Job Seekers, Recruiters, and Admins.' />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <x-table-header title="User Management"  :route="route('users.create')" />
                        <div class="table-responsive w-100">
                            <table class="table reloadAdminTable table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Joined At</th>
                                        <th>Is Active</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal') <x-status-modal /> @endsection

@section('script')
    @include('backend.layouts.users.partials._usersJS')
@endsection

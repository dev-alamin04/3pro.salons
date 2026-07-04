@extends('backend.layouts.app')
@section('title', ' || Team Management')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Team Management" subtitle="View and manage all team members across the platform." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <x-table-header title="All Team Members" />
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Salon</th>
                                        <th>Specialist</th>
                                        <th>Status</th>
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

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dt = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                responsive: true,
                ajax: '{{ route("admin.team.index") }}',
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role', orderable: false, searchable: false },
                    { data: 'salon_name', name: 'salon_name' },
                    { data: 'specialist', name: 'specialist' },
                    { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                language: {
                    paginate: {
                        previous: '<i class="fas fa-angle-left"></i>',
                        next: '<i class="fas fa-angle-right"></i>'
                    }
                }
            });
        });
    </script>
@endsection

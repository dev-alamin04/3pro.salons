@extends('backend.layouts.app')
@section('title', ' || Daily Tasks')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Daily Tasks Management" subtitle="View and manage all daily tasks across the platform." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <x-table-header title="All Daily Tasks" />
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>User</th>
                                        <th>Assigned By</th>
                                        <th>Salon</th>
                                        <th>Target Date</th>
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
                ajax: '{{ route("admin.tasks.index") }}',
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'description', name: 'description', orderable: false, searchable: false },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'assigned_by_name', name: 'assigned_by_name' },
                    { data: 'salon_name', name: 'salon_name' },
                    { data: 'target_date', name: 'target_date' },
                    { data: 'is_completed', name: 'is_completed', orderable: false, searchable: false },
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

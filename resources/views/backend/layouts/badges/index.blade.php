@extends('backend.layouts.app')
@section('title', ' || Badges')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Badges Management" subtitle="View and manage all badges across the platform." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <x-table-header title="All Badges" />
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>User</th>
                                        <th>Assigned By</th>
                                        <th>Salon</th>
                                        <th>Pillar</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th>Visible</th>
                                        <th>Notes</th>
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
                ajax: '{{ route("admin.badges.index") }}',
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'assigned_by_name', name: 'assigned_by_name' },
                    { data: 'salon_name', name: 'salon_name' },
                    { data: 'pillar_name', name: 'pillar_name' },
                    { data: 'perfomence_level', name: 'perfomence_level', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'is_visible', name: 'is_visible', orderable: false, searchable: false },
                    { data: 'notes', name: 'notes', orderable: false, searchable: false },
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

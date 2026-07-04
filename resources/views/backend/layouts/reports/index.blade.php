@extends('backend.layouts.app')
@section('title', ' || Reports')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Reports Management" subtitle="View and manage all reports across the platform." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <x-table-header title="All Reports" />
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>User</th>
                                        <th>Reported By</th>
                                        <th>Salon</th>
                                        <th>Progress Type</th>
                                        <th>Report Text</th>
                                        <th>Date</th>
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
                ajax: '{{ route("admin.reports.index") }}',
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'reported_by_name', name: 'reported_by_name' },
                    { data: 'salon_name', name: 'salon_name' },
                    { data: 'progress_type', name: 'progress_type' },
                    { data: 'report_text', name: 'report_text', orderable: false, searchable: false },
                    { data: 'created_at', name: 'created_at' },
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

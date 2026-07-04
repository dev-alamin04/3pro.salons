@extends('backend.layouts.app')
@section('title', ' || User Goals')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="{{ $user->name }} - Goals"
            subtitle="All goals assigned to this user."
            :breadcrumbs="[
                ['text' => 'Goals', 'url' => route('admin.goals.index')],
                ['text' => 'User Goals'],
            ]" />

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ $user->avatar_path ?? $user->profile_photo_url }}" alt="Avatar"
                            class="rounded-circle mb-2" width="80" height="80">
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-2">{{ $user->email }}</p>
                        <span class="badge bg-primary">{{ ucfirst($user->role ?? 'N/A') }}</span>
                        @if($user->currentSalon?->salon)
                            <p class="text-muted small mt-2 mb-0">
                                <i class="fas fa-store"></i> {{ $user->currentSalon->salon->name }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <x-table-header title="User Goals" />
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Title</th>
                                        <th>Assigned By</th>
                                        <th>Level</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Target Date</th>
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
                ajax: '{{ route("admin.goals.user", $user->id) }}',
                order: [[0, 'desc']],
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'assigned_by_name', name: 'assigned_by_name' },
                    { data: 'level', name: 'level', orderable: false, searchable: false },
                    { data: 'progress', name: 'progress', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'target_date', name: 'target_date' },
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

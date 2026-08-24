@extends('backend.layouts.app')
@section('title') Support Requests || Admin @endsection

@push('style')
<style>
    :root {
        --brand:      #00b4c8;
        --brand-dark: #0093a8;
        --brand-soft: rgba(0,180,200,.10);
        --card-bg:    #27282D;
        --surface:    #2e2f35;
        --border:     #3a3b42;
        --text:       #F2F3F5;
        --muted:      #A9AFBB;
    }

    /* Stat cards */
    .stat-card {
        background: var(--card-bg);
        border-radius: 14px;
        border: 1px solid var(--border);
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .stat-icon {
        width: 50px; height: 50px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px; flex-shrink: 0;
    }
    .stat-icon.brand  { background: var(--brand-soft); color: var(--brand); }
    .stat-icon.warn   { background: rgba(245,158,11,.12); color: #f59e0b; }
    .stat-icon.green  { background: rgba(74,222,128,.12); color: #4ade80; }
    .stat-value { font-size: 26px; font-weight: 700; color: var(--text); line-height: 1; }
    .stat-label { font-size: 12px; color: var(--muted); margin-top: 4px; }

    /* Main card */
    .cs-main-card {
        background: var(--card-bg);
        border-radius: 14px;
        border: 1px solid var(--border);
        padding: 22px;
    }
    .cs-card-title {
        font-size: 15px; font-weight: 600; color: var(--text);
        display: flex; align-items: center; gap: 8px;
    }
    .cs-card-title i { color: var(--brand); }
</style>
@endpush

@section('content')
    <x-breadcrumbs title="Support Requests" :breadcrumbs="[['text' => 'Support Requests', 'url' => route('admin.contacts.index')]]" />

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon warn"><i class="fas fa-envelope-open"></i></div>
                <div>
                    <div class="stat-value">{{ \App\Models\ContactSubmission::where('is_read', false)->count() }}</div>
                    <div class="stat-label">Unread Requests</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-value">{{ \App\Models\ContactSubmission::where('is_read', true)->count() }}</div>
                    <div class="stat-label">Resolved Requests</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon brand"><i class="fas fa-headset"></i></div>
                <div>
                    <div class="stat-value">{{ \App\Models\ContactSubmission::count() }}</div>
                    <div class="stat-label">Total Requests</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="cs-main-card">
        <div class="cs-card-title mb-3">
            <i class="fas fa-headset"></i>
            <span>All Support Requests</span>
        </div>
        <hr style="border-color:var(--border);margin:0 0 16px;">
        <div class="table-responsive">
            <table class="table table-hover table-striped" id="contacts-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Job Title</th>
                        <th>Salon</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
    @include('modal._delete_confirm')
@endsection

@section('script')
    <script>
        $(function () {
            const table = $('#contacts-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ route("admin.contacts.index") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name',        name: 'name' },
                    { data: 'job_title',   name: 'job_title',   defaultContent: '—' },
                    { data: 'salon_name',  name: 'salon_name',  defaultContent: '—' },
                    { data: 'email',       name: 'email' },
                    { data: 'phone',       name: 'phone',       defaultContent: '—' },
                    { data: 'message',     name: 'message' },
                    { data: 'status',      name: 'status',  orderable: false, searchable: false },
                    { data: 'action',      name: 'action',  orderable: false, searchable: false }
                ],
                language: {
                    paginate: {
                        previous: '<i class="fas fa-angle-left"></i>',
                        next:     '<i class="fas fa-angle-right"></i>'
                    },
                    processing: dataTableLoader()
                }
            });

            window.showDeleteConfirm = function (id) {
                $('#delete_id').val(id);
                $('#deletemodal').modal('show');
            };

            $('#delete_modal_clear').on('submit', function (e) {
                e.preventDefault();
                const id = $('#delete_id').val();
                $.ajax({
                    url:  '/admin/contacts/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        $('#deletemodal').modal('hide');
                        table.ajax.reload(null, false);
                        successModal(res.message);
                    },
                    error: function () { errorModal('Unable to delete!'); }
                });
            });

            window.markRead = function (id) {
                $.ajax({
                    url:  '/admin/contacts/' + id,
                    type: 'PUT',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function (res) {
                        table.ajax.reload(null, false);
                        successModal(res.message);
                    },
                    error: function (xhr) {
                        errorModal(xhr.responseJSON?.message || 'Already marked as read!');
                    }
                });
            };
        });
    </script>
@endsection
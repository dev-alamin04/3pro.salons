@extends('backend.layouts.app')
@section('title', 'View Support Request')

@push('style')
<style>
    /* ── Brand color tokens ─────────────────── */
    :root {
        --brand:      #00b4c8;
        --brand-dark: #0093a8;
        --brand-soft: rgba(0, 180, 200, .10);
        --card-bg:    #27282D;
        --surface:    #2e2f35;
        --border:     #3a3b42;
        --text:       #F2F3F5;
        --muted:      #A9AFBB;
    }

    /* ── Layout ─────────────────────────────── */
    .cs-grid { display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start; }
    @media(max-width:991px){ .cs-grid { grid-template-columns:1fr; } }

    /* ── Card ───────────────────────────────── */
    .cs-card {
        background: var(--card-bg);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
    }
    .cs-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 22px;
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-dark) 100%);
    }
    .cs-card-header h5 { margin:0; color:#fff; font-size:15px; font-weight:600; }
    .cs-card-body { padding: 24px 22px; }

    /* ── Field rows ─────────────────────────── */
    .cs-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px; }
    @media(max-width:600px){ .cs-row { grid-template-columns:1fr; } }
    .cs-field label {
        display:block; font-size:11px; font-weight:600;
        letter-spacing:.6px; text-transform:uppercase;
        color: var(--muted); margin-bottom:5px;
    }
    .cs-field p {
        margin:0; font-size:14px; color: var(--text); font-weight:500;
    }
    .cs-field a { color: var(--brand); text-decoration:none; }
    .cs-field a:hover { text-decoration:underline; }

    /* ── Message box ────────────────────────── */
    .cs-message {
        background: var(--surface);
        border-left: 4px solid var(--brand);
        border-radius: 0 10px 10px 0;
        padding: 16px 18px;
        font-size:14px; line-height:1.8;
        color: var(--text);
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* ── Divider ────────────────────────────── */
    .cs-divider { border:none; border-top:1px solid var(--border); margin:18px 0; }

    /* ── Reply textarea ─────────────────────── */
    .cs-textarea {
        width:100%; box-sizing:border-box;
        background: var(--surface) !important;
        border: 1px solid var(--border) !important;
        border-radius: 10px !important;
        color: var(--text) !important;
        font-size:14px; padding:14px 16px;
        resize:vertical; min-height:140px;
        transition: border-color .2s;
    }
    .cs-textarea:focus {
        outline:none;
        border-color: var(--brand) !important;
        box-shadow: 0 0 0 3px rgba(0,180,200,.15) !important;
    }
    .cs-textarea::placeholder { color: var(--muted); }

    /* ── Buttons ────────────────────────────── */
    .btn-brand {
        display:inline-flex; align-items:center; gap:7px;
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color:#fff; border:none; border-radius:10px;
        padding:10px 22px; font-size:14px; font-weight:600;
        cursor:pointer; transition: opacity .2s, transform .1s;
    }
    .btn-brand:hover { opacity:.9; }
    .btn-brand:active { transform:scale(.98); }
    .btn-brand:disabled { opacity:.55; cursor:not-allowed; }

    .btn-outline-brand {
        display:inline-flex; align-items:center; gap:7px;
        background:transparent;
        color: var(--brand); border:1.5px solid var(--brand);
        border-radius:10px; padding:9px 20px;
        font-size:14px; font-weight:600; cursor:pointer;
        transition: background .2s, color .2s;
        text-decoration:none;
    }
    .btn-outline-brand:hover { background: var(--brand-soft); color: var(--brand); }

    .btn-danger-soft {
        display:inline-flex; align-items:center; gap:7px;
        background:transparent; color:#ef4444;
        border:1.5px solid #ef4444; border-radius:10px;
        padding:9px 20px; font-size:14px; font-weight:600;
        cursor:pointer; transition: background .2s;
    }
    .btn-danger-soft:hover { background:rgba(239,68,68,.08); }

    /* ── Status badge ───────────────────────── */
    .cs-badge-read   { background:#16a34a22; color:#4ade80; border:1px solid #4ade8044; border-radius:20px; padding:4px 14px; font-size:12px; font-weight:600; }
    .cs-badge-unread { background:#f59e0b22; color:#fbbf24; border:1px solid #fbbf2444; border-radius:20px; padding:4px 14px; font-size:12px; font-weight:600; }

    /* ── Attachment ─────────────────────────── */
    .cs-attachment img,
    .cs-attachment video { width:100%; border-radius:10px; border:1px solid var(--border); }

    /* ── Char counter ───────────────────────── */
    #char-count { font-size:11px; color:var(--muted); text-align:right; margin-top:4px; }
    #char-count.warn { color:#f59e0b; }
</style>
@endpush

@section('content')
    <x-breadcrumbs title="Support Request Detail"
        :breadcrumbs="[['text' => 'Support Requests', 'url' => route('admin.contacts.index')]]" />

    <div class="cs-grid">

        {{-- ───── LEFT — Main Details ───── --}}
        <div>

            {{-- Submitter Info Card --}}
            <div class="cs-card mb-3">
                <div class="cs-card-header">
                    <i class="fas fa-user" style="color:#fff;"></i>
                    <h5>Submitter Information</h5>
                    @if($contact->is_read)
                        <span class="cs-badge-read ms-auto">✓ Read</span>
                    @else
                        <span class="cs-badge-unread ms-auto">● Unread</span>
                    @endif
                </div>
                <div class="cs-card-body">
                    <div class="cs-row">
                        <div class="cs-field">
                            <label><i class="fas fa-user me-1"></i> Full Name</label>
                            <p>{{ $contact->name }}</p>
                        </div>
                        <div class="cs-field">
                            <label><i class="fas fa-briefcase me-1"></i> Job Title</label>
                            <p>{{ $contact->job_title ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="cs-row">
                        <div class="cs-field">
                            <label><i class="fas fa-store me-1"></i> Salon Name</label>
                            <p>{{ $contact->salon_name ?? '—' }}</p>
                        </div>
                        <div class="cs-field">
                            <label><i class="fas fa-map-marker-alt me-1"></i> City & State</label>
                            <p>{{ $contact->city_state ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="cs-row">
                        <div class="cs-field">
                            <label><i class="fas fa-envelope me-1"></i> Email</label>
                            <p><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></p>
                        </div>
                        <div class="cs-field">
                            <label><i class="fas fa-phone me-1"></i> Phone</label>
                            <p>
                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                @else
                                    —
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr class="cs-divider">

                    <div class="cs-field">
                        <label><i class="fas fa-comment-dots me-1"></i> What's going on?</label>
                        <div class="cs-message mt-2">{{ $contact->message }}</div>
                    </div>

                    {{-- Attachment --}}
                    @if($contact->attachment)
                        <hr class="cs-divider">
                        <div class="cs-field cs-attachment">
                            <label><i class="fas fa-paperclip me-1"></i> Attachment</label>
                            @php
                                $ext     = strtolower(pathinfo($contact->attachment, PATHINFO_EXTENSION));
                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                $isVideo = in_array($ext, ['mp4','mov','avi','webm']);
                                $fileUrl = asset('storage/' . $contact->attachment);
                            @endphp
                            <div class="mt-2">
                                @if($isImage)
                                    <a href="{{ $fileUrl }}" target="_blank">
                                        <img src="{{ $fileUrl }}" alt="Attachment">
                                    </a>
                                @elseif($isVideo)
                                    <video controls><source src="{{ $fileUrl }}" type="video/{{ $ext }}"></video>
                                @else
                                    <a href="{{ $fileUrl }}" target="_blank" class="btn-outline-brand">
                                        <i class="fas fa-download"></i> Download File
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Reply Card ── --}}
            <div class="cs-card">
                <div class="cs-card-header">
                    <i class="fas fa-reply" style="color:#fff;"></i>
                    <h5>Reply to {{ $contact->name }}</h5>
                </div>
                <div class="cs-card-body">
                    <p style="font-size:13px;color:var(--muted);margin:0 0 14px;">
                        Your reply will be sent directly to <strong style="color:var(--brand);">{{ $contact->email }}</strong>
                    </p>
                    <textarea
                        id="reply-textarea"
                        class="cs-textarea"
                        placeholder="Type your reply here — be clear and helpful. This goes directly to {{ $contact->name }}."
                        maxlength="5000"
                    ></textarea>
                    <div id="char-count">0 / 5000</div>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button id="send-reply-btn" class="btn-brand" onclick="sendReply()">
                            <i class="fas fa-paper-plane"></i> Send Reply
                        </button>
                        <span id="reply-spinner" class="ms-2 text-muted" style="display:none;font-size:13px;">
                            <i class="fas fa-circle-notch fa-spin me-1"></i> Sending…
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- ───── RIGHT — Sidebar ───── --}}
        <div>

            {{-- Submission Meta --}}
            <div class="cs-card mb-3">
                <div class="cs-card-header">
                    <i class="fas fa-info-circle" style="color:#fff;"></i>
                    <h5>Submission Info</h5>
                </div>
                <div class="cs-card-body">
                    <div class="cs-field mb-3">
                        <label>Submitted</label>
                        <p>{{ $contact->created_at->format('M d, Y') }}</p>
                        <p style="font-size:12px;color:var(--muted);margin-top:2px;">
                            {{ $contact->created_at->format('h:i A') }}
                            · {{ $contact->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="cs-field mb-3">
                        <label>Status</label>
                        <div class="mt-1">
                            @if($contact->is_read)
                                <span class="cs-badge-read">✓ Read / Resolved</span>
                            @else
                                <span class="cs-badge-unread">● Unread / Pending</span>
                            @endif
                        </div>
                    </div>
                    @if(!$contact->is_read)
                        <button onclick="markRead({{ $contact->id }})"
                                class="btn-brand w-100 justify-content-center mt-1">
                            <i class="fas fa-check"></i> Mark as Read
                        </button>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="cs-card">
                <div class="cs-card-header">
                    <i class="fas fa-bolt" style="color:#fff;"></i>
                    <h5>Quick Actions</h5>
                </div>
                <div class="cs-card-body d-flex flex-column gap-2">
                    <a href="{{ route('admin.contacts.index') }}" class="btn-outline-brand">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                    <button onclick="showDeleteConfirm({{ $contact->id }})" class="btn-danger-soft">
                        <i class="fas fa-trash"></i> Delete Request
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
<script>
    const replyUrl = '{{ route("admin.contacts.reply", $contact->id) }}';
    const csrf     = '{{ csrf_token() }}';
    const contactId = {{ $contact->id }};

    // Char counter
    const textarea  = document.getElementById('reply-textarea');
    const counter   = document.getElementById('char-count');
    textarea.addEventListener('input', function () {
        const len = this.value.length;
        counter.textContent = len + ' / 5000';
        counter.classList.toggle('warn', len > 4500);
    });

    // Send reply via AJAX
    window.sendReply = function () {
        const message = textarea.value.trim();
        if (!message || message.length < 5) {
            errorModal('Please enter at least 5 characters before sending.');
            return;
        }
        const btn     = document.getElementById('send-reply-btn');
        const spinner = document.getElementById('reply-spinner');
        btn.disabled  = true;
        spinner.style.display = 'inline-block';

        $.ajax({
            url:  replyUrl,
            type: 'POST',
            data: { _token: csrf, reply_message: message },
            success: function (res) {
                successModal(res.message);
                textarea.value = '';
                counter.textContent = '0 / 5000';
                // Update status badge
                document.querySelectorAll('.cs-badge-unread').forEach(el => {
                    el.className = 'cs-badge-read';
                    el.textContent = '✓ Read';
                });
            },
            error: function (xhr) {
                errorModal(xhr.responseJSON?.message || 'Failed to send reply. Please try again.');
            },
            complete: function () {
                btn.disabled = false;
                spinner.style.display = 'none';
            }
        });
    };

    // Mark as read
    window.markRead = function (id) {
        $.ajax({
            url:  '/admin/contacts/' + id,
            type: 'PUT',
            data: { _token: csrf },
            success: function (res) {
                successModal(res.message);
                setTimeout(() => location.reload(), 1400);
            },
            error: function (xhr) {
                errorModal(xhr.responseJSON?.message || 'Already marked as read!');
            }
        });
    };

    // Delete
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
            data: { _token: csrf },
            success: function (res) {
                $('#deletemodal').modal('hide');
                successModal(res.message);
                setTimeout(() => window.location.href = '{{ route("admin.contacts.index") }}', 1500);
            },
            error: function () { errorModal('Unable to delete!'); }
        });
    });
</script>
@endsection
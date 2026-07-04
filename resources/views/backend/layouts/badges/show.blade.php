@extends('backend.layouts.app')
@section('title', ' || Badge Details')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Badge Details"
            subtitle="View complete details of this badge."
            :breadcrumbs="[
                ['text' => 'Badges', 'url' => route('admin.badges.index')],
                ['text' => 'Detail'],
            ]" />

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Badge Information</h5>
                        <x-back-button />
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Performance Level</label>
                                <p class="mb-0">
                                    @php
                                        $levelClass = match($badge->perfomence_level) {
                                            'mastery' => 'bg-success',
                                            'advanced' => 'bg-info',
                                            'foundation' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $levelClass }}">{{ ucfirst($badge->perfomence_level ?? 'N/A') }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <p class="mb-0">
                                    @php
                                        $statusClass = match($badge->status) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-warning text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($badge->status ?? 'pending') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Pillar</label>
                                <p class="fw-semibold mb-0">{{ $badge->pillar?->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Visible</label>
                                <p class="mb-0">
                                    @if($badge->is_visible)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Salon</label>
                                <p class="fw-semibold mb-0">{{ $badge->salon?->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Created At</label>
                                <p class="fw-semibold mb-0">{{ $badge->created_at?->format('d M Y h:i A') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($badge->notes)
                            <div class="mb-3">
                                <label class="text-muted small">Notes</label>
                                <div class="p-3 bg-light rounded">
                                    {{ $badge->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- User Info --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Awarded To</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($badge->user)
                            <img src="{{ $badge->user->avatar_path ?? $badge->user->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="80" height="80">
                            <h6 class="mb-1">{{ $badge->user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $badge->user->email }}</p>
                        @else
                            <p class="text-muted mb-0">N/A</p>
                        @endif
                    </div>
                </div>

                {{-- Assigned By --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Assigned By</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($badge->assinedBy)
                            <img src="{{ $badge->assinedBy->avatar_path ?? $badge->assinedBy->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="60" height="60">
                            <h6 class="mb-1">{{ $badge->assinedBy->name }}</h6>
                            <p class="text-muted small mb-0">{{ $badge->assinedBy->email }}</p>
                        @else
                            <p class="text-muted mb-0">N/A</p>
                        @endif
                    </div>
                </div>

                {{-- Salon Info --}}
                @if($badge->salon)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Salon Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">Salon Name</label>
                                <p class="fw-semibold mb-0">{{ $badge->salon->name }}</p>
                            </div>
                            @if($badge->salon->location)
                                <div class="mb-2">
                                    <label class="text-muted small">Location</label>
                                    <p class="mb-0">{{ $badge->salon->location }}</p>
                                </div>
                            @endif
                            @if($badge->salon->address)
                                <div>
                                    <label class="text-muted small">Address</label>
                                    <p class="mb-0">{{ $badge->salon->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

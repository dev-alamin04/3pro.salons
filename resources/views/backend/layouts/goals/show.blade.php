@extends('backend.layouts.app')
@section('title', ' || Goal Details')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Goal Details"
            subtitle="View complete details of this goal."
            :breadcrumbs="[
                ['text' => 'Goals', 'url' => route('admin.goals.index')],
                ['text' => 'Detail'],
            ]" />

        <div class="row">
            {{-- Goal Info --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Goal Information</h5>
                        <x-back-button />
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Title</label>
                                <p class="fw-semibold mb-0">{{ $goal->title ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Level</label>
                                <p class="mb-0">
                                    @php
                                        $levelClass = match($goal->level) {
                                            'mastery' => 'bg-success',
                                            'advanced' => 'bg-info',
                                            'foundation' => 'bg-warning text-dark',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $levelClass }}">{{ ucfirst($goal->level ?? 'N/A') }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Pillar</label>
                                <p class="fw-semibold mb-0">{{ ucfirst($goal->piller ?? 'N/A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Target Date</label>
                                <p class="fw-semibold mb-0">{{ $goal->target_date?->format('d M Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <p class="mb-0">
                                    @php
                                        $statusClass = match($goal->status) {
                                            'completed' => 'bg-success',
                                            'late' => 'bg-danger',
                                            default => 'bg-warning text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($goal->status ?? 'pending') }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Public</label>
                                <p class="mb-0">
                                    @if($goal->is_public)
                                        <span class="badge bg-success">Public</span>
                                    @else
                                        <span class="badge bg-secondary">Private</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Active</label>
                                <p class="mb-0">
                                    @if($goal->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Progress</label>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 10px;">
                                        @php $percent = min(((int) $goal->progress / 5) * 100, 100); @endphp
                                        <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="fw-semibold">{{ $goal->progress }}/5</span>
                                </div>
                            </div>
                        </div>
                        @if($goal->description)
                            <div class="mb-3">
                                <label class="text-muted small">Description</label>
                                <p class="mb-0">{{ $goal->description }}</p>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <label class="text-muted small">Created At</label>
                                <p class="fw-semibold mb-0">{{ $goal->created_at?->format('d M Y h:i A') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="col-lg-4">
                {{-- Assigned To --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Assigned To (User)</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($goal->user)
                            <img src="{{ $goal->user->avatar_path ?? $goal->user->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="80" height="80">
                            <h6 class="mb-1">{{ $goal->user->name }}</h6>
                            <p class="text-muted small mb-2">{{ $goal->user->email }}</p>
                            @if($goal->user->currentSalon)
                                <a href="{{ route('admin.team.show', $goal->user->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-user"></i> View Profile
                                </a>
                            @endif
                        @else
                            <p class="text-muted">N/A</p>
                        @endif
                    </div>
                </div>

                {{-- Assigned By --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Assigned By</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($goal->assinedBy)
                            <img src="{{ $goal->assinedBy->avatar_path ?? $goal->assinedBy->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="60" height="60">
                            <h6 class="mb-1">{{ $goal->assinedBy->name }}</h6>
                            <p class="text-muted small mb-0">{{ $goal->assinedBy->email }}</p>
                        @else
                            <p class="text-muted">N/A</p>
                        @endif
                    </div>
                </div>

                {{-- Salon Info --}}
                @if($goal->user?->currentSalon?->salon)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Salon Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">Salon Name</label>
                                <p class="fw-semibold mb-0">{{ $goal->user->currentSalon->salon->name }}</p>
                            </div>
                            @if($goal->user->currentSalon->salon->location)
                                <div class="mb-2">
                                    <label class="text-muted small">Location</label>
                                    <p class="mb-0">{{ $goal->user->currentSalon->salon->location }}</p>
                                </div>
                            @endif
                            @if($goal->user->currentSalon->salon->address)
                                <div>
                                    <label class="text-muted small">Address</label>
                                    <p class="mb-0">{{ $goal->user->currentSalon->salon->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

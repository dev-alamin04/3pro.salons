@extends('backend.layouts.app')
@section('title', ' || Task Details')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Task Details"
            subtitle="View complete details of this daily task."
            :breadcrumbs="[
                ['text' => 'Tasks', 'url' => route('admin.tasks.index')],
                ['text' => 'Detail'],
            ]" />

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Task Information</h5>
                        <x-back-button />
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Title</label>
                                <p class="fw-semibold mb-0">{{ $dailyTask->title ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Status</label>
                                <p class="mb-0">
                                    @if($dailyTask->is_completed)
                                        <span class="badge bg-success">Completed</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Target Date</label>
                                <p class="fw-semibold mb-0">{{ $dailyTask->target_date?->format('d M Y') ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Created At</label>
                                <p class="fw-semibold mb-0">{{ $dailyTask->created_at?->format('d M Y h:i A') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($dailyTask->description)
                            <div class="mb-3">
                                <label class="text-muted small">Description</label>
                                <div class="p-3 bg-light rounded">
                                    {{ $dailyTask->description }}
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
                        <h6 class="mb-0">Assigned To</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($dailyTask->user)
                            <img src="{{ $dailyTask->user->avatar_path ?? $dailyTask->user->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="80" height="80">
                            <h6 class="mb-1">{{ $dailyTask->user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $dailyTask->user->email }}</p>
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
                        @if($dailyTask->assignedBy)
                            <img src="{{ $dailyTask->assignedBy->avatar_path ?? $dailyTask->assignedBy->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="60" height="60">
                            <h6 class="mb-1">{{ $dailyTask->assignedBy->name }}</h6>
                            <p class="text-muted small mb-0">{{ $dailyTask->assignedBy->email }}</p>
                        @else
                            <p class="text-muted mb-0">N/A</p>
                        @endif
                    </div>
                </div>

                {{-- Salon Info --}}
                @if($dailyTask->user?->currentSalon?->salon)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Salon Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">Salon Name</label>
                                <p class="fw-semibold mb-0">{{ $dailyTask->user->currentSalon->salon->name }}</p>
                            </div>
                            @if($dailyTask->user->currentSalon->salon->location)
                                <div class="mb-2">
                                    <label class="text-muted small">Location</label>
                                    <p class="mb-0">{{ $dailyTask->user->currentSalon->salon->location }}</p>
                                </div>
                            @endif
                            @if($dailyTask->user->currentSalon->salon->address)
                                <div>
                                    <label class="text-muted small">Address</label>
                                    <p class="mb-0">{{ $dailyTask->user->currentSalon->salon->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('backend.layouts.app')
@section('title', ' || Report Details')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Report Details"
            subtitle="View complete details of this report."
            :breadcrumbs="[
                ['text' => 'Reports', 'url' => route('admin.reports.index')],
                ['text' => 'Detail'],
            ]" />

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Report Information</h5>
                        <x-back-button />
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Progress Type</label>
                                <p class="fw-semibold mb-0">{{ $report->progress_type ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Salon</label>
                                <p class="fw-semibold mb-0">{{ $report->salon?->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="text-muted small">Created At</label>
                                <p class="fw-semibold mb-0">{{ $report->created_at?->format('d M Y h:i A') ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small">Updated At</label>
                                <p class="fw-semibold mb-0">{{ $report->updated_at?->format('d M Y h:i A') ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small">Report Text</label>
                            <div class="p-3 bg-light rounded">
                                {{ $report->report_text ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                {{-- User Info --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Reported User</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($report->user)
                            <img src="{{ $report->user->avatar_path ?? $report->user->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="80" height="80">
                            <h6 class="mb-1">{{ $report->user->name }}</h6>
                            <p class="text-muted small mb-0">{{ $report->user->email }}</p>
                        @else
                            <p class="text-muted mb-0">N/A</p>
                        @endif
                    </div>
                </div>

                {{-- Reported By --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Reported By</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($report->reportedBy)
                            <img src="{{ $report->reportedBy->avatar_path ?? $report->reportedBy->profile_photo_url }}" alt="Avatar"
                                class="rounded-circle mb-2" width="60" height="60">
                            <h6 class="mb-1">{{ $report->reportedBy->name }}</h6>
                            <p class="text-muted small mb-0">{{ $report->reportedBy->email }}</p>
                        @else
                            <p class="text-muted mb-0">N/A</p>
                        @endif
                    </div>
                </div>

                {{-- Salon Info --}}
                @if($report->salon)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Salon Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">Salon Name</label>
                                <p class="fw-semibold mb-0">{{ $report->salon->name }}</p>
                            </div>
                            @if($report->salon->location)
                                <div class="mb-2">
                                    <label class="text-muted small">Location</label>
                                    <p class="mb-0">{{ $report->salon->location }}</p>
                                </div>
                            @endif
                            @if($report->salon->address)
                                <div>
                                    <label class="text-muted small">Address</label>
                                    <p class="mb-0">{{ $report->salon->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

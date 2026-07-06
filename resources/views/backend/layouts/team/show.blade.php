@extends('backend.layouts.app')
@section('title', ' || Team Member Details')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="{{ $team->name }}"
            subtitle="Team member profile and activity overview."
            :breadcrumbs="[
                ['text' => 'Team', 'url' => route('admin.team.index')],
                ['text' => 'Profile'],
            ]" />

        <div class="row">
            {{-- Profile Card --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="{{ $team->avatar_path ?? $team->profile_photo_url }}" alt="Avatar"
                            class="rounded-circle mb-3" width="120" height="120">
                        <h4 class="mb-1">{{ $team->name }}</h4>
                        <p class="text-muted mb-2">{{ $team->email }}</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                            <span class="badge bg-primary">{{ ucfirst($team->role ?? 'N/A') }}</span>
                            @if($team->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                        @if($team->specialist)
                            <p class="text-muted small"><strong>Specialist:</strong> {{ $team->specialist }}</p>
                        @endif
                        @if($team->pronoun)
                            <p class="text-muted small"><strong>Pronoun:</strong> {{ $team->pronoun }}</p>
                        @endif
                        @if($team->experience_level)
                            <p class="text-muted small"><strong>Experience:</strong> {{ ucfirst($team->experience_level) }}</p>
                        @endif
                        <hr>
                        <div class="text-start">
                            <p class="mb-1 small text-muted"><strong>Joined:</strong> {{ $team->joined_at?->format('d M Y') ?? 'N/A' }}</p>
                            <p class="mb-0 small text-muted"><strong>Email Verified:</strong> {{ $team->email_verified_at ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Salon Info --}}
                @if($team->currentSalon?->salon)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Assigned Salon</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label class="text-muted small">Salon Name</label>
                                <p class="fw-semibold mb-0">{{ $team->currentSalon->salon->name }}</p>
                            </div>
                            @if($team->currentSalon->salon->location)
                                <div class="mb-2">
                                    <label class="text-muted small">Location</label>
                                    <p class="mb-0">{{ $team->currentSalon->salon->location }}</p>
                                </div>
                            @endif
                            @if($team->currentSalon->salon->address)
                                <div>
                                    <label class="text-muted small">Address</label>
                                    <p class="mb-0">{{ $team->currentSalon->salon->address }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Skills --}}
                @if($team->teamSkill && $team->teamSkill->count())
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Skills</h6>
                        </div>
                        <div class="card-body">
                            @foreach($team->teamSkill->groupBy('skill_category') as $category => $skills)
                                <div class="mb-3">
                                    <h6 class="text-primary small">{{ ucfirst($category) }}</h6>
                                    @foreach($skills as $skill)
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="small">{{ $skill->skill_name }}</span>
                                            <span class="badge bg-light text-dark">{{ ucfirst($skill->skill_level) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Activity Tabs --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="activityTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="goals-tab" data-bs-toggle="tab"
                                    data-bs-target="#goals" type="button" role="tab">
                                    Goals <span class="badge bg-primary ms-1">{{ $team->mygoal?->count() ?? 0 }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tasks-tab" data-bs-toggle="tab"
                                    data-bs-target="#tasks" type="button" role="tab">
                                    Tasks <span class="badge bg-primary ms-1">{{ $team->myTask?->count() ?? 0 }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="badges-tab" data-bs-toggle="tab"
                                    data-bs-target="#badges-tab-content" type="button" role="tab">
                                    Badges <span class="badge bg-primary ms-1">{{ $team->myBadges?->count() ?? 0 }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reports-tab" data-bs-toggle="tab"
                                    data-bs-target="#reports" type="button" role="tab">
                                    Reports <span class="badge bg-primary ms-1">{{ $team->myReport?->count() ?? 0 }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="activityTabContent">
                            {{-- Goals Tab --}}
                            <div class="tab-pane fade show active" id="goals" role="tabpanel">
                                @if($team->mygoal && $team->mygoal->count())
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Level</th>
                                                    <th>Progress</th>
                                                    <th>Status</th>
                                                    <th>Target</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($team->mygoal as $goal)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('admin.goals.show', $goal->id) }}">{{ $goal->title ?? 'N/A' }}</a>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $lvl = match($goal->level) {
                                                                    'mastery' => 'bg-success',
                                                                    'advanced' => 'bg-info',
                                                                    'foundation' => 'bg-warning text-dark',
                                                                    default => 'bg-secondary'
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $lvl }}">{{ ucfirst($goal->level ?? 'N/A') }}</span>
                                                        </td>
                                                        <td>
                                                            <div class="progress" style="height: 6px; width: 80px;">
                                                                @php $pct = min(((int) $goal->progress / 5) * 100, 100); @endphp
                                                                <div class="progress-bar bg-primary" style="width: {{ $pct }}%"></div>
                                                            </div>
                                                            <small class="text-muted">{{ $goal->progress }}/5</small>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $st = match($goal->status) {
                                                                    'completed' => 'bg-success',
                                                                    'late' => 'bg-danger',
                                                                    default => 'bg-warning text-dark'
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $st }}">{{ ucfirst($goal->status ?? 'pending') }}</span>
                                                        </td>
                                                        <td>{{ $goal->target_date?->format('d M Y') ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3 mb-0">No goals found.</p>
                                @endif
                            </div>

                            {{-- Tasks Tab --}}
                            <div class="tab-pane fade" id="tasks" role="tabpanel">
                                @if($team->myTask && $team->myTask->count())
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($team->myTask as $task)
                                                    <tr>
                                                        <td>{{ $task->title ?? 'N/A' }}</td>
                                                        <td><small>{{ Str::limit($task->description ?? '', 40) }}</small></td>
                                                        <td>{{ $task->target_date?->format('d M Y') ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($task->is_completed)
                                                                <span class="badge bg-success">Done</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3 mb-0">No tasks found.</p>
                                @endif
                            </div>

                            {{-- Badges Tab --}}
                            <div class="tab-pane fade" id="badges-tab-content" role="tabpanel">
                                @if($team->myBadges && $team->myBadges->count())
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Pillar</th>
                                                    <th>Level</th>
                                                    <th>Status</th>
                                                    <th>Salon</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($team->myBadges as $badge)
                                                    <tr>
                                                        <td>{{ $badge->pillar?->name ?? 'N/A' }}</td>
                                                        <td>
                                                            @php
                                                                $lvl = match($badge->perfomence_level) {
                                                                    'mastery' => 'bg-success',
                                                                    'advanced' => 'bg-info',
                                                                    'foundation' => 'bg-warning text-dark',
                                                                    default => 'bg-secondary'
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $lvl }}">{{ ucfirst($badge->perfomence_level ?? 'N/A') }}</span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $st = match($badge->status) {
                                                                    'approved' => 'bg-success',
                                                                    'rejected' => 'bg-danger',
                                                                    default => 'bg-warning text-dark'
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $st }}">{{ ucfirst($badge->status ?? 'pending') }}</span>
                                                        </td>
                                                        <td>{{ $badge->salon?->name ?? 'N/A' }}</td>
                                                        <td>{{ $badge->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3 mb-0">No badges found.</p>
                                @endif
                            </div>

                            {{-- Reports Tab --}}
                            <div class="tab-pane fade" id="reports" role="tabpanel">
                                @if($team->myReport && $team->myReport->count())
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Progress Type</th>
                                                    <th>Report</th>
                                                    <th>Salon</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($team->myReport as $report)
                                                    <tr>
                                                        <td>{{ $report->progress_type ?? 'N/A' }}</td>
                                                        <td><small>{{ Str::limit($report->report_text ?? '', 50) }}</small></td>
                                                        <td>{{ $report->salon?->name ?? 'N/A' }}</td>
                                                        <td>{{ $report->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3 mb-0">No reports found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

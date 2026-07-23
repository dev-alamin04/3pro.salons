@extends('backend.layouts.app')
@section('title')
    User Details || Admin
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="dashboard_header mb_10">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="dashboard_header_title">
                                <h3>User Details</h3>
                                <p class="text-muted">Complete profile overview and account status for {{ $user->name }}.</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="dashboard_breadcam text-end">
                                <x-back-button />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body text-center px-4 py-5">
                        <img src="{{ $user->avatar_path ?? $user->profile_photo_url }}" alt="Avatar"
                            class="rounded-circle mb-3" width="140" height="140" style="object-fit:cover;">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-1">{{ $user->email }}</p>
                        <p class="text-muted mb-3">{{ $user->phone ?? 'N/A' }}</p>

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Active' : 'Disabled' }}</span>
                            <span class="badge bg-{{ $user->email_verified_at ? 'primary' : 'secondary' }}">
                                {{ $user->email_verified_at ? 'Email Verified' : 'Email Unverified' }}</span>
                            @if(isset($user->is_premium) && $user->is_premium)
                                <span class="badge bg-warning text-dark">Premium</span>
                            @endif
                        </div>

                        <div class="row text-start gy-3">
                            <div class="col-6">
                                <small class="text-muted">Joined</small>
                                <div>{{ optional($user->joined_at)->format('M d, Y') ?? 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Role</small>
                                <div>{{ ucfirst($user->role ?? 'N/A') }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Experience</small>
                                <div>{{ ucfirst($user->experience_level ?? 'N/A') }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Tier Level</small>
                                <div>{{ $user->tier_level ?? 'N/A' }}</div>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Current Salon</small>
                                <div>{{ optional($user->currentSalon)->salon_id ?? 'Not assigned' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mt-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0">About</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">
                            {{ $user->bio ?? $user->about ?? 'No profile summary available for this user.' }}
                        </p>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Address:</strong> {{ $user->address ?? 'N/A' }}</li>
                            <li><strong>City:</strong> {{ $user->city ?? 'N/A' }}</li>
                            <li><strong>Country:</strong> {{ $user->country ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-uppercase text-muted mb-3">Progress Summary</h6>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h3 class="mb-0">{{ $user->badge ?? 0 }}</h3>
                                        <small class="text-muted">Total Badges</small>
                                    </div>
                                    <div class="badge bg-light text-dark py-2 px-3">{{ ucfirst($user->experience_level ?? 'N/A') }}</div>
                                </div>
                                <p class="mb-0 text-muted">Badges represent the completed milestones and performance level for this user.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-uppercase text-muted mb-3">Account Activity</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2"><strong>Last login:</strong> {{ optional($user->last_login_at)->format('M d, Y H:i') ?? 'N/A' }}</li>
                                    <li class="mb-2"><strong>Registered via:</strong> {{ ucfirst($user->provider ?? 'Email') }}</li>
                                    <li class="mb-2"><strong>Trial status:</strong> {{ isset($user->is_trail) ? ($user->is_trail ? 'On trial' : 'Completed') : 'N/A' }}</li>
                                    <li><strong>Metadata:</strong> {{ is_array($user->metadata) ? json_encode($user->metadata) : ($user->metadata ?? 'N/A') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Contact & Profile Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-sm-6">
                                <strong>Full Name</strong>
                                <p class="mb-0">{{ $user->name }}</p>
                            </div>
                            <div class="col-sm-6">
                                <strong>Email</strong>
                                <p class="mb-0">{{ $user->email }}</p>
                            </div>
                            <div class="col-sm-6">
                                <strong>Phone</strong>
                                <p class="mb-0">{{ $user->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <strong>Verified</strong>
                                <p class="mb-0">{{ $user->email_verified_at ? 'Yes' : 'No' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <strong>Premium</strong>
                                <p class="mb-0">{{ isset($user->is_premium) ? ($user->is_premium ? 'Yes' : 'No') : 'Unknown' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <strong>Account status</strong>
                                <p class="mb-0">{{ $user->is_active ? 'Active' : 'Disabled' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

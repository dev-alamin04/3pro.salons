@extends('backend.layouts.app')

@section('title', 'Profile Settings || Admin')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Profile Settings"
            subtitle='Manage your personal information, email, and account password.' />

        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <x-table-header title="My Profile" subtitle="Update your profile information and password." />

                     {{-- Profile Picture --}}
                        <div class="d-flex align-items-center mb-30">
                            <div class="profile-image" style="position: relative; display: inline-block; cursor: pointer;">
                                <img id="profile-picture"
                                    src="{{ asset(Auth::user()->avatar_path ?? 'backend/assets/img/profile/profile.jpeg') }}"
                                    alt="Profile Picture"
                                    style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">

                                <input type="file" name="profile_picture" id="profile_picture_input"
                                    accept="image/*"
                                    style="position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%;">

                                <div class="update-image" style="position: absolute; bottom: 0; right: 0; background: #d3d2e4; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                                    <i class="fa-solid fa-upload fa-lg" style="color: rgb(194, 124, 198);"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Profile Info Form --}}
                        <div class="card card-body mt-4">
                            <form method="POST" action="{{ route('update.profile') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 mt-4">
                                        <div class="input-style-1">
                                            <label for="name">User Name</label>
                                            <input type="text"
                                                class="form-control @error('name') is-invalid @enderror"
                                                name="name" id="name"
                                                value="{{ Auth::user()->name }}"
                                                placeholder="Full Name" />
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="input-style-1">
                                            <label for="email">Email</label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Email" name="email" id="email"
                                                value="{{ Auth::user()->email }}" />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <hr class="my-4">

                        {{-- Password Update --}}
                        <div class="mb-3">
                            <h3>Update Your Password</h3>
                        </div>

                        <div class="card card-body">
                            <form method="POST" action="{{ route('update.Password') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-12 mt-4">
                                        <div class="input-style-1">
                                            <label for="old_password">Current Password</label>
                                            <input type="password"
                                                class="form-control @error('old_password') is-invalid @enderror"
                                                placeholder="Current Password" name="old_password"
                                                id="old_password" />
                                            @error('old_password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="input-style-1">
                                            <label for="password">New Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="New Password" name="password"
                                                id="password" />
                                            @error('password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="input-style-1">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Confirm Password" name="password_confirmation"
                                                id="password_confirmation" />
                                            @error('password_confirmation')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger me-2">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#profile_picture_input').change(function() {
                const formData = new FormData();
                formData.append('profile_picture', $(this)[0].files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route('update.profile.picture') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success) {
                            $('#profile-picture').attr('src', data.image_url);
                            toastr.success('Profile picture updated successfully.');
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function() {
                        toastr.error('Something went wrong!');
                    }
                });
            });
        });
    </script>
@endpush

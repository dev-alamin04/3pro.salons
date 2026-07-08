@extends('backend.layouts.app')
@section('title', '|| Create User')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Create User account Or Salon" subtitle="Add a new user to the platform." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create User Account and Salon</h4>
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}">
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assign Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="">-- Select Account Type --</option>
                                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Salon</option>
                                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                                        <option value="lead"  {{ old('role') == 'lead'  ? 'selected' : '' }}>Lead</option>
                                    </select>
                                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Assign Salon <span class="text-danger">*</span></label>
                                    <select name="salon_id" class="form-control @error('salon_id') is-invalid @enderror">
                                        <option value="">-- Select Salon --</option>
                                        @foreach ($salons as $salon)
                                            <option value="{{ $salon->id }}" {{ old('salon_id') == $salon->id ? 'selected' : '' }}>
                                                {{ $salon->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('salon_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Create User</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary mt-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
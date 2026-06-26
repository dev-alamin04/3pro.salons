@extends('backend.layouts.app')
@section('title', '|| Assign User')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Assign Users" subtitle="Assign users to {{ $salon->name }}." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Assign User to {{ $salon->name }}</h4>

                        {{-- Assign Form --}}
                        <div class="d-flex gap-2 mb-4">
                            <select id="user_select" class="form-control w-auto">
                                <option value="">-- Select User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <button id="assign_btn" class="btn btn-primary">Assign</button>
                        </div>

                        {{-- Assigned Users Table --}}
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <a href="{{ route('salons.index') }}" class="btn btn-secondary mt-3">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('backend.layouts.salons.partials._assignJS', ['salon' => $salon])
@endsection
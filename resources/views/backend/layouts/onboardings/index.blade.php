@extends('backend.layouts.app')
@section('title', '|| Onboarding')

@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Onboarding Management" subtitle="Manage all onboarding items." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <x-table-header title="Onboarding Management" />
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                                <i class="fas fa-plus me-1"></i> Add Onboarding
                            </button>
                        </div>
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <x-status-modal />

    <x-onboarding-modal />
@endsection

@section('script')
    @include('backend.layouts.onboardings.partials._onboardingsJS')
@endsection
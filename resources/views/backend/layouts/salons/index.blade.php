@extends('backend.layouts.app')
@section('title', '|| Salons')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Salon Management" subtitle="Manage all salons across the platform." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                         <x-table-header title="Salon Management" :route="route('salons.create')" />

                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Address</th>
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

@section('modal') <x-status-modal /> @endsection

@section('script')
    @include('backend.layouts.salons.partials._salonsJS')
@endsection
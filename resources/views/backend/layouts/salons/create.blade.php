@extends('backend.layouts.app')
@section('title', '|| Create Salon')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Create Salon" subtitle="Add a new salon to the platform." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Create Salon</h4>
                        <form action="{{ route('salons.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('backend.layouts.salons.partials._form')
                            <button type="submit" class="btn btn-primary mt-3">Create</button>
                            <a href="{{ route('salons.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


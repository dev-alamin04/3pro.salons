 @extends('backend.layouts.app')
@section('title', '|| Edit Salon')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="Edit Salon" subtitle="Update salon information." />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Edit Salon</h4>
                        <form action="{{ route('salons.update', $salon->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('backend.layouts.salons.partials._form')
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                            <a href="{{ route('salons.index') }}" class="btn btn-secondary mt-3">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
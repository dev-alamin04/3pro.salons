<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $salon->name ?? '') }}">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>


    <div class="col-md-6 mb-3">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
            value="{{ old('location', $salon->location ?? '') }}">
        @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
            value="{{ old('address', $salon->address ?? '') }}">
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>


    <!-- <div class="col-md-6 mb-3">
        <label class="form-label">Latitude</label>
        <input type="text" name="lat" class="form-control @error('lat') is-invalid @enderror"
            value="{{ old('lat', $salon->lat ?? '') }}">
        @error('lat') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div> -->

    <!-- <div class="col-md-6 mb-3">
        <label class="form-label">Longitude</label>
        <input type="text" name="lang" class="form-control @error('lang') is-invalid @enderror"
            value="{{ old('lang', $salon->lang ?? '') }}">
        @error('lang') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div> -->

    <div class="col-md-6 mb-3">
        <label class="form-label">Avatar</label>
        @isset($salon->avatar_path)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $salon->avatar_path) }}" width="80" class="rounded">
            </div>
        @endisset
        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

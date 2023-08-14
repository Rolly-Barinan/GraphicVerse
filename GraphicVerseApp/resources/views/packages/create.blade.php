@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name">Package Name</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div>
                <label for="sub_category">Sub-Category</label>
                <select name="sub_category" id="sub_category">
                    <option value="fonts">Fonts</option>
                    <option value="character">Character</option>
                    <option value="environment">Environment</option>
                    <!-- Add more sub-category options as needed -->
                </select>
            </div>
            <div>
                <label for="images">Images</label>
                <input type="file" name="images[]" id="images" multiple required>
            </div>
            <button type="submit">Create Package</button>

            
            <div class="row mb-3">
                <label for="asset_name" class="col-md-4 col-form-label text-md-end text-white">Title </label>

                <div class="col-md-6">
                    <input id="asset_name" type="text"
                        class="form-control @error('asset_name') is-invalid @enderror" name="asset_name"
                        value="{{ old('asset_name') }}" autocomplete="asset_name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            
        </form>
    </div>
@endsection

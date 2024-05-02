@extends('layouts.app')

@section('content')
<link href="{{ asset('css/create.css') }}" rel="stylesheet">
@if (session('success'))
    <div class="alert alert-success" id="successAlert">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger" id="errorAlert">
        {{ session('error') }}
    </div>
@endif

<div class="container-fluid d-block">
    @if ($errors->any())
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                <li>{{ $errors->first() }}</li>
            </ul>
        </div>
        <script>
            setTimeout(function() {
                $('#errorAlert').fadeOut();
            }, 6000);
        </script>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-7" style="height: inherit;">
            <form action="{{ route('image.update', $image->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf
                @method('PATCH')
                <h1 class="mb-4">Edit Artwork</h1>
                <div class="form-group">
                    <input type="file" name="imageFile" class="form-control d-none" id="imageFile">
                </div>
                <div class="image-container mx-auto d-block">
                    <img id="mainImage" src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top main-image "
                        alt="{{ $image->ImageName }}">
                </div>
                <div class="form-group">
                    <h3 class="desc">Image Description</h3>
                    <textarea name="ImageDescription" class="description form-control">{{ old('ImageDescription', $image->ImageDescription) }}</textarea>
                    <p class="tiny-text">10-200 characters</p>
                </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class="input-icon">
                            <input type="text" name="ImageName" class="title" value="{{ old('ImageName', $image->ImageName) }}">
                            <i class="bi bi-pencil"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Price</h3>
                        <input type="number" name="Price" class="price form-control" min="0" value="{{ old('Price', $image->Price) }}">
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Categories</h3>
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="form-check-input" {{ in_array($category->id, old('category_ids', $image->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $category->cat_name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Update Artwork Info</button>
                </div>
            </form>
    </div>
</div>

<script>
    var imageDropzone = document.getElementById('image-dropzone');
    var imageInput = document.getElementById('imageFile');

    imageDropzone.addEventListener('click', function() {
        imageInput.click();
    });

    imageInput.addEventListener('change', function() {
        if (imageInput.files.length > 0) {
            imageDropzone.innerText = imageInput.files[0].name + ' selected';
        }
    });

    imageDropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        imageDropzone.classList.add('bg-light');
    });

    imageDropzone.addEventListener('dragleave', function() {
        imageDropzone.classList.remove('bg-light');
    });

    imageDropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        imageInput.files = e.dataTransfer.files;
        imageDropzone.innerText = e.dataTransfer.files[0].name + ' selected';
        imageDropzone.classList.remove('bg-light');
    });
</script>
<script>
    function validateForm() {
        var imageDescription = document.querySelector('textarea[name="ImageDescription"]');

        if (!imageDescription.value) {
            alert("Please fill in all required fields.");
            return false;
        }

        return true;
    }
</script>

@endsection

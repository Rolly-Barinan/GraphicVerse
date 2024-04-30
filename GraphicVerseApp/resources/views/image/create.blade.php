
@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css" rel="stylesheet">
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
            // Automatically remove the error alert after 3 seconds
            setTimeout(function() {
                $('#errorAlert').fadeOut();
            }, 6000);
        </script>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-7" style="height: inherit;">
            <form method="POST" action="{{ route('image.store') }}" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf
                <h1 class="mb-4">UPLOAD ARTWORK</h1>
                <div class="form-group">
                    <div class="dropzone d-flex flex-column justify-content-center align-items-center text-center" id="image-dropzone">
                        <i class="bi bi-cloud-upload"></i>
                        <div class="upload">Upload Image</div>
                        <p class="my-3">Maximum file size: 2 GB</p>
                    </div>
                    <input type="file" name="imageFile" class="form-control d-none" id="imageFile" required>
                </div>
                <div class="form-group">
                    <h3 class="desc">Image Description</h3>
                    <p class="desc">Provide a short description of your asset pack.</p>
                    <textarea name="ImageDescription" class="description form-control" placeholder="Enter description"></textarea>
                    
                    <p class="tiny-text">10-200 characters</p>
                </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class="input-icon">
                            <input type="text" name="ImageName" class="title" value="Artwork Title">
                            <i class="bi bi-pencil"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Price</h3>
                        <p class="text-muted">Leave empty for free download.</p>
                        <input type="number" name="Price" class="price form-control" min="0">
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Categories</h3>
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="form-check-input">
                                <label class="form-check-label">{{ $category->cat_name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <div id="watermarkDropzone" class="dropzone  d-flex flex-column justify-content-center align-items-center text-center">
                            <i class="bi bi-cloud-upload"></i>
                            <div class="upload">Upload Watermark</div>
                            <p class="my-3">Maximum file size: 5 MB</p>
                        </div>
                        <input type="file" name="watermarkFile" class="form-control-file d-none"id="watermarkFile" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
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
    var watermarkDropzone = document.getElementById('watermarkDropzone');
var watermarkInput = document.getElementById('watermarkFile');

watermarkDropzone.addEventListener('click', function() {
    watermarkInput.click();
});

watermarkInput.addEventListener('change', function() {
    if (watermarkInput.files.length > 0) {
        watermarkDropzone.querySelector('.upload').textContent = watermarkInput.files[0].name + ' selected';
    }
});
    watermarkDropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        watermarkDropzone.classList.add('bg-light');
    });

    watermarkDropzone.addEventListener('dragleave', function() {
        watermarkDropzone.classList.remove('bg-light');
    });

    watermarkDropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        watermarkInput.files = e.dataTransfer.files;
        watermarkDropzone.innerText = e.dataTransfer.files[0].name + ' selected';
        watermarkDropzone.classList.remove('bg-light');
    });
</script>

<script>
    function validateForm() {
        var imageFile = document.querySelector('input[name="imageFile"]');
        var imageDescription = document.querySelector('textarea[name="ImageDescription"]');

        if (!imageFile.files.length || !imageDescription.value) {
            alert("Please fill in all required fields.");
            return false;
        }

        return true;
    }
</script>

@endsection
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/create.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css" rel="stylesheet">
<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@extends('layouts.app')

@section('content')
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

    <script>
        function hideAlerts() {
            var successAlert = document.getElementById('successAlert');
            var errorAlert = document.getElementById('errorAlert');

            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 3000); // 3000 milliseconds = 3 seconds
            }

            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 3000); // 3000 milliseconds = 3 seconds
            }
        }
        // Call the hideAlerts function when the page loads
        window.onload = function() {
            hideAlerts();
        };

        function validateForm() {
            var assetFiles = document.querySelector('input[name="asset[]"]');
            var assetType = document.querySelector('select[name="asset_type_id"]');
            var packageName = document.querySelector('input[name="PackageName"]');
            var preview = document.querySelector('input[name="preview"]');
            var description = document.querySelector('textarea[name="Description"]');
            var price = document.querySelector('input[name="Price"]');
            var categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');

            if (!assetFiles.files.length || !assetType.value || !packageName.value || !description.value || !
                categoryCheckboxes.length) {
                alert("Please fill in all required fields.");
                return false;
            }
            // Check if the preview file is not a JPEG or PNG
            if (preview.files.length) {
                var allowedExtensions = ['jpg', 'jpeg', 'png'];
                var previewExtension = preview.files[0].name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(previewExtension)) {
                    alert("Preview file must be a JPEG, JPG, PNG or GIF.");
                    return false;
                }
            }
            // Additional custom validation if needed...
            return true;
        }
    </script>

    <script>
        function addTagToInput(tagName) {
            var customTagsInput = document.getElementById('customTags');
            var currentValue = customTagsInput.value.trim();
            if (currentValue === '') {
                customTagsInput.value = tagName;
            } else {
                customTagsInput.value = currentValue + ', ' + tagName;
            }
        }

        window.onload = function() {
            var recommendedTags = document.querySelectorAll('.recommended-tag');
            recommendedTags.forEach(function(tag) {
                tag.addEventListener('click', function() {
                    var tagName = this.textContent;
                    addTagToInput(tagName);
                });
            });

            var customTagsInput = document.getElementById('customTags');
            customTagsInput.addEventListener('keyup', function(event) {
                if (event.keyCode === 13 || event.keyCode === 188 || event.keyCode ===
                    32) { // Enter, Comma, Space
                    var tagName = this.value.trim();
                    if (tagName !== '') {
                        addTag(tagName, false);
                        this.value = '';
                    }
                }
            });
        };
    </script>

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
            <div class="col-md-7">
                <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data"
                    onsubmit="return validateForm()">
                    @csrf
                    <h1 class="mb-4">UPLOAD PACKAGE</h1>
                    <div class="form-group">
                        <div class="dropzone d-flex flex-column justify-content-center align-items-center text-center" id="asset-dropzone">
                            <i class="bi bi-cloud-upload"></i>
                            <div class="upload">Upload Asset Files</div>
                            <p class="my-3">Maximum file size: 5 GB</p>
                        </div>
                        <input type="file" name="asset[]" multiple class="form-control d-none" id="asset">
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Description</h3>
                        <p class="desc">Provide a short description of your asset pack.</p>
                        <textarea name="Description" class="description form-control" oninput="limitInput(this)">Description of the new package</textarea>
                        <p class="tiny-text">10-200 characters</p>
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Custom Tags</h3>
                        <input type="text" name="customTags" id="customTags" class="form-control"
                            placeholder="Enter custom tags separated by commas">
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Package Preview</h3>
                        <div class="dropzone p-3 text-center" id="preview-dropzone">
                            <p class="my-3">Drag and drop preview files here or click to select files</p>
                        </div>
                        <input type="file" name="preview" class="form-control d-none" id="preview">
                    </div>  
            </div>
            <div class="col-md-5">
                    <div class="form-group">
                        <div class="input-icon">
                            <input type="text" name="PackageName" class="title" value="Package Title">
                            <i class="bi bi-pencil"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Price</h3>
                        <p class="desc">Leave empty for free download.</p>
                        <input type="number" name="Price" class="price form-control" min="0">
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Team</h3>
                        <p class="desc">Assign asset to a team.</p>
                        <select name="asset_type_id" class="form-control">
                            <option value="" class="desc">Select Team</option>
                            @foreach ($assetTypes as $assetType)
                                <option value="{{ $assetType->id }}">{{ $assetType->asset_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Category</h3>
                        <select name="asset_type_id" class="form-control">
                            <option value="">Select an Asset Type</option>
                            @foreach ($assetTypes as $assetType)
                                <option value="{{ $assetType->id }}">{{ $assetType->asset_type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Recommended Tags</h3>
                        <div id="recommendedTags">
                            @foreach ($recommendedTags as $tag)
                            <div class="button-like">
                                <span class="tag recommended-tag"
                                    onclick="addTag('{{ $tag->name }}')">{{ $tag->name }} </span>
                                    <i class="bi bi-plus"></i>
                                </div>
                                    @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Categories:</h3>
                        @foreach ($categories as $category)
                            <div class="form-check">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                    class="form-check-input">
                                <label class="form-check-label">{{ $category->cat_name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        var assetDropzone = document.getElementById('asset-dropzone');
        var previewDropzone = document.getElementById('preview-dropzone');
        var assetInput = document.getElementById('asset');
        var previewInput = document.getElementById('preview');

        assetDropzone.addEventListener('click', function() {
            assetInput.click();
        });

        previewDropzone.addEventListener('click', function() {
            previewInput.click();
        });

        assetInput.addEventListener('change', function() {
            if (assetInput.files.length > 0) {
                assetDropzone.innerText = assetInput.files.length + ' asset file(s) selected';
            }
        });

        previewInput.addEventListener('change', function() {
            if (previewInput.files.length > 0) {
                previewDropzone.innerText = previewInput.files.length + ' preview file(s) selected';
            }
        });

        assetDropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            assetDropzone.classList.add('bg-light');
        });

        previewDropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            previewDropzone.classList.add('bg-light');
        });

        assetDropzone.addEventListener('dragleave', function() {
            assetDropzone.classList.remove('bg-light');
        });

        previewDropzone.addEventListener('dragleave', function() {
            previewDropzone.classList.remove('bg-light');
        });

        assetDropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            assetInput.files = e.dataTransfer.files;
            assetDropzone.innerText = e.dataTransfer.files.length + ' asset file(s) selected';
            assetDropzone.classList.remove('bg-light');
        });

        previewDropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            previewInput.files = e.dataTransfer.files;
            previewDropzone.innerText = e.dataTransfer.files.length + ' preview file(s) selected';
            previewDropzone.classList.remove('bg-light');
        });
    </script>
    <script>
        function limitInput(textarea) {
            var maxLength = 200;
            if (textarea.value.length > maxLength) {
                textarea.value = textarea.value.slice(0, maxLength);
            }
        }
    </script>
@endsection

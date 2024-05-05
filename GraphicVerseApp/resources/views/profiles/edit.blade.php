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

    <script>
        function limitInput(textarea) {
            var maxLength = 200;
            if (textarea.value.length > maxLength) {
                textarea.value = textarea.value.slice(0, maxLength);
            }
        }
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/create.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css" rel="stylesheet">

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
                <form method="POST" action="{{ route('profile.update', ['user' => $user->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <h1 class="mb-4">Edit Profile</h1>
                    <div class="form-group">
                        <h3 class="desc">Profile Image</h3>
                        <div class="dropzone d-flex flex-column justify-content-center align-items-center text-center" id="profile-image-dropzone">
                            <i class="bi bi-cloud-upload"></i>
                            <div class="upload">Upload Profile Image</div>
                            <p class="my-3">Maximum file size: 5 MB</p>
                        </div>
                        <input id="image" type="file" class="form-control d-none" name="image">
                    </div>

                    <div class="form-group">
                        <h3 class="desc">Cover Image</h3>
                            <div class="dropzone d-flex flex-column justify-content-center align-items-center text-center" id="cover-image-dropzone">
                                <i class="bi bi-cloud-upload"></i>
                                <div class="upload">Upload Cover Image</div>
                                <p class="my-3">Maximum file size: 5 MB</p>
                            </div>
                        <input id="cover_image" type="file" class="form-control d-none" name="cover_image">
                    </div>
            </div>
            <div class="col-md-5">
                    <div class="form-group">
                        <label for="title">Username</label>
                        <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ?? $user->profile->title }}" autofocus>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" style="height: 150px;">{{ old('description') ?? $user->profile->description }}</textarea>
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="url">Social Media URL</label>
                        <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') ?? $user->profile->url }}">
                        @error('url')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    var profileImageDropzone = document.getElementById('profile-image-dropzone');
    var coverImageDropzone = document.getElementById('cover-image-dropzone');
    var profileImageInput = document.getElementById('image');
    var coverImageInput = document.getElementById('cover_image');

    profileImageDropzone.addEventListener('click', function() {
        profileImageInput.click();
    });

    coverImageDropzone.addEventListener('click', function() {
        coverImageInput.click();
    });

    profileImageInput.addEventListener('change', function() {
        if (profileImageInput.files.length > 0) {
            profileImageDropzone.innerText = profileImageInput.files.length + ' profile image(s) selected';
        }
    });

    coverImageInput.addEventListener('change', function() {
        if (coverImageInput.files.length > 0) {
            coverImageDropzone.innerText = coverImageInput.files.length + ' cover image(s) selected';
        }
    });

    profileImageDropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        profileImageDropzone.classList.add('bg-light');
    });

    coverImageDropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        coverImageDropzone.classList.add('bg-light');
    });

    profileImageDropzone.addEventListener('dragleave', function() {
        profileImageDropzone.classList.remove('bg-light');
    });

    coverImageDropzone.addEventListener('dragleave', function() {
        coverImageDropzone.classList.remove('bg-light');
    });

    profileImageDropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        profileImageInput.files = e.dataTransfer.files;
        profileImageDropzone.innerText = e.dataTransfer.files.length + ' profile image(s) selected';
        profileImageDropzone.classList.remove('bg-light');
    });

    coverImageDropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        coverImageInput.files = e.dataTransfer.files;
        coverImageDropzone.innerText = e.dataTransfer.files.length + ' cover image(s) selected';
        coverImageDropzone.classList.remove('bg-light');
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

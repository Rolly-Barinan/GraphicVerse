<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('css/create.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css" rel="stylesheet">
<!-- JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@extends('layouts.app')

@section('content')

<div class="container-fluid d-block overflow-hidden">
        <div class="row justify-content-center">
            <div class="col-md-7 ">
                <form method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
                                @csrf
                    <h1 class="mb-4">CREATE A TEAM</h1>
                    <div class="form-group mt-3">
                        <h3 class="desc">Team Name</h3>
                        <input type="text" name="team_name" id="team_name" class="form-control" minlength="1" maxlength="25" required>
                        <p class="tiny-text">1-25 characters</p>
                        @error('profile_picture')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Team Logo</h3>
                        <div class="dropzone d-flex flex-column justify-content-center align-items-center text-center" id="profile-picture-dropzone">
                            <i class="bi bi-cloud-upload"></i>
                            <div class="upload">Upload Profile Picture</div>
                            <p class="my-3">Maximum file size: 5 MB</p>
                        </div>
                        <input type="file" name="profile_picture" class="form-control d-none" id="profile_picture">
                    </div>
                    <div class="form-group">
                        <h3 class="desc">Team Cover Picture</h3>
                        <div class="dropzone d-flex flex-column justify-content-center align-items-center text-center" id="cover-picture-dropzone">
                            <i class="bi bi-cloud-upload"></i>
                            <div class="upload">Upload Cover Picture</div>
                            <p class="my-3">Maximum file size: 5 MB</p>
                        </div>
                        <input type="file" name="cover_picture" class="form-control d-none" id="cover_picture">
                        @error('cover_picture')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create Team</button>
                </form>
            </div>
            <div class="col-md-5">
            <form method="POST" action="{{ route('teams.store') }}">
                @csrf
                <h1 class="mb-4">JOIN A TEAM</h1>
                    <div class="form-group mt-3">
                        <h3 class="desc">Team Code</h3>
                        <input type="text" name="team_code" id="team_code" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Join Team</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var profilePictureDropzone = document.getElementById('profile-picture-dropzone');
        var profilePictureInput = document.getElementById('profile_picture');

        profilePictureDropzone.addEventListener('click', function() {
            profilePictureInput.click();
        });

        profilePictureInput.addEventListener('change', function() {
            var fileName = this.files[0].name;
            var upload = profilePictureDropzone.querySelector('.upload');
            upload.textContent = fileName;
        });
    </script>
    <script>
        var coverPictureDropzone = document.getElementById('cover-picture-dropzone');
        var coverPictureInput = document.getElementById('cover_picture');

        coverPictureDropzone.addEventListener('click', function() {
            coverPictureInput.click();
        });

        coverPictureInput.addEventListener('change', function() {
            var file = this.files[0];
            var fileName = file.name;
            var upload = coverPictureDropzone.querySelector('.upload');
            upload.textContent = fileName;
        });
    </script>
@endsection
@extends('layouts.app')
<style>
    body {
        font-family: 'Nunito', sans-serif;
        background-image: url('https://cdn.discordapp.com/attachments/1121006331323760680/1123571308496691210/Copy_of_GraphicVerse_Capstone_Hearing.png');
        background-size: cover;
        background-repeat: no-repeat;
    }

    .navbar-nav .nav-item {
        display: flex;
    }
</style>
@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card" style="background-color: #222344; color: white;">
                <div class="card-header">
                    <h2 class="mb-4">Edit Profile</h2>
                </div>
                <div class="card-body">
                    <form action="/profile/{{ $user->id }}" enctype="multipart/form-data" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="title" class="form-label">Username</label>
                            <input id="title" type="text" class="form-control @error('title') is-invalid @enderror"
                                name="title" value="{{ old('title') ?? $user->profile->title }}" autocomplete="title"
                                autofocus>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" class="form-control @error('description') is-invalid @enderror"
                                name="description" rows="4"
                                autocomplete="description">{{ old('description') ?? $user->profile->description }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="url" class="form-label">URL</label>
                            <input id="url" type="text" class="form-control @error('url') is-invalid @enderror"
                                name="url" value="{{ old('url') ?? $user->profile->url }}" autocomplete="url"
                                autofocus>
                            @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control-file" id="image" name="image">
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary">Save Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <h1>Image Details</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <img src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top" alt="{{ $image->watermarkedImage }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $image->ImageName }}</h5>
                    <p class="card-text">{{ $image->ImageDescription }}</p>
                    <p class="card-text">Price: ${{ $image->Price }}</p>
                    <!-- Add more details as needed -->
                </div>
            </div>
        </div>
        <!-- You can add more details here -->
    </div>
</div>

@endsection

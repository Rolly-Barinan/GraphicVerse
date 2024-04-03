@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1>Image Details</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <img src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top"
                        alt="{{ $image->watermarkedImage }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $image->ImageName }}</h5>
                        <p class="card-text">{{ $image->ImageDescription }}</p>
                        @if ($image->Price != null && $image->Price != 0)
                            <p>Price: ${{ $image->Price }}</p>
                        @endif
                        <p> image size: {{ number_format($imageSize, 2) }}mb </p>
                        <p>Created By: {{ $user->name }} </p>
                    </div>
                </div>
            </div>
        </div>
        @if ($image->Price == null || $image->Price == 0)
            <a href="{{ route('image.download', $image->id) }}" class="btn btn-success">Download</a>
        @else
            <form action="{{ route('paypal') }}" method="POST">
                @csrf
                <input type="hidden" name="price" value="{{ $image->Price }}">
                <button type="submit" class="btn btn-primary">Pay with PayPal</button>
            </form>
        @endif
        <a href="/image" class="btn btn-secondary">Back</a>
    </div>
@endsection

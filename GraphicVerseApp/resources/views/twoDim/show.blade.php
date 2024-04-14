@extends('layouts.app')
<link href="{{ asset('css/show.css') }}" rel="stylesheet">

@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<div class="show container-fluid ">
    <div class="row">
        <div class="col-md-7">
            <img src="{{ Storage::url($package->Location) }}" class="card-img-top main-image" alt="{{ $package->PackageName }}">
            <div class="image-scroll-container">
                <ul class="image-list">
                    @if ($assets->count() > 0)
                        @foreach ($assets->take(5) as $asset)
                            <li>
                                <div class="card text-bg-secondary mb-3" style="width: 18rem;">
                                    <img src="{{ Storage::url($asset->Location) }}" class="card-img-top asset-image" alt="{{ $asset->AssetName }}">
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li>No assets found for this package.</li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-md-5">
            <div class="r-body">
                <h5 class="r-title">{{ $package->PackageName }}</h5>
                <p class="r-text">{{ $package->Description }}</p>
                @if ($package->Price != null && $package->Price != 0)
                    <p>Price: ${{ $package->Price }}</p>
                @endif
                <p>File Types: {{ implode(', ', $fileTypes->toArray()) }}</p>
                <p>File Size: {{ number_format($totalSizeMB, 2) }}mb</p>
                <p>Created By: {{ $user->name }}</p>
                <form action="{{ route('paypal') }}" method="POST">
                    @csrf
                    <input type="hidden" name="price" value="{{ $package->Price }}">
                    <button type="submit" class="btn btn-primary">Pay with PayPal</button>
                </form>
            </div>
            <a href="/3d-models" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

@endsection

<script>
    $(document).ready(function() {
        $('.scroll-btn.left').click(function() {
            $('.image-scroll-container').animate({ scrollLeft: '-=100' }, 'slow');
        });

        $('.scroll-btn.right').click(function() {
            $('.image-scroll-container').animate({ scrollLeft: '+=100' }, 'slow');
        });
    });
</script>

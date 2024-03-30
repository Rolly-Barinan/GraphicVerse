@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <h1>Package Details</h1>
            <div class="card">
                <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $package->PackageName }}</h5>
                    <p class="card-text">{{ $package->Description }}</p>
                    @if ($package->Price != null && $package->Price != 0)
                        <p>Price: ${{ $package->Price }}</p>
                    @endif
                    <p>File Types: {{ implode(', ', $fileTypes->toArray()) }}</p>
                    <p>File Size: {{ number_format($totalSizeMB, 2) }}mb</p>
                    <p>Created By: {{ $user->name }}</p>
                </div>
            </div>
            <h2>Assets in this Package</h2>
            <ul>
                @if ($assets->count() > 0)
                    @foreach ($assets->take(5) as $asset)
                        <li>{{ $asset->AssetName }}</li>

                        <div class="card text-bg-secondary mb-3" style="width: 18rem;">
                            <img src="{{ Storage::url($asset->Location) }}" class="card-img-top"
                                alt="{{ $asset->AssetName }}">
                        </div>
                    @endforeach
                @else
                    <li>No assets found for this package.</li>
                @endif
            </ul>
            @if ($package->Price == null || $package->Price == 0)
                <a href="{{ route('asset.download', $package->id) }}" class="btn btn-success">Download</a>
            @else
                <form action="{{ route('paypal') }}" method="POST">
                    @csrf
                    <input type="hidden" name="price" value="{{ $package->Price }}">
                    <button type="submit" class="btn btn-primary">Pay with PayPal</button>
                </form>
            @endif
            <a href="/2d-models" class="btn btn-secondary">Back</a>
        </div>

    </div>

@endsection

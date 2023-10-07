@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Package Details</h1>

    <div class="card">
        <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
        <div class="card-body">
            <h5 class="card-title">{{ $package->PackageName }}</h5>
            <p class="card-text">{{ $package->Description }}</p>
        </div>
    </div>

    {{-- <h2>Assets in this Package</h2>
    <ul>
        @if ($assets)
            @foreach ($assets as $asset)
                <li>{{ $asset->AssetName }}</li>
            @endforeach
        @else
            <li>No assets found for this package.</li>
        @endif
    </ul> --}}
    

    <a href="{{ route('asset.index') }}" class="btn btn-primary">Back to Packages</a>
    <a href="{{ route('asset.download', $package->id) }}" class="btn btn-success">Download</a>

</div>
@endsection

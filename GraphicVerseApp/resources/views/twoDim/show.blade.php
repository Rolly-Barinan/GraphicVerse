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
                <p>Price: ${{ $package->Price }}</p>
            </div>
        </div>

        <h2>Assets in this Package</h2>
        <ul>
            @if ($assets)
                @foreach ($assets as $asset)
                    <li>{{ $asset->AssetName }}</li>

                    <div class="card text-bg-secondary mb-3" style="width: 18rem;">
                       
                        <img src="{{ Storage::url($asset->Location) }}" class="card-img-top">
                        {{-- {{ dd(route('watermarked-assets.show', ['id' => $asset->AssetID])) }} --}}
                        {{-- <img src="{{ route('watermarked-assets.show', ['id' => $asset->AssetID]) }}" class="card-img-top"> --}}

                    </div>
                @endforeach
            @else
                <li>No assets found for this package.</li>
            @endif
        </ul>

        <form action="{{ route('paypal') }}" method="POST">
            @csrf

            <input type="hidden" name="price" value={{ $package->Price }}>

            <button type="submit" class="btn btn-primary">Pay with Paypal</button>
            {{-- <button type="submit"> Pay withss paypal</button> --}}

        </form>

        <a href="{{ route('asset.index') }}" class="btn btn-secondary">Back to Packages</a>
        <a href="{{ route('asset.download', $package->id) }}" class="btn btn-success">Download</a>

    </div>

    </div>
@endsection
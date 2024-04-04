@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="container">
            <h1>Package Details </h1>
            {{-- @if (Auth::id() == $package->UserID)
                <a href="/package/{{ $package->id }}/edit">
                    <img src="/svg/edit.svg" class="logo" alt="Edit Logo">
                </a>
                <form action="{{ route('asset.destroy', $package->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this package?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Package</button>
                </form>
            @endif --}}

            <div class="card">
                <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $package->PackageName }}</h5>
                    <p class="card-text">{{ $package->Description }}</p>
                    @if ($package->Price != null && $package->Price != 0)
                        <p>Price: ${{ $package->Price }}</p>
                    @endif
                    <p>File Types: {{ implode(', ', $fileTypes->toArray()) }}</p>
                    <p>File Size: {{ number_format($totalSizeMB, 2) }}kb</p>
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
            @if ($package->Price == null || $package->Price == 0 || $package->UserID == auth()->id())
                <!-- Assuming 'user_id' is the column that stores the owner's ID -->
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

@extends('layouts.app')

@section('content')

<div class="container">

    <h3>Filter Packages by Categories</h3>
    <form action="{{ route('filter.3d') }}" method="GET">
        @foreach($categories as $category)
            <div class="form-check " required>
                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}">
                <label class="form-check-label">
                    {{ $category->cat_name }}
                </label>
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Apply Filter</button>
    </form>
    <div class="row">
        @php
            $has3DAssets = false;
        @endphp
        @foreach ($packages as $package)
            @if ($package->assetType && $package->assetType->asset_type === '3D')
                @php
                    $has3DAssets = true;
                @endphp
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <a href="{{ route('threeDim.show', ['id' => $package->id]) }}">
                            <img src="{{ Storage::url($package->Location) }}" class="card-img-top"
                                alt="{{ $package->PackageName }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $package->PackageName }}</h5>
                                <p class="card-text">{{ $package->Description }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endif
        @endforeach
    
        @if (!$has3DAssets)
            <div class="col-md-12">
                <div class="alert alert-info" role="alert">
                    No 3D assets to display yet.
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h3>Filter Packages by Categories</h3>
        <form action="{{ route('filter.2d') }}" method="GET">
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
            <div class="col-md-4 mb-4">
                @foreach ($packages as $package)
                @if ($package->assetType && $package->assetType->asset_type === '2D')
                    <div class="card">
                        <a href="{{ route('twoDim.show', ['id' => $package->id]) }}">
                            <img src="{{ Storage::url($package->Location) }}" class="card-img-top"
                                alt="{{ $package->PackageName }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $package->PackageName }}</h5>
                                <p class="card-text">{{ $package->Description }}</p>
                            </div>
                        </a>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection

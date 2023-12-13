@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="filter-section">
                    <h3>Filter Packages by Categories</h3>
                    <form action="{{ route('filter.2d') }}" method="GET">
                        @foreach($categories as $category)
                            <div class="form-check">
                                <input class="form-check-input custom-checkbox" type="checkbox" name="categories[]" value="{{ $category->id }}" id="category{{ $category->id }}">
                                <label class="form-check-label checkbox-label" for="category{{ $category->id }}">
                                    {{ $category->cat_name }}
                                </label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </form>
                </div>
            </div>
            <div class="col-md-9">
                <div class="package-list">
                    @foreach ($packages as $package)
                        @if ($package->assetType && $package->assetType->asset_type === '2D')
                            <div class="card">
                                <a href="{{ route('twoDim.show', ['id' => $package->id]) }}">
                                    <img src="{{ Storage::url($package->Location) }}" class="card-img" alt="{{ $package->PackageName }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $package->PackageName }}</h5>
                                        <p class="card-text">{{ $package->Description }}</p>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
                {{ $packages->links() }}
            </div>
        </div>
    </div>
@endsection

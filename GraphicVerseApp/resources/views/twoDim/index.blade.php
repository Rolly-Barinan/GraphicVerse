@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex">
    <div class="sticky-column filter_column">
        <h3>Filter category</h3>
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
    </div>

    <div class="scrollable-column packages_column">
        <div class="row package_row">
            @if ($packages->isEmpty())
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        No assets to display yet.
                    </div>
                </div>
            @else
                @foreach ($packages as $index => $package)
                    @if ($package->assetType && $package->assetType->asset_type === '2D')
                        <div class="col-md-3 mb-3 preview_card">
                            <div class="card ">
                                <a href="{{ route('twoDim.show', ['id' => $package->id]) }}">
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
            @endif
        </div>
    </div>
</div>
@endsection

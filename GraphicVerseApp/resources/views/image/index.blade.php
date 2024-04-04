@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex">
        <div class= "sticky-column filter_column">
            <h3>Filter category</h3>
            <form action="{{ route('filter.image') }}" method="GET">
                @foreach ($categories as $category)
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

        <h1>Image Assets</h1>
        <div class="scrollable-column packages_column">
            <div class="row">
                @if ($images->isEmpty())
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            No image assets to display yet.
                        </div>
                    </div>
                @else
                    @foreach ($images as $image)
                        <div class="col-md-3 mb-3 preview_card">
                            <a href="{{ route('image.show', ['id' => $image->id]) }}">
                                <div class="card">
                                    <img src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top"
                                        alt="{{ $image->watermarkedImage }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $image->ImageName }}</h5>
                                        <p class="card-text">{{ $image->ImageDescription }}</p>
                                        <p class="card-text">Price: ${{ $image->Price }}</p>

                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h3>Filter by Category:</h3>
                    <form action="{{ route('twoD.index') }}" method="get">
                        @foreach ($categories as $category)
                            <label class="checkbox-label">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                {{ $category->cat_name }}
                            </label><br>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">2D Models</h1>
                    <div class="row">
                        @foreach ($models2D as $model)
                            <div class="col-md-4 mb-4">
                                <div class="card model-card">
                                    <img class="card-img-top model-image" src="{{ Storage::url($model->filename) }}" alt="{{ $model->twoD_name }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $model->twoD_name }}</h5>
                                        <p class="card-text">{{ $model->description }}</p>
                                        <p class="card-text">Creator: {{ $model->creator_name }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .checkbox-label {
        display: block;
        margin-bottom: 5px;
    }

    .model-card {
        height: 100%;
    }

    .model-image {
        max-width: 100%;
        max-height: 200px; /* Adjust the height as needed */
        object-fit: cover;
    }
</style>
@endsection

@extends('layouts.app')

@section('content')
    <h1 style="color: white">2D Models</h1>

    <!-- Category Filter -->
    <form action="{{ route('twoD.index') }}" method="get">
        <h3>Filter by Category:</h3>
        @foreach ($categories as $category)
            <label>
                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                {{ $category->cat_name }}
            </label><br>
        @endforeach
        <button type="submit">Apply Filters</button>
    </form>
    
    <ul style="color: white">
        @foreach ($models2D as $model)
            <li>
                <img src="{{ Storage::url($model->filename) }}" alt="{{ $model->twoD_name }}">
                <h3>{{ $model->twoD_name }}</h3>
                <p>{{ $model->description }}</p>
                <p>{{ $model->creator_name }}</p>
            </li>
        @endforeach
    </ul>
@endsection

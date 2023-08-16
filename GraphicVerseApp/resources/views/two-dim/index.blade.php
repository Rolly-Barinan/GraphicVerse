@extends('layouts.app')

@section('content')
    <h1 style="color: white">2D Models</h1>
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

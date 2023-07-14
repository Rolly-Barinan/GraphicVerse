@extends('layouts.app')
@section('content')
    <h1>Upload Audio</h1>

    <form method="POST" action="{{ route('audios.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control">
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <select name="category" id="category" class="form-control">
                <option value="music">Music</option>
                <option value="sound">Sound</option>
                <option value="ambient">Ambient</option>
            </select>
            @error('category')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="audio">Select Audio:</label>
            <input type="file" name="audio" id="audio" class="form-control">
            @error('audio')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
@endsection

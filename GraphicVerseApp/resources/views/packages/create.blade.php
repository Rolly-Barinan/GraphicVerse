@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="name">Package Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="category">Category</label>
            <select name="category" id="category" required>
                <option value="2D">2D</option>
                <option value="3D">3D</option>
                <option value="audio">Audio</option>
            </select>
        </div>
        <div>
            <label for="sub_category">Sub-Category</label>
            <select name="sub_category" id="sub_category">
                <option value="fonts">Fonts</option>
                <option value="character">Character</option>
                <option value="environment">Environment</option>
                <!-- Add more sub-category options as needed -->
            </select>
        </div>
        <div>
            <label for="images">Images</label>
            <input type="file" name="images[]" id="images" multiple required>
        </div>
        <button type="submit">Create Package</button>
    </form>
</div>
@endsection
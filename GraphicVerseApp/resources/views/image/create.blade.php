    <!-- resources/views/image/create.blade.php -->

    @extends('layouts.app')

    @section('content')

    @if (session('success'))
        <div class="alert alert-success" id="successAlert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" id="errorAlert">
            {{ session('error') }}
        </div>
    @endif
    
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Create ImageAsset') }}</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('image.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="asset_type_id">Asset Type:</label>
                                    <select name="asset_type_id" class="form-control" disabled>
                                        <option value="1" selected>2D</option>
                                        <!-- You can add more options for other asset types if needed -->
                                    </select>
                                    <input type="hidden" name="asset_type_id" value="1"> <!-- Set the default value here -->
                                </div>

                                <div class="form-group">
                                    <label for="ImageName">Image Name:</label>
                                    <input type="text" name="ImageName" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="ImageDescription">Image Description:</label>
                                    <textarea name="ImageDescription" class="form-control"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="Price">Price:</label>
                                    <input type="number" name="Price" class="form-control" min="0">
                                </div>
                        
                                <div class="form-group">
                                    <label for="imageFile">Upload Image:</label>
                                    <input type="file" name="imageFile" class="form-control-file" required>
                                </div>
                        
                                <div class="form-group">
                                    <label for="watermarkFile">Upload Watermark Image:</label>
                                    <input type="file" name="watermarkFile" class="form-control-file">
                                </div>


                                <div class="form-group">
                                    <label for="category_ids">Categories:</label>
                                    @foreach ($categories as $category)
                                        <div class="form-check">
                                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                                class="form-check-input" >
                                            <label class="form-check-label">{{ $category->cat_name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">Create ImageAsset</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

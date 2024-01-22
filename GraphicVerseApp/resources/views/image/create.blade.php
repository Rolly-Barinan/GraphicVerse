<!-- resources/views/imageassets/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Create ImageAsset') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('image.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="userID">User ID:</label>
                                <input type="text" name="userID" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="assetTypeID">Asset Type ID:</label>
                                <input type="text" name="assetTypeID" class="form-control" required>
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
                                <label for="Location">Location:</label>
                                <input type="text" name="Location" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="Price">Price:</label>
                                <input type="text" name="Price" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="ImageSize">Image Size:</label>
                                <input type="text" name="ImageSize" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="watermarkedImage">Watermarked Image:</label>
                                <input type="text" name="watermarkedImage" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Create ImageAsset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

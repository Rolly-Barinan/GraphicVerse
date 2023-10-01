@extends('layouts.app') 
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create New Asset Package</div>
                <div class="card-body">
                    <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="package_id">Select a Package (or create a new one):</label>
                            <select name="package_id" class="form-control">
                                <option value="">Create a New Package</option>
                                @foreach ($packages as $package)
                                    <option value="{{ $package->id }}">{{ $package->PackageName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="PackageName">Package Name:</label>
                            <input type="text" name="PackageName" class="form-control" value="New Package">
                        </div>

                        <div class="form-group">
                            <label for="Description">Package Description:</label>
                            <textarea name="Description" class="form-control">Description of the new package</textarea>
                        </div>

                        <div class="form-group">
                            <label for="preview">Package Preview:</label>
                            <input type="text" name="preview" class="form-control" value="default_preview_value">
                        </div>

                        <div class="form-group">
                            <label for="Location">Package Location:</label>
                            <input type="text" name="Location" class="form-control" value="default_location_value">
                        </div>

                        <div class="form-group">
                            <label for="images">Upload Images:</label>
                            <input type="file" name="images[]" multiple class="form-control-file">
                        </div>

                        <button type="submit" class="btn btn-primary">Upload Images</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

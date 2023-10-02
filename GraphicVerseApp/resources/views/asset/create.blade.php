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
                            <input type="text" name="PackageName" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="Description">Package Description:</label>
                            <textarea name="Description" class="form-control">Description of the new package</textarea>
                        </div>

                        <div class="form-group">
                            <label for="preview">Package Preview:</label>
                            <input type="file" name="preview" class="form-control" class="form-control-file">
                        </div>


                        <div class="form-group">
                            <label for="asset">Upload Asset:</label>
                            <input type="file" name="asset[]" multiple class="form-control-file">
                        </div>

                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

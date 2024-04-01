@extends('layouts.app')

@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Asset Package</div>
                <div class="card-body">
                    <form action="{{ route('asset.update', $package->id) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label for="asset_type_id">Asset Type:</label>
                            <select name="asset_type_id" class="form-control">
                                <option value="">Select an Asset Type</option>
                                @foreach ($assetTypes as $assetType)
                                    <option value="{{ $assetType->id }}" {{ $assetType->id == old('asset_type_id', $package->asset_type_id) ? 'selected' : '' }}>
                                        {{ $assetType->asset_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="PackageName">Package Name:</label>
                            <input type="text" name="PackageName" class="form-control" value="{{ old('PackageName', $package->PackageName) }}">
                        </div>

                        <div class="form-group">
                            <label for="preview">Package Preview:</label>
                            <input type="file" name="preview" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="Description">Package Description:</label>
                            <textarea name="Description" class="form-control">{{ old('Description', $package->Description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="Price">Price (Leave empty for free download):</label>
                            <input type="number" name="Price" class="form-control" value="{{ old('Price', $package->Price) }}">
                        </div>

                        <div class="form-group">
                            <label for="category_ids">Categories:</label>
                            @foreach ($categories as $category)
                                <div class="form-check">
                                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="form-check-input"
                                        {{ in_array($category->id, old('category_ids', $package->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $category->cat_name }}</label>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">Update Package</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

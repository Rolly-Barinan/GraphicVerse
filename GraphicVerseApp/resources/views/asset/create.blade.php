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
                                <label for="PackageName">Package Name:</label>
                                <input type="text" name="PackageName" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="Description">Package Description:</label>
                                <textarea name="Description" class="form-control" required>Description of the new package</textarea>
                            </div>

                            <div class="form-group">
                                <label for="preview">Package Preview:</label>
                                <input type="file" name="preview" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="Price">Price (Leave empty for free download):</label>
                                <input type="number" name="Price" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="asset">Upload Asset:</label>
                                <input type="file" name="asset[]" multiple class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="asset_type_id">Asset Type:</label>
                                <select name="asset_type_id" class="form-control" required>
                                    <option value="">Select an Asset Type</option>
                                    @foreach ($assetTypes as $assetType)
                                        <option value="{{ $assetType->id }}">{{ $assetType->asset_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <script>
                                function validateForm() {
                                    const checkboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');

                                    if (checkboxes.length === 0) {
                                        alert("Please select at least one category.");
                                        return false;
                                    }

                                    // If at least one category is selected, the form will submit
                                    return true;
                                }
                            </script>
                            
                            <div class="form-group">
                                <label for="category_ids">Categories:</label>
                                @foreach ($categories as $category)
                                    <div class="form-check">
                                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                            class="form-check-input">
                                        <label class="form-check-label">{{ $category->cat_name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

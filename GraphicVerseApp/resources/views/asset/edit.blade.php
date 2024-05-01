<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<link href="{{ asset('css/create.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css" rel="stylesheet">
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
    
    <script>
        function hideAlerts() {
            var successAlert = document.getElementById('successAlert');
            var errorAlert = document.getElementById('errorAlert');

            if (successAlert) {
                setTimeout(function() {
                    successAlert.style.display = 'none';
                }, 3000);
            }

            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 3000);
            }
        }

        window.onload = function() {
            hideAlerts();
        };

        function validateForm() {
            var assetType = document.querySelector('select[name="asset_type_id"]');
            var packageName = document.querySelector('input[name="PackageName"]');
            var preview = document.querySelector('input[name="preview"]');
            var description = document.querySelector('textarea[name="Description"]');
            var price = document.querySelector('input[name="Price"]');
            var categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');

            if (!assetType.value || !packageName.value || !description.value || !categoryCheckboxes.length) {
                alert("Please fill in all required fields.");
                return false;
            }

            if (preview.files.length) {
                var allowedExtensions = ['jpg', 'jpeg', 'png'];
                var previewExtension = preview.files[0].name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(previewExtension)) {
                    alert("Preview file must be a JPEG, JPG, PNG or GIF.");
                    return false;
                }
            }

            return true;
        }
    </script>

<div class="container-fluid d-block">
    @if ($errors->any())
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                <li>{{ $errors->first() }}</li>
            </ul>
        </div>
        <script>
            setTimeout(function() {
                $('#errorAlert').fadeOut();
            }, 6000);
        </script>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-7">
            <form action="{{ route('asset.update', $package->id) }}" method="POST" enctype="multipart/form-data"
                onsubmit="return validateForm()">
                @csrf
                @method('PATCH')
                <h1 class="mb-4">Edit Package</h1>
                <div class="form-group">
                    <input type="file" name="asset[]" multiple class="form-control d-none" id="asset">
                </div>
                <div class="image-container mx-auto d-block">
                    <img id="mainImage" src="{{ Storage::url($package->Location) }}" class="card-img-top main-image " style="height: 500px;"
                        alt="{{ $package->PackageName }}">
                </div>
                <div class="form-group">
                    <h3 class="desc">Description</h3>
                    <textarea name="Description" class="description form-control" oninput="limitInput(this)">{{ old('Description', $package->Description) }}</textarea>
                    <p class="tiny-text">10-200 characters</p>
                </div>
                <div class="form-group">
                    <h3 class="desc">Custom Tags</h3>
                    <input type="text" name="customTags" id="customTags" class="form-control"
                        placeholder="Enter custom tags separated by commas">
                </div>
        </div>
        <div class="col-md-5">
                <div class="form-group">
                    <div class="input-icon">
                        <input type="text" name="PackageName" class="title" value="{{ old('PackageName', $package->PackageName) }}">
                        <i class="bi bi-pencil"></i>
                    </div>
                </div>
                <div class="form-group">
                    <h3 class="desc">Price</h3>
                    <input type="number" name="Price" class="price form-control" min="0" value="{{ old('Price', $package->Price) }}">
                </div>
                <div class="form-group">
                    <h3 class="desc">Team</h3>
                    <select name="asset_type_id" class="form-control">
                        @foreach ($assetTypes as $assetType)
                            <option value="{{ $assetType->id }}" {{ $assetType->id == old('asset_type_id', $package->asset_type_id) ? 'selected' : '' }}>
                                {{ $assetType->asset_type }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <h3 class="desc">Category</h3>
                    <select name="asset_type_id" class="form-control">
                        <option value="">Select an Asset Type</option>
                        @foreach ($assetTypes as $assetType)
                            <option value="{{ $assetType->id }}">{{ $assetType->asset_type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <h3 class="desc">Categories:</h3>
                    @foreach ($categories as $category)
                        <div class="form-check">
                            <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                class="form-check-input" {{ in_array($category->id, old('category_ids', $package->categories->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $category->cat_name }}</label>
                        </div>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-primary">Update Package</button>
            </form>
        </div>
    </div>
</div>
@endsection

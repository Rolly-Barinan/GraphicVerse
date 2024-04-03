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
                }, 3000); // 3000 milliseconds = 3 seconds
            }

            if (errorAlert) {
                setTimeout(function() {
                    errorAlert.style.display = 'none';
                }, 3000); // 3000 milliseconds = 3 seconds
            }
        }
        // Call the hideAlerts function when the page loads
        window.onload = function() {
            hideAlerts();
        };

        function validateForm() {
            var assetFiles = document.querySelector('input[name="asset[]"]');
            var assetType = document.querySelector('select[name="asset_type_id"]');
            var packageName = document.querySelector('input[name="PackageName"]');
            var preview = document.querySelector('input[name="preview"]');
            var description = document.querySelector('textarea[name="Description"]');
            var price = document.querySelector('input[name="Price"]');
            var categoryCheckboxes = document.querySelectorAll('input[name="category_ids[]"]:checked');

            if (!assetFiles.files.length || !assetType.value || !packageName.value || !description.value || !
                categoryCheckboxes.length) {
                alert("Please fill in all required fields.");
                return false;
            }
            // Check if the preview file is not a JPEG or PNG
            if (preview.files.length) {
                var allowedExtensions = ['jpg', 'jpeg', 'png'];
                var previewExtension = preview.files[0].name.split('.').pop().toLowerCase();

                if (!allowedExtensions.includes(previewExtension)) {
                    alert("Preview file must be a JPEG, JPG, PNG or GIF.");
                    return false;
                }
            }
            // Additional custom validation if needed...
            return true;
        }
    </script>
    <div class="container">
        @if ($errors->any())
            <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    <li>{{ $errors->first() }}</li>
                </ul>
            </div>
            <script>
                // Automatically remove the error alert after 3 seconds
                setTimeout(function() {
                    $('#errorAlert').fadeOut();
                }, 6000);
            </script>
        @endif
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New Asset Package</div>
                    <div class="card-body">
                        <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data"
                            onsubmit="return validateForm()">
                            @csrf

                            <div class="form-group">
                                <label for="asset">Upload Asset:</label>
                                <input type="file" name="asset[]" multiple class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="asset_type_id">Asset Type:</label>
                                <select name="asset_type_id" class="form-control">
                                    <option value="">Select an Asset Type</option>
                                    @foreach ($assetTypes as $assetType)
                                        <option value="{{ $assetType->id }}">{{ $assetType->asset_type }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="PackageName">Package Name:</label>
                                <input type="text" name="PackageName" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="preview">Package Preview:</label>
                                <input type="file" name="preview" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="Description">Package Description:</label>
                                <textarea name="Description" class="form-control">Description of the new package</textarea>
                            </div>


                            <div class="form-group">
                                <label for="Price">Price (Leave empty for free download):</label>
                                <input type="number" name="Price" class="form-control" min="0">
                            </div>
                            


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

@extends('layouts.adminlayout')

@section('admin-content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right mb-4">
                        <h2>Artwork Information</h2>
                    </div>
                </div>
            </div>
            <div class="card rounded" style="border-left: 10px solid #333;">
                <div class="card-body">
                    <table class="table">
                        <tbody>   
                            <div class="d-flex justify-content-center mb-4"> <!-- Centering the image -->
                                <a href="{{ Storage::url($image->watermarkedImage) }}" target="_blank">
                                    <img src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top"
                                        alt="{{ $image->ImageName }}">
                                </a>
                            </div>
                            <tr>
                                <th>Image ID:</th>
                                <td>{{ $image->id }}</td>
                            </tr>
                            <tr>
                                <th>Belongs to User:</th>
                                <td>{{ $image->user->username }}</td>
                            </tr>
                            <tr>
                                <th>Package Name:</th>
                                <td>{{ $image->ImageName }}</td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $image->ImageDescription }}</td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td>${{ $image->Price}}</td>
                            </tr>
                            <tr>
                                <th>Asset type:</th>
                                <td>{{ $image->assetType->asset_type }}</td>
                            </tr>
                            <tr>
                                <th>Image size:</th>
                                <td>{{ $image->ImageSize }}</td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $image->created_at->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At:</th>
                                <td>{{ $image->updated_at->format('Y-m-d') }}</td>
                            </tr>
                            <!-- Add more user details as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.imageAssets') }}" class="btn btn-primary">Back to Artworks</a>
                <a href="{{ route('admin.deleteImage' , $image->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this image?')">Delete Artwork</a>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.adminlayout')

@section('admin-content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right mb-4">
                        <h2>Package Information</h2>
                    </div>
                </div>
            </div>
            <div class="card rounded" style="border-left: 10px solid #333;">
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Package ID:</th>
                                <td>{{ $package->id }}</td>
                            </tr>
                            <tr>
                                <th>Belongs to User ID:</th>
                                <td>{{ $package->UserID }}</td>
                            </tr>
                            <tr>
                                <th>Package Name:</th>
                                <td>{{ $package->PackageName }}</td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $package->Description }}</td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td>{{ $package->Price }}</td>
                            </tr>
                            <tr>
                                <th>Asset type:</th>
                                <td>{{ $package->assetType->asset_type }}</td>
                            </tr>
                            <tr>
                                <th>Number of assets in this package:</th>
                                <td>{{ $userUploadsCountAssets }}</td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $package->created_at->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At:</th>
                                <td>{{ $package->updated_at->format('Y-m-d') }}</td>
                            </tr>
                            <!-- Add more user details as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Assets Information -->
            @if($userUploadsCountAssets > 0)
            <div class="mt-4">
                <h3>Assets in this Package</h3>
                <div class="card rounded" style="border-left: 10px solid #333;">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Asset Name</th>
                                    <th>File Type</th>
                                    <th>File Size</th>
                                    <!-- Add more asset fields if needed -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($package->assets as $asset)
                                <tr>
                                    <td>{{ $asset->AssetName }}</td>
                                    <td>{{ $asset->FileType }}</td>
                                    <td>{{ $asset->FileSize }}</td>
                                    <!-- Add more asset fields if needed -->
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Back and Delete Buttons -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.packages') }}" class="btn btn-primary">Back to Packages</a>
                <a href="{{ route('admin.deletePackage' , $package->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this package?')">Delete Package?</a>
            </div>
        </div>
    </div>
</div>
@endsection

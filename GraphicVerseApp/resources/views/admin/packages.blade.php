@extends('layouts.adminlayout')

@section('admin-content')
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right mb-4">
                    <h2>PACKAGES</h2>
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <div class="card rounded" style="border-left: 10px solid #333;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Package ID</th>
                                <th>User ID</th>
                                <th>Package Name</th>
                                <th>Asset Type</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($packages->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">No packages found</td>
                                </tr>
                            @else
                                @foreach ($packages as $package)
                                    <tr>
                                        <td>{{ $package->id }}</td>
                                        <td>{{ $package->UserID }}</td>
                                        <td>{{ $package->PackageName }}</td>
                                        <td>{{ $package->assetType->asset_type }}</td>
                                        <td>{{ $package->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $package->updated_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.userDetails', $package->id) }}" class="btn btn-success">
                                                View Details
                                            </a>                                        
                                        </td>  
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $packages->links('pagination::bootstrap-4') }}
        </div>

    </div>
</body>
@endsection
@extends('layouts.adminlayout')

@section('admin-content')
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-6 margin-tb">
                <div class="pull-left mb-4">
                    <h2>IMAGE ASSETS</h2>
                </div>
            </div>
            <div class="col-lg-6 margin-tb">
                <div class="pull-right mb-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by image asset name">
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
                                <th>Image ID</th>
                                <th>User ID</th>
                                <th>Image Name</th>
                                <th>Asset Type</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="imageTableBody">
                            @if ($images->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">No images found</td>
                                </tr>
                            @else
                                @foreach ($images as $image)
                                    <tr>
                                        <td>{{ $image->id }}</td>
                                        <td>{{ $image->userID }}</td>
                                        <td>{{ $image->ImageName }}</td>
                                        <td>{{ $image->assetType->asset_type }}</td>
                                        <td>{{ $image->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $image->updated_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.imageDetails', $image->id) }}" class="btn btn-success">
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
        <div class="d-flex justify-content-center mt-5">
            {{ $images->links('pagination::bootstrap-4') }}
        </div>

    </div>

    <script>
        // Add event listener for keyup event on search input
        document.getElementById('searchInput').addEventListener('keyup', function(event) {
            // Get the search query
            var searchQuery = event.target.value.toLowerCase();

            // Send AJAX request to search for images
            $.ajax({
                url: '{{ route("admin.imageSearch") }}',
                type: 'GET',
                data: { q: searchQuery },
                success: function(response) {
                    $('#imageTableBody').html(response);
                }
            });
        });
    </script>
</body>
@endsection

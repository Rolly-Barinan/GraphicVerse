@extends('layouts.adminlayout')

@section('admin-content')
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-6 margin-tb">
                <div class="pull-left mb-4">
                    <h2>USERS</h2>
                </div>
            </div>
            <div class="col-lg-6 margin-tb">
                <div class="pull-right mb-4">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by username">
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
                                <th>ID</th>
                                <th>Username</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @if ($users->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No users found</td>
                                </tr>
                            @else
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.userDetails', $user->id) }}" class="btn btn-success">
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
            {{ $users->links('pagination::bootstrap-4') }}
        </div>

    </div>

    <script>
        // Add event listener for keyup event on search input
        document.getElementById('searchInput').addEventListener('keyup', function(event) {
            // Get the search query
            var searchQuery = event.target.value.toLowerCase();

            // Send AJAX request to search for users
            $.ajax({
                url: '{{ route("admin.userSearch") }}',
                type: 'GET',
                data: { q: searchQuery },
                success: function(response) {
                    $('#userTableBody').html(response);
                }
            });
        });
    </script>
</body>
@endsection

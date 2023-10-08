@extends('layouts.adminlayout')

@section('admin-content')
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right mb-4">
                    <h2>Users</h2>
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
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.deleteCategory', $user->id) }}" class="btn btn-success">
                                            View Details
                                        </a>                                        
                                        <span style="margin: 0 10px;"> <!-- Add space between icons -->
                                            <a href="{{ route('admin.deleteCategory', $user->id) }}" onclick="return confirm('Are you sure you want to delete this category?')" style="text-decoration: none; color: red;">
                                                <i class="fas fa-trash-alt"></i> <!-- Font Awesome delete icon -->
                                            </a>
                                        </span>
                                    </td>  
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>

    </div>
</body>
@endsection
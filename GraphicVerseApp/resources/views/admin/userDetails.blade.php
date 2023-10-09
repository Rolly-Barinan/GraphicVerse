@extends('layouts.adminlayout')

@section('admin-content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right mb-4">
                        <h2>User Information</h2>
                    </div>
                </div>
            </div>
            <div class="card rounded" style="border-left: 10px solid #333;">
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>ID:</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>Username:</th>
                                <td>{{ $user->username }}</td>
                            </tr>
                            <tr>
                                <th>Name:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $user->profile->description ?? 'N/A'}}</td>
                            </tr>
                            <tr>
                                <th>URL:</th>
                                <td>{{ $user->profile->url ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>2D assets uploaded:</th>
                                <td>{{ $userUploadsCount2D }}</td>
                            </tr>
                            <tr>
                                <th>3D assets uploaded:</th>
                                <td>{{ $userUploadsCount3D }}</td>
                            </tr>
                            <!-- Add more user details as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
            <a href="{{ route('admin.users') }}" class="btn btn-primary mt-4">Back to Users</a>
            <span style="margin: 0 50px;">
                <a href="{{ route('admin.deleteUser' , $user->id) }}" class="btn btn-danger mt-4" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</a>
            </span>
        </div>
    </div>
</div>
@endsection

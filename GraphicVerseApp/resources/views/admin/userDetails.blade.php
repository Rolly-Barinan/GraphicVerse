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
                    <div class="row">
                        <!-- Display cover photo -->
                        <div class="col-md-12 mb-4">
                            <div class="cover-photo" style="background-image: url('{{ $user->profile->coverImage() }}'); background-size: cover; background-repeat: no-repeat; background-position: center; height: 200px;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Display profile photo -->
                        <div class="col-md-3 d-flex justify-content-start align-items-center">
                            <div class="profile-photo">
                                <img src="{{ $user->profile->profileImage() }}" class="rounded-circle img-fluid" alt="User Profile Image" style="max-width: 150px;">
                            </div>
                        </div>
                        <div class="col-md-9">
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
                                        <th>Number of packages uploaded:</th>
                                        <td>{{ $userUploadsCountPackages }}</td>
                                    </tr>
                                    <tr>
                                        <th>Number of assets uploaded:</th>
                                        <td>{{ $userUploadsCountAssets }}</td>
                                    </tr>
                                    <tr>
                                        <th>Number of artworks uploaded:</th>
                                        <td>{{ $userUploadsCountArtworks }}</td>
                                    </tr>
                                    <!-- Add more user details as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.users') }}" class="btn btn-primary">Back to Users</a>
                <a href="{{ route('admin.deleteUser' , $user->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete User</a>
            </div>
        </div>
    </div>
</div>
@endsection

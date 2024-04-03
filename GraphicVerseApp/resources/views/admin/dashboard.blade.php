@extends('layouts.adminlayout')

@section('admin-content')
    <style>
        /* Custom CSS for Dashboard */
        .dashboard-container {
            background-color: #f7f7f7;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            font-size: 24px;
            font-weight: bold;
        }
    </style>

    <div class="container mt-5">
        <div class="dashboard-container" style="border-left: 25px solid #333;">
            <h2>ADMIN DASHBOARD</h2>
            <hr>
            <h4>Welcome!</h4>
            <p class="mb-5" style="font-style: italic">Monitor users, categories, and different types of assets. Effortlessly add, edit, or delete with ease.</p>
            <div class="row justify-content-center"> <!-- Modified: justify-content-center added -->
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Categories</h3>
                        <p>{{ $categories->count() }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Users</h3>
                        <p>{{ $users->count() }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Packages</h3>
                        <p>{{ $packages->count() }}</p>
                        {{-- <p>{{ $models2D->count() }}</p> --}}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Assets</h3>
                        <p>{{ $assets->count() }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Images</h3>
                        <p>{{ $images->count() }}</p>
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="dashboard-card" style="border-left: 10px solid #333;">
                        <h3>Total Audios</h3>
                        <p>{{ $packages->where('asset_type_id', 1)->count() }}</p>
                    </div>
                </div>                 --}}
                <!-- Add more cards for other statistics if needed -->
            </div>
        </div>
    </div>
@endsection
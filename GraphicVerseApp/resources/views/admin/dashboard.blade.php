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

    .dashboard-title {
        font-size: 24px;
        margin-bottom: 20px;
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

<div class="container mt-4">
    <div class="dashboard-container" style="border-left: 10px solid #333;">
        <h2 class="dashboard-title">Dashboard</h2>
        
        <div class="row">
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
                    <h3>Total 2D Assets</h3>
                    <p>{{ $models2D->count() }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card" style="border-left: 10px solid #333;">
                    <h3>Total 3D Assets</h3>
                    <p>{{ $models3D->count() }}</p>
                </div>
            </div>
            <!-- Add more cards for other statistics if needed -->
        </div>
    </div>
</div>
@endsection

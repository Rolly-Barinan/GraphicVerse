@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Purchases</h1>
        <h2>package</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Package</th>
                  
                    <th>Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($packages as $package)
                    <tr>
                        <td>{{ $package->id }}</td>                       
                        <td>{{ $package->package_id }}</td>
                        <td>{{ $package->price }}</td>
                        <td>{{ $package->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <h2>artowrk</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>artwork</th>
                  
                    <th>Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($artworks as $artwork)
                    <tr>
                        <td>{{ $artwork->id }}</td>                       
                        <td>{{ $artwork->artwork_id }}</td>
                        <td>{{ $artwork->price }}</td>
                        <td>{{ $artwork->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

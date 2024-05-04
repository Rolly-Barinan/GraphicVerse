@extends('layouts.app')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link href="{{ asset('css/purchaseHistory.css') }}" rel="stylesheet">
@section('content')
    <div class="container-fluid d-block">
        <div class="row">
            <div class="col-md-6" style="background-color: white; min-height: 100vh;">
                <h1 class="w-100">PACKAGE PURCHASES</h1>
                @if ($packages->isEmpty())
                    <p class="text-center pt-4">You have no package purchases.</p>
                @else
                    <table class="table" id="package-table"> <!-- Add unique id to the table -->
                        <thead>
                            <tr>
                                <th>Transaction #</th>
                                <th>Transaction Date</th>
                                <th>Asset</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($packages as $package)
                                <tr>
                                    <td>{{ $package->id }}</td>
                                    <td>{{ $package->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="asset-details">
                                            <div class="image-container">
                                                @if ($package->package->asset_type_id == 1)
                                                    <a href="{{ route('twoDim.show', ['id' => $package->package->id]) }}">
                                                        <img src="{{ Storage::url($package->package->Location) }}"
                                                            alt="Package Image" class="contained-image">{{ $package->package->PackageName }}
                                                    </a>
                                                @elseif ($package->package->asset_type_id == 2)
                                                    <a href="{{ route('threeDim.show', ['id' => $package->package->id]) }}">
                                                        <img src="{{ Storage::url($package->package->Location) }}"
                                                            alt="Package Image" class="contained-image">
                                                    </a>
                                                @elseif ($package->package->asset_type_id == 3)
                                                    <a href="{{ route('audio.show', ['id' => $package->package->id]) }}">
                                                        <img src="{{ Storage::url($package->package->Location) }}"
                                                            alt="Package Image" class="contained-image">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="asset-name">{{ $package->package->PackageName }}</div> 
                                           
                                        </div>
                                    </td>
                                    <td>${{ $package->price }}</td>
                                </tr>   
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
            <div class="col-md-6">
                <h1 class="w-100">ARTWORK PURCHASES</h1>
                @if ($artworks->isEmpty())
                    <p class="text-center pt-4">You have no artwork purchases.</p>
                @else
                    <table class="table" id="artwork-table"> <!-- Add unique id to the table -->
                        <thead>
                            <tr>
                                <th>Transaction #</th>
                                <th>Transaction Date</th>
                                <th>Asset</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($artworks as $artwork)
                                <tr>
                                    <td>{{ $artwork->id }}</td>
                                    <td>{{ $artwork->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <div class="asset-details">
                                            <div class="image-container">
                                               <a href="{{ route('image.show', ['id' => $artwork->artwork->id]) }}">
                                                <img src="{{ Storage::url($artwork->artwork->Location) }}"
                                                alt="Artwork Image" class="contained-image">
                                               </a>
                                            </div>
                                            <div class="asset-name">{{ $artwork->artwork->ImageName }}</div>
                        
                                        </div>
                                    </td>
                                    <td>${{ $artwork->price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
@endsection

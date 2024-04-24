@extends('layouts.app')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@section('content')
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include Bootstrap's JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <div class="cover-photo" style="background-image: url('{{ $user->profile->coverImage() }}')"></div>
<div class="container-fluid py-50">
    <!-- <div class="row">
        <div class="col-12 col-md-3 p-2 align-items-start">
            
        </div>
    </div> -->
    <div class="container-fluid d-lg-inline">
        <div class="row">
            <div class="col-6 user-info-1 d-flex flex-row justify-content-start">
                <div class="rounded-circle-container">
                    <img src="{{ $user->profile->profileImage() }}" class="rounded-circle img-fluid" alt="User Profile Image">
                </div>
                <div class="text1">
                <h1 class="name">{{ $user->name }}</h1>
                <p class="username">{{ $user->profile->title }}</p>
                <!-- <p class="description">{{ $user->profile->description }}</p> -->
                </div>
            </div>
            <div class="col-6 d-flex justify-content-end">        
                            <a href="{{ route('asset.create') }}">
                                <button type="button" class="uploadBtn">
                                    Upload Package
                                </button>
                            </a>
                            <a href="{{ $user->profile->url ?? '/profile/' . $user->id . '/edit' }}">
                                <button type="button" class="connectBtn">
                                    {{ $user->profile->url ? 'Connect' : 'Edit' }}
                                </button>
                            </a>
            </div>
        </div>
        <div class="row" style="margin-top: -100px !important;">
            <div class="col-9">
                <div class="scrollable-column packages_column">
                    <div class="row package_row">
                        <h1 class="text-start w-100">2D ASSETS</h1>
                    </div>
                    <div class="image-scroll-container carousel overflow-auto" id="assetCarousel2D" data-interval="false">
                        <div class="carousel-inner">
                            @foreach ($user->packages->where('assetType.asset_type', '2D')->chunk(4) as $chunk)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="row">
                                        @foreach ($chunk as $asset)
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <a href="{{ route('twoDim.show', ['id' => $asset->id]) }}">
                                                        <img src="{{ Storage::url($asset->Location) }}" class="card-img-top" alt="{{ $asset->PackageName }}">
                                                        <div class="card-body p-1">
                                                            <h5 class="card-title">{{ $asset->PackageName }}</h5>
                                                            <p class="card-text">{{ $asset->user->username }}</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#assetCarousel2D" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#assetCarousel2D" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex">
            <div class="col-9">
                <div class="scrollable-column packages_column">
                    <div class="row package_row">
                        <h1 class="text-start w-100">3D ASSETS</h1>
                    </div>
                    <div class="image-scroll-container carousel overflow-auto" id="assetCarousel" data-interval="false">
                        <div class="carousel-inner">
                            @foreach ($user->packages->where('assetType.asset_type', '3D')->chunk(4) as $chunk)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                    <div class="row">
                                        @foreach ($chunk as $asset)
                                            <div class="col-md-3">
                                                <div class="card">
                                                <a href="{{ route('threeDim.show', ['id' => $asset->id]) }}">
                                                    <div class="card-image"><img src="{{ Storage::url($asset->Location) }}" class="card-img-top" alt="{{ $asset->PackageName }}"></div>
                                                        <div class="card-body p-1">
                                                            <h5 class="card-title">{{ $asset->PackageName }}</h5>
                                                            <p class="card-text">{{ $asset->user->username }}</p>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#assetCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#assetCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-3">
            <div class="card h-100 d-flex align-self-start" style="margin-top: -382px !important; border-style: none !important"> <!-- Add .d-flex and .align-items-start to the .card div -->
                    <div class="card-body">
                        <h1 class="card-title p-1">TEAMS</h1>
                        <div class="card-text">
                                @foreach ($user->teams as $team)
                                    <!-- <a href="{{ route('teams.create', ['id' => $team->id]) }}">
                                        <p class="team-name">{{ $team->name }}</p>
                                    </a> -->
                                @endforeach
                            @if ($user->teams->isEmpty())
                                <a href="{{ route('teams.create') }}">Create a team</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



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
                <button type="button" class="uploadBtn" data-toggle="modal" data-target="#uploadPackageModal">
                    Upload Package
                </button>
                <a href="{{ $user->profile->url ?? '/profile/' . $user->id . '/edit' }}">
                    <button type="button" class="connectBtn">
                        {{ $user->profile->url ? 'Connect' : 'Edit' }}
                    </button>
                </a>
            </div>
        </div>
        <!-- Upload Package Modal -->
        <div class="modal fade" id="uploadPackageModal" tabindex="-1" role="dialog" aria-labelledby="uploadPackageModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadPackageModalLabel">Upload Package</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body m-auto">
                        <!-- Add your upload package form here -->
                        <a href="{{ route('asset.create') }}">
                            <button type="button" class="uploadBtn">
                                Upload Assets
                            </button>
                        </a>
                        <a href="{{ route('image.create') }}">
                            <button type="button" class="uploadBtn">
                                Upload Artwork
                            </button>
                        </a>
                    </div>
                </div>
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
                            @if ($user->packages->where('assetType.asset_type', '2D')->isEmpty())
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body p-1 justify-center">
                                                    <h5 class="card-title">No assets found</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
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
                            @endif
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
            <div class="col-3">
                <h1 class="text-start w-100">TEAMS</h1>
                <hr>
                @foreach ($user->teams as $team)
                    <a href="{{ route('teams.create', ['id' => $team->id]) }}" class="team-link">
                        <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 32px; background-color: {{ $team->color }};">
                            @php
                                $words = explode(" ", $team->name); // Split the team name into an array of words

                                if (count($words) === 1) {
                                    echo strtoupper(substr($team->name, 0, 3)); // Use the first three letters for single-word team names
                                } else {
                                    foreach ($words as $word) {
                                        echo strtoupper(substr($word, 0, 1)); // Output the first letter of each word for multi-word team names
                                    }
                                }
                            @endphp
                        </div>
                        <p class="team-name">{{ $team->name }}</p>
                    </a>
                    <hr>
                @endforeach
                <a href="{{ route('teams.create') }}" class="team-link">
                @if ($user->teams->isEmpty())
                    <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 32px; background-color: #5F5F79;">
                        +
                    </div>
                    <p class="team-name">Create a team</p>
                @endif
                </a>
                <hr>
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
                        @if ($user->packages->where('assetType.asset_type', '3D')->isEmpty())
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-body p-1 justify-center">
                                                <h5 class="card-title">No assets found</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
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
                        @endif
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
            <div class="col-9">
                <div class="scrollable-column packages_column">
                    <div class="row package_row">
                        <h1 class="text-start w-100">AUDIO ASSETS</h1>
                    </div>
                    <div class="image-scroll-container carousel overflow-auto" id="assetCarouselAudio" data-interval="false">
                        <div class="carousel-inner">
                            @if ($user->packages->where('assetType.asset_type', 'Audio')->isEmpty())
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body p-1 justify-center">
                                                    <h5 class="card-title">No assets found</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach ($user->packages->where('assetType.asset_type', 'Audio')->chunk(4) as $chunk)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <div class="row">
                                            @foreach ($chunk as $asset)
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <a href="{{ route('audio.show', ['id' => $asset->id]) }}">
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
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#assetCarouselAudio" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#assetCarouselAudio" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <div class="scrollable-column packages_column">
                    <div class="row package_row">
                        <h1 class="text-start w-100">ARTWORKS</h1>
                    </div>
                    <div class="image-scroll-container carousel overflow-auto" id="assetCarouselArtwork" data-interval="false">
                        <div class="carousel-inner">
                            @if ($user->images->where('assetType.asset_type', '2D')->isEmpty())
                                <div class="carousel-item active">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="card-body p-1 justify-center">
                                                    <h5 class="card-title">No artworks found</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @foreach ($user->images->where('assetType.asset_type', '2D')->chunk(4) as $chunk)
                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                        <div class="row">
                                            @foreach ($chunk as $asset)
                                                <div class="col-md-3">
                                                    <div class="card">
                                                        <a href="{{ route('image.show', ['id' => $asset->id]) }}">
                                                            <img src="{{ Storage::url($asset->Location) }}" class="card-img-top" alt="{{ $asset->ImageName }}">
                                                            <div class="card-body p-1">
                                                                <h5 class="card-title">{{ $asset->ImageName }}</h5>
                                                                <p class="card-text">{{ $asset->user->username }}</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#assetCarouselAudio" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#assetCarouselAudio" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
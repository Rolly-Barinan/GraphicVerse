@extends('layouts.app')

@section('content')

    <div class="container-fluid">
        <h1>Package Details</h1>

        <div class="card">
            <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
            <div class="card-body">
                <h5 class="card-title">{{ $package->PackageName }}</h5>
                <p class="card-text">{{ $package->Description }}</p>
                <p>Price: ${{ $package->Price }}</p>
            </div>
        </div>

        <h2>Assets in this Package</h2>

        @if ($assets)
            <div class="audio-cards">
                <div class="container-fluid text-center">
                    <div class="row">
                        @foreach ($assets as $asset)
                            <div class="col">
                                <div class="card" style="width: 22rem;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $asset->AssetName }}</h5>
                                        <audio controls class="audio-player" id="audio-{{ $asset->id }}">
                                            <source src="{{ Storage::url($asset->Location) }}" type="audio/mpeg">
                                            Your browser does not support the audio element.
                                        </audio>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <p>No assets found for this package.</p>
        @endif







        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var players = document.querySelectorAll('.audio-player');

                players.forEach(function(player) {
                    player.addEventListener('play', function(event) {
                        stopOtherPlayers(player);
                    });
                });

                function stopOtherPlayers(currentPlayer) {
                    players.forEach(function(player) {
                        if (player !== currentPlayer && !player.paused) {
                            player.pause();
                        }
                    });
                }
            });
        </script>
    </div>
@endsection

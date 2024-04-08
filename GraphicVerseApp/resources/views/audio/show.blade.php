@extends('layouts.app')

@section('content')
    <div class="">
        @if (Auth::id() == $package->UserID)
        <a href="/package/{{ $package->id }}/edit">
            <img src="/svg/edit.svg" class="logo" alt="Edit Logo">
        </a>
        <form action="{{ route('asset.destroy', $package->id) }}" method="POST"
            onsubmit="return confirm('Are you sure you want to delete this package?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Package</button>
        </form>
    @endif
        <h1>Package Details</h1>
        <div class="card">
            <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
            <div class="card-body">
                <h5 class="card-title">{{ $package->PackageName }}</h5>
                <p class="card-text">{{ $package->Description }}</p>
                @if ($package->Price != null && $package->Price != 0)
                    <p>Price: ${{ $package->Price }}</p>
                @endif
                <p>File Types: {{ implode(', ', $fileTypes->toArray()) }}</p>
                <p>File Size: {{ number_format($totalSizeMB, 2) }}mb</p>
                <p>Created By: {{ $user->name }}</p>
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
        @if ($package->Price == null || $package->Price == 0 || $package->UserID == auth()->id())
            <a href="{{ route('asset.download', $package->id) }}" class="btn btn-success">Download</a>
        @else
            <form action="{{ route('paypal') }}" method="POST">
                @csrf
                <input type="hidden" name="price" value="{{ $package->Price }}">
                <button type="submit" class="btn btn-primary">Pay with PayPal</button>
            </form>
        @endif
        <a href="/audio-models" class="btn btn-secondary">Back</a>

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

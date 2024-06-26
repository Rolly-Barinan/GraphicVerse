<div class="col-md-4 mb-4">
    @foreach ($packages as $package)
    @if ($package->assetType && $package->assetType->asset_type === 'audio')
        <div class="card">
            <a href="{{ route('audio.show', ['id' => $package->id]) }}">
                <img src="{{ Storage::url($package->Location) }}" class="card-img-top"
                    alt="{{ $package->PackageName }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $package->PackageName }}</h5>
                    <p class="card-text">{{ $package->Description }}</p>
                </div>
            </a>
        </div>
        @endif
    @endforeach
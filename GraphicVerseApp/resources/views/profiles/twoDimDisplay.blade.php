<div class="scrollable-column packages_column">
    <div class="row package_row">
        @foreach ($packages as $index => $package)
        @if ($package->assetType && $package->assetType->asset_type === '2D')
        <div class="col-md-3 mb-3 preview_card">
            <div class="card ">
                <a href="{{ route('twoDim.show', ['id' => $package->id]) }}">
                    <img src="{{ Storage::url($package->Location) }}" class="card-img-top"
                        alt="{{ $package->PackageName }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $package->PackageName }}</h5>
                        <p class="card-text">{{ $package->Description }}</p>
                    </div>
                </a>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
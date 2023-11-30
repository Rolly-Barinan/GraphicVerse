<div  class="container">
    <!-- Display only 3D asset type packages -->
    <div class="row">
        <div class="col-md-4 mb-4">
            @foreach ($packages as $package)
            @if ($package->assetType && $package->assetType->asset_type === '3D')
                <div class="card">
                    <a href="{{ route('asset.display3d', ['id' => $package->id]) }}">
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
        </div>
    </div>
 

</div>


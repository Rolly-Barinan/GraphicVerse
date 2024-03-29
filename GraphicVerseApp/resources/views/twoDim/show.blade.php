@extends('layouts.app')

@section('content')
    <div class="container-fluid">


        <div class="container">
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
            <ul>
                @if ($assets->count() > 0)
                    @foreach ($assets->take(5) as $asset)
                        <li>{{ $asset->AssetName }}</li>
                    
                        <div class="card text-bg-secondary mb-3" style="width: 18rem;">
                            <img src="{{ Storage::url($asset->Location) }}" class="card-img-top"
                                alt="{{ $asset->AssetName }}">
                      </div>
                    @endforeach
                @else
                    <li>No assets found for this package.</li>
                @endif
            </ul>

            <form action="{{ route('paypal') }}" method="POST">
                @csrf

                <input type="hidden" name="price" value={{ $package->Price }}>

                <button type="submit" class="btn btn-primary">Pay with Paypal</button>
                {{-- <button type="submit"> Pay withss paypal</button> --}}

            </form>

            <a href="{{ route('asset.index') }}" class="btn btn-secondary">Back to Packages</a>
            <a href="{{ route('asset.download', $package->id) }}" class="btn btn-success">Download</a>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // jQuery script to add a watermark overlay on images
        $(document).ready(function() {
            // Loop through each image and add a watermark overlay
            $('img.card-img-top').each(function() {
                // Create a watermark div and set its background to your watermark image
                var watermarkDiv = $('<div class="watermark">')
                    .css({
                        'position': 'absolute',
                        'top': '50%',
                        'left': '50%',
                        'transform': 'translate(-50%, -50%)',
                        'pointer-events': 'none',
                        'z-index': '10',
                        'opacity': '0.5', // Adjust watermark opacity as needed
                        'background-image': 'url("/svg/watermark.png")', // Path to your watermark image
                        'background-repeat': 'no-repeat',
                        'background-size': 'contain', // Adjust based on your watermark image size
                        'width': '50%', // Adjust the watermark size as needed
                        'height': '50%'
                    });

                // Append the watermark div to the parent container
                $(this).parent().css('position', 'relative').append(watermarkDiv);
            });
        });
    </script>
@endsection

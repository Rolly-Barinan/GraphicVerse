@extends('layouts.app')
<link href="{{ asset('css/show.css') }}" rel="stylesheet">
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include Bootstrap's JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@section('content')

<div class="show container-fluid ">
    <div class="row">
        <div class="col-md-7">
            <div class="image-container mx-auto d-block">
            <img id="mainImage" src="{{ Storage::url($package->Location) }}" class="card-img-top main-image " alt="{{ $package->PackageName }}">
            </div>
            <div class="image-scroll-container carousel slide mx-auto d-block" id="assetCarousel" data-interval="false">
                <div class="carousel-inner">
                    @foreach ($assets->chunk(4) as $chunk)
                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <div class="row">
                                @foreach ($chunk as $asset)
                                    <div class="col-md-3">
                                        <div class="card">
                                            <img src="{{ Storage::url($asset->Location) }}" class="card-img-top asset-image mx-auto d-block" alt="{{ $asset->AssetName }}" onmouseover="changeMainImage(this)" onmouseout="resetMainImage()">
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
        <div class="col-md-5">
            <div class="r-body">
                @if ($user->teams->isNotEmpty())
                    <h4>{{ $user->teams->first()->name }}</h4>
                @endif
                <h1 class="r-title">{{ $package->PackageName }}</h1>
                <p>{{ $user->name }}</p>
                <hr>
                <div class="buy">
                    <h3>Buy Asset</h3>
                    <p>For more information about the royalties for the asset, <a href="#">click here</a>.</p>
                    <form action="{{ route('paypal') }}" method="POST">
                    @csrf
                    <input type="hidden" name="price" value="{{ $package->Price }}">
                    <button type="submit">
                    @if (!empty($package->Price) && $package->Price != "0")
                        <form action="{{ route('paypal') }}" method="POST">
                            @csrf
                            <input type="hidden" name="price" value="{{ $package->Price }}">
                            <button type="submit">
                                Pay ${{ $package->Price }} with PayPal
                            </button>
                        </form>
                    @else
                        <a href="{{ route('asset.download', $package->id) }}" class = "no-underline">Download for Free</a>
                    @endif
                    </button>
                    </form>
                </div>
                <hr>
                <p class="r-text">Package Description:</p>
                <div class="file-list">
                    <ul>
                    @foreach ($package->assets as $asset)
                    <div class="row">
                    <div class="col-md-6 size-info-left"> <li>{{ $asset->AssetName }}</div> <div class="col-md-6 size-info-right">{{ implode(', ', $fileTypes->toArray()) }} / {{ number_format($totalSizeMB / 1000, 2) }}mb</div></li>
                    </div>
                    @endforeach
                    </ul>
                </div>
                <hr>
                <p class="r-text">Tags:</p>
                <!-- <p class="r-text">{{ $package->Description }}</p>
                <p>File Types: {{ implode(', ', $fileTypes->toArray()) }}</p>
                <p>File Size: {{ number_format($totalSizeMB / 1000, 2) }}mb</p> -->
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    $(document).ready(function() {
        $('.scroll-btn.left').click(function() {
            $('.image-scroll-container').animate({ scrollLeft: '-=100' }, 'slow');
        });

        $('.scroll-btn.right').click(function() {
            $('.image-scroll-container').animate({ scrollLeft: '+=100' }, 'slow');
        });
    });

</script>
<script>
    
    function changeMainImage(assetImage) {
    document.getElementById('mainImage').src = assetImage.src;
}

var mainImageOriginalSrc = document.getElementById('mainImage').src;

function resetMainImage() {
    document.getElementById('mainImage').src = "{{ Storage::url($package->Location) }}";
}
</script>
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
                    <img id="mainImage" src="{{ Storage::url($package->Location) }}" class="card-img-top main-image "
                        alt="{{ $package->PackageName }}">
                </div>
                <div class="image-scroll-container carousel slide mx-auto d-block" id="assetCarousel" data-interval="false">
                    <div class="carousel-inner">
                        @foreach ($assets->chunk(4) as $chunk)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="row">
                                    @foreach ($chunk as $asset)
                                        <div class="col-md-3">
                                            <div class="card bg-secondary" style="margin: 20;">
                                                <img src="{{ Storage::url($asset->Location) }}"
                                                    class="card-img-top asset-image mx-auto d-block"
                                                    alt="{{ $asset->AssetName }}" onmouseover="changeMainImage(this)"
                                                    onmouseout="resetMainImage()">
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
                    @if ($package->team_id)
                        <a href="{{ route('teams.details', ['team' => $user->teams->first()->name]) }}"
                            style="text-decoration: none;">
                            <h4>Team: {{ $package->team->name }}</h4>
                        </a>
                    @endif
                    <h1 class="r-title">{{ $package->PackageName }}</h1>
                    <a href="{{ route('profile.show', ['user' => $user->id]) }}" style="text-decoration: none;">
                        <p>{{ $user->username }}</p>
                    </a>
                    <div class="buy">
                        @if (Auth::id() == $package->UserID)
                            <form action="/package/{{ $package->id }}/edit" method="GET">
                                <button type="submit" style="background-color: #9494AD; "class="no-underline">
                                    Edit Package
                                </button>
                            </form>
                            <form action="{{ route('asset.destroy', $package->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this package?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: #5F5F79; "class="no-underline">Delete Package</button>
                            </form>
                        @else
                            <h3>Download Asset</h3>
                            <p>For more information about the royalties for the asset, <a href="#">click here</a>.</p>
                            
                            @if (!empty($package->Price) && $package->Price != '0' && !$checkPurchase)                          
                                <form action="{{ route('paypal') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                                    <input type="hidden" name="price" value="{{ $package->Price }}">
                                    <button type="submit">
                                        <a class ="no-underline"> Pay ${{ $package->Price }} with PayPal</a>
                                    </button>
                                </form>
                            @else
                                <button type="submit">
                                    <a href="{{ route('asset.download', $package->id) }}" class = "no-underline">Download
                                        for Free</a>
                                </button>
                            @endif
                        @endif
                    </div>
                    <hr>
                    <p class="r-text">{{ $package->Description }}</p>
                    <p>File Types: {{ implode(', ', $fileTypes->toArray()) }}</p>
                    <p>File Size: {{ number_format($totalSizeMB, 2) }}kb</p>
                    <h3>Tags</h3>
                    <p>
                        @foreach ($package->tags as $tag)
                            <p># {{ $tag->name }}</p>
                        @endforeach
                        @foreach ($package->categories as $category)
                            <p># {{ $category->cat_name }}</p>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    $(document).ready(function() {
        $('.scroll-btn.left').click(function() {
            $('.image-scroll-container').animate({
                scrollLeft: '-=100'
            }, 'slow');
        });

        $('.scroll-btn.right').click(function() {
            $('.image-scroll-container').animate({
                scrollLeft: '+=100'
            }, 'slow');
        });

        // Function to change main image
        function changeMainImage(assetImage) {
            $('#mainImage').attr('src', assetImage.src);
        }

        // Variable to store original main image source
        var mainImageOriginalSrc = $('#mainImage').attr('src');

        // Function to reset main image
        function resetMainImage() {
            $('#mainImage').attr('src', mainImageOriginalSrc);
        }

        // Event listeners for mouse hover and mouse leave
        $('.asset-image').hover(function() {
            changeMainImage(this);
        }, function() {
            resetMainImage();
        });
    });
</script>

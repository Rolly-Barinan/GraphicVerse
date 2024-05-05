@extends('layouts.app')
<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@section('content')
    <div class="show container-fluid ">
        <div class="row">
            <div class="col-md-7">
                <div class="image-container mx-auto d-block">
                    <img id="mainImage" src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top main-image "
                        alt="{{ $image->ImageName }}">
                </div>
            </div>
            <div class="col-md-5">
                <div class="r-body">
                    @if ($image->team_id)
                        <a href="{{ route('teams.details', ['team' => $image->team->name]) }}"
                            style="text-decoration: none;">
                            <h4>Team: {{ $image->team->name }}</h4>
                        </a>
                    @endif
                    <div class="package-info" style="display: flex; align-items: center; justify-content: space-between;">
                        <h1 class="r-title" style="margin-right: 10px;">{{ $image->ImageName }}</h1>
                        <div class="likes" style="display: flex; align-items: center;">
                            <!-- Form for liking a package -->
                            <form action="{{ route('image.like', ['id' => $image->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn">
                                    <!-- Check if the user is authenticated and if the package is liked by the user -->
                                    @if(auth()->check() && $image->likes()->where('user_id', auth()->user()->id)->exists())
                                        <i class="fas fa-heart" style="color: #e52424; font-size: 24px;"></i><!-- Show filled heart icon if the package is liked -->                    
                                    @else 
                                        <i class="far fa-heart" style="color: #e52424; font-size: 24px;"></i> <!-- Show heart outline icon if the package is not liked -->
                                    @endif
                                    <!-- Display the number of likes -->
                                    <span>{{ $image->likes }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <a href="{{ route('profile.show', ['user' => $user->id]) }}" style="text-decoration: none;">
                        <p>{{ $user->username }}</p>
                    </a>
                    <div class="buy">
                        @if (Auth::id() == $image->userID)
                            <form action="/image/{{ $image->id }}/edit" method="GET">
                                <button type="submit" style="background-color: #9494AD; "class="no-underline">
                                    Edit Artwork
                                </button>
                            </form>
                            <form action="{{ route('image.destroy', $image->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: #5F5F79; "class="no-underline">Delete Artwork</button>
                            </form>
                        @else
                            <h3>Download Artwork</h3>
                            <p>For more information about the royalties for the asset, <a href="#">click here</a></p>

                            @if (!empty($image->Price) && $image->Price != '0' && !$checkPurchase)
                                <form action="{{ route('paypal') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="artwork_id" value="{{ $image->id }}">
                                    <input type="hidden" name="price" value="{{ $image->Price }}">
                                    <button type="submit">
                                        <a class="no-underline">Pay ${{ $image->Price }} with PayPal</a>
                                    </button>
                                </form>
                            @else
                            <button type="submit">
                                <a href="{{ route('image.download', $image->id) }}" class = "no-underline">Download
                                    for Free</a>
                            </button>
                            @endif
                        @endif
                    </div>
                    <hr>
                    <p class="r-text">{{ $image->ImageDescription }}</p>
                    @if ($image->Price != null && $image->Price != 0)
                        <p>Price: ${{ $image->Price }}</p>
                    @endif
                    <p>Image size: {{ number_format($imageSize, 2) }} KB</p>
                    <hr>
                    <h3>Tags</h3>
                    <p>
                        @foreach ($image->categories as $category)
                            <p># {{ $category->cat_name }}</p>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

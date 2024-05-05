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
                                            <div class="card">
                                                <h5 class="card-title">{{ $asset->AssetName }}</h5>
                                                <button class="btn btn-primary model-viewer" data-model-path="{{ Storage::url($asset->Location) }}">Play Audio</button>
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
                        <a href="{{ route('teams.details', ['team' => $package->team->name]) }}"
                            style="text-decoration: none;">
                            <h4>Team: {{ $package->team->name }}</h4>
                        </a>
                    @endif
                    <div class="package-info" style="display: flex; align-items: center; justify-content: space-between;">
                        <h1 class="r-title" style="margin-right: 10px;">{{ $package->PackageName }}</h1>
                        <div class="likes" style="display: flex; align-items: center;">
                            <!-- Form for liking a package -->
                            <form action="{{ route('package.like', ['id' => $package->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn">
                                    <!-- Check if the user is authenticated and if the package is liked by the user -->
                                    @if(auth()->check() && $package->likes()->where('user_id', auth()->user()->id)->exists())
                                        <i class="fas fa-heart" style="color: #e52424; font-size: 24px;"></i><!-- Show filled heart icon if the package is liked -->                    
                                    @else 
                                        <i class="far fa-heart" style="color: #e52424; font-size: 24px;"></i> <!-- Show heart outline icon if the package is not liked -->
                                    @endif
                                    <!-- Display the number of likes -->
                                    <span>{{ $package->likes }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <a href="{{ route('profile.show', ['user' => $user->id]) }}" style="text-decoration: none;"><p>{{ $user->username }}</p></a>
                    <hr>
                    <div class="buy">
                        @if (Auth::id() == $package->UserID)
                            <form action="/package/{{ $package->id }}/edit" method="GET">
                                <button type="submit" style="background-color: #9494AD; "class="no-underline">
                                    Edit Package
                                </button>
                            </form>
                            <form action="{{ route('asset.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: #5F5F79; "class="no-underline">Delete Package</button>
                            </form>
                        @else
                            <h3>Download Asset</h3>
                            <p>For more information about the royalties for the asset, <a href="#">click here</a>.</p>
                            <form action="{{ route('paypal') }}" method="POST">
                                @csrf
                                <input type="hidden" name="price" value="{{ $package->Price }}">
                                <button type="submit">
                                    @if (!empty($package->Price) && $package->Price != '0')
                                        <form action="{{ route('paypal') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="price" value="{{ $package->Price }}">
                                            <a class = "no-underline">
                                                Pay ${{ $package->Price }} with PayPal
                                            </a>
                                        </form>
                                    @else
                                        <a href="{{ route('asset.download', $package->id) }}" class = "no-underline">Download
                                            for Free</a>
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>
                    <hr>
                    <p class="r-text">Package Description:</p>
                    <div class="file-list">
                        <ul>
                            @foreach ($package->assets as $asset)
                                <div class="row">
                                    <div class="col-md-6 size-info-left">
                                        <li>{{ $asset->AssetName }}
                                    </div>
                                    <div class="col-md-6 size-info-right">{{ implode(', ', $fileTypes->toArray()) }} /
                                        {{ number_format($totalSizeMB / 1000, 2) }}mb</div>
                                    </li>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                    <hr>
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

    <!-- Add this HTML markup -->
    <div id="modelModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalViewerContainer"></div>
        </div>
    </div>

    <style>
        .carousel-item .card {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 10px;
            margin: 5px;
        }

        /* Center the h5 element text */
        .carousel-item .card h5 {
            text-align: center;
            margin: auto;
        }

        /* Adjust the size of the "Play Audio" button */
        .carousel-item .card .btn-primary {
            width: 100%; /* Make button width 100% of parent container */
            margin-top: 10px; /* Add some space between the h5 and button */
            font-size: 16px; /* Adjust font size as needed */
            padding: 5px 10px; /* Adjust padding as needed */
        }

        /* Add or adjust modal styles as needed */
        /* Style the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%; /* Set width to 100% to cover the entire viewport */
            height: 100%; /* Set height to 100% to cover the entire viewport */
            background-color: rgba(0, 0, 0, 0.9);
            overflow: auto;
        }

        /* Style the modal content */
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 80%; /* Set the desired width */
            max-width: 800px; /* Set a max-width if needed */
            height: auto; /* Set the desired height */
            max-height: 80vh; /* Set a max-height if needed, e.g., 80% of viewport height */
            padding: 20px;
        }

        /* Style the close button */
        .close {
            color: darkred;
            position: absolute;
            top: 15px;
            right: 35px;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            z-index: 1001;
        }

        .close:hover,
        .close:focus {
            color: orangered;
            text-decoration: none;
            cursor: pointer;
        }

        /* Hide vertical scrollbar */
        body.modal-open {
            overflow: hidden;
        }
    </style>

    <!-- Modify the JavaScript code -->
    <script>
        $(document).ready(function() {
            // Get the modal
            var modal = document.getElementById("modelModal");

            // Get the modal content div
            var modalContent = modal.querySelector('.modal-content');

            // Get the model viewer container
            var modalViewerContainer = modalContent.querySelector("#modalViewerContainer");

            // Get all elements with class="model-viewer"
            var triggers = document.querySelectorAll(".model-viewer");

            // Get the <span> element that closes the modal
            var span = document.querySelector(".close");

            // Variable to store scroll position
            var scrollPosition = 0;

            // Function to load and play audio in modal
            function loadAndPlayAudio(audioSrc) {
                modalViewerContainer.innerHTML = `
                    <audio controls controlsList="nodownload" autoplay>
                        <source src="${audioSrc}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                `;
            }

            // Loop through all triggers and attach click event listeners
            Array.from(triggers).forEach(function(trigger) {
                trigger.onclick = function(e) {
                    e.preventDefault(); // Prevent default link behavior
                    modal.style.display = "block";
                    loadAndPlayAudio(this.dataset.modelPath);
                    scrollPosition = window.scrollY; // Store scroll position
                    document.body.classList.add('modal-open'); // Hide vertical scrollbar
                }
            });

            // When the user clicks on <span> (x) or outside the modal, close the modal
            span.onclick = function() {
                closeModal();
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            }

            // Function to close the modal
            function closeModal() {
                modal.style.display = "none";
                document.body.classList.remove('modal-open'); // Show vertical scrollbar
                window.scrollTo(0, scrollPosition); // Restore scroll position
                // Pause audio when modal is closed
                modalViewerContainer.innerHTML = '';
            }
        });
    </script>


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
@endsection

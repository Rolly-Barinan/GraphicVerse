<link href="{{ asset('css/index.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')
    <div class="container-fluid d-flex">
        <div class="sticky-column filter_column">
            <div class="header">
                <h3>Refine by</h3>
                <a href="{{ route('filter.audio') }}" class="btn btn-link">Clear Filters</a>
            </div>
            <form action="{{ route('filter.audio') }}" method="GET">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 style="color: #989898 !important;">Categories</h3>
                    <button type="button" class="btn btn-link" onclick="toggleCategories()">-</button>
                </div>
                <div id="categories">
                    @foreach($categories as $category)
                    <div class="form-check " required>
                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                        @if(is_array(request()->categories) && in_array($category->id, request()->categories)) checked @endif>
                        <label class="form-check-label">
                            {{ $category->cat_name }}
                        </label>
                    </div>
                    @endforeach
                </div>
                <hr>

                <!-- Price Range filter -->
                <div class="d-flex justify-content-between align-items-center">
                    <h3 style="color: #989898 !important;">Price Range</h3>
                    <button type="button" class="btn btn-link" onclick="togglePriceRange()">-</button>
                </div>
                <div id="priceRange">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price_range[]" value="free"
                        @if(is_array(request()->price_range) && in_array('free', request()->price_range)) checked @endif>
                        <label class="form-check-label">
                            Free
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price_range[]" value="1-100"
                            @if (is_array(request()->price_range) && in_array('1-100', request()->price_range)) checked @endif>
                        <label class="form-check-label">
                            $1 - $100
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price_range[]" value="101-500"
                            @if (is_array(request()->price_range) && in_array('101-500', request()->price_range)) checked @endif>
                        <label class="form-check-label">
                            $101 - $500
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price_range[]" value="501-1000"
                            @if (is_array(request()->price_range) && in_array('501-1000', request()->price_range)) checked @endif>
                        <label class="form-check-label">
                            $501 - $1000
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="price_range[]" value="1000+"
                            @if (is_array(request()->price_range) && in_array('1000+', request()->price_range)) checked @endif>
                        <label class="form-check-label">
                            More than $1000
                        </label>
                    </div>
                </div>
                <hr>

                <!-- Authors filter -->
                <div class="d-flex justify-content-between align-items-center">
                    <h3 style="color: #989898 !important;">Authors</h3>
                    <button type="button" class="btn btn-link" onclick="toggleAuthors()">-</button>
                </div>
                <div id="authors">
                    <input type="text" class="searchbox" name="search" placeholder="Search Author's username" class="mb-2">
                </div>
                <hr>

                <!-- Submit button -->
                <button type="submit" class="apply">Apply Filters</button>
            </form>
        </div>

        <div class="scrollable-column packages_column">
            <div class="row package_row">
                <h1 class="text-center w-100">AUDIO ASSETS</h1>
                <div class="results-container">
                    <p class="results-text">
                        {{ ($packages->currentPage()-1)*$packages->perPage()+1 }} - 
                        {{ (($packages->currentPage()-1)*$packages->perPage())+$packages->count() }} 
                        of {{ $packages->total() }} results
                    </p>
                    <div class="sort-container">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Sort By
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item" href="{{ route('filter.audio', array_merge(request()->except('sort'), ['sort' => 'name_asc'])) }}">Name (Ascending)</a></li>
                            <li><a class="dropdown-item" href="{{ route('filter.audio', array_merge(request()->except('sort'), ['sort' => 'name_desc'])) }}">Name (Descending)</a></li>
                            <li><a class="dropdown-item" href="{{ route('filter.audio', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}">Price (Ascending)</a></li>
                            <li><a class="dropdown-item" href="{{ route('filter.audio', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}">Price (Descending)</a></li>
                            <li><a class="dropdown-item" href="{{ route('filter.audio', array_merge(request()->except('sort'), ['sort' => 'username_asc'])) }}">UserName (Ascending)</a></li>
                            <li><a class="dropdown-item" href="{{ route('filter.audio', array_merge(request()->except('sort'), ['sort' => 'username_desc'])) }}">UserName (Descending)</a></li>
                            <!-- Add more sorting options as needed -->
                        </ul>
                    </div>
                </div>
                </div>
                @php
                    $hasAudioAssets = false;
                @endphp

                @foreach ($packages as $index => $package)
                    @if ($package->assetType && $package->assetType->asset_type === 'Audio')
                        @php
                            $hasAudioAssets = true;
                        @endphp
                        <div class="col-md-4 mb-4">
                            <!-- <div class="card">
                                <a href="{{ route('audio.show', ['id' => $package->id]) }}">
                                    <img src="{{ Storage::url($package->Location) }}" class="card-img-top"
                                        alt="{{ $package->PackageName }}">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $package->PackageName }}</h5>
                                        <p class="card-text">{{ $package->user->username }}</p>
                                    </div>
                                </a>
                            </div> -->
                            <div class="card">
                                <a href="{{ route('audio.show', ['id' => $package->id]) }}">
                                    <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title">{{ $package->PackageName }}</h5>
                                            <p class="card-text">{{ $package->user->username }}</p>
                                        </div>
                                        <div>
                                            <!-- Form for liking an package -->
                                            <form action="{{ route('package.like', ['id' => $package->id]) }}" method="POST" style="text-decoration: none;">
                                                @csrf
                                                <button type="submit" class="btn">
                                                    <!-- Check if the user is authenticated and if the package is liked by the user -->
                                                    @if(auth()->check() && $package->likes()->where('user_id', auth()->user()->id)->exists())
                                                        <i class="fas fa-heart" style="color: #e52424;"></i><!-- Show filled heart icon if the package is liked -->                    
                                                    @else 
                                                        <i class="far fa-heart" style="color: #e52424;"></i> <!-- Show heart outline icon if the package is not liked -->
                                                    @endif
                                                    <!-- Display the number of likes -->
                                                    <span>{{ $package->likes }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach

                @if (!$hasAudioAssets)
                    <div class="col-md-12">
                        <div class="alert alert-info" role="alert">
                            No Audio assets to display yet.
                        </div>
                    </div>
                @endif    
            </div>
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-5">
                {{ $packages->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <script>
        function toggleCategories() {
            var categories = document.getElementById('categories');
            var toggleButton = document.getElementById('toggleCategories');

            if (categories.style.display === 'none') {
                categories.style.display = 'block';
                toggleButton.textContent = '-';
            } else {
                categories.style.display = 'none';
                toggleButton.textContent = '+';
            }
        }

        function togglePriceRange() {
            var priceRangeSection = document.getElementById('priceRange');
            var toggleButton = document.getElementById('togglePriceRange');

            if (priceRangeSection.style.display === 'none') {
                priceRangeSection.style.display = 'block';
                toggleButton.textContent = '-';
            } else {
                priceRangeSection.style.display = 'none';
                toggleButton.textContent = '+';
            }
        }

        function toggleAuthors() {
            var authorsSection = document.getElementById('authors');
            var toggleButton = document.getElementById('toggleAuthors');

            if (authorsSection.style.display === 'none') {
                authorsSection.style.display = 'block';
                toggleButton.textContent = '-';
            } else {
                authorsSection.style.display = 'none';
                toggleButton.textContent = '+';
            }
        }

        // Get all checkboxes
        var checkboxes = document.querySelectorAll('input[type=checkbox]');

        // Add event listener to each checkbox
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('change', function() {
                // Submit the form whenever a checkbox is clicked
                this.form.submit();
            });
        }

        // Get the price slider element
        var slider = document.getElementById('price-slider');

        // Initialize the price slider using noUiSlider
        noUiSlider.create(slider, {
            start: [{{ request()->min_price ?? '0' }}, {{ request()->max_price ?? '1000' }}], // Initial values
            connect: true,
            range: {
                'min': 0,
                'max': 1000 // Set your maximum price range here
            }
        });

        // Update the hidden input fields with the selected price range values whenever the slider values change
        slider.noUiSlider.on('update', function(values, handle) {
            document.getElementById('min_price').value = values[0];
            document.getElementById('max_price').value = values[1];
        });

        // Optionally, you can add an event listener to submit the form whenever the slider values change
        slider.noUiSlider.on('change', function(values, handle) {
            // Submit the form to apply the filter based on the selected price range
            document.querySelector('form').submit();
        });
    </script>
@endsection

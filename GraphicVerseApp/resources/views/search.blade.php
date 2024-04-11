<link href="{{ asset('css/index.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex">
    <div class="sticky-column filter_column">
        <div class="header">
            <h3>Refine by</h3>
            <a href="{{ route('filtered-search-results') }}" class="btn btn-link">Clear Filters</a>
        </div>

        <form action="{{ route('filtered-search-results') }}" method="GET" id="searchForm">
            <!-- Categories filter -->
            <div class="d-flex justify-content-between align-items-center">
                <h3 style="color: #989898 !important;">Categories</h3>
                <button type="button" class="btn btn-link" onclick="toggleCategories()">-</button>
            </div>
            <div id="categories">
                <!-- Category checkboxes -->
                <input type="hidden" name="q" value="{{ request('q') }}">
                @foreach ($categories as $category)
                    <div class="form-check " required>
                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                            @if (is_array(request()->categories) && in_array($category->id, request()->categories)) checked @endif>
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
                <!-- Price range checkboxes -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="price_range[]" value="free"
                        @if (is_array(request()->price_range) && in_array('free', request()->price_range)) checked @endif>
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
                <input type="text" class="searchbox"name="search" placeholder="Search Author's username" class="mb-2">
            </div>
            <hr>
        
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Apply Filters</button>
        </form>
    </div>

    <div class="scrollable-column packages_column">
        <div class="row package_row">
            <h1 class="text-center w-100">Search Results</h1>
            <div class="results-container">
                <p class="results-text">
                    {{ ($packages->currentPage() - 1) * $packages->perPage() + 1 }} -
                    {{ ($packages->currentPage() - 1) * $packages->perPage() + $packages->count() }}
                    of {{ $packages->total() }} results
                </p>
                <div class="sort-container">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', ['sort' => 'name_asc', 'q' => request()->q, 'categories' => request()->categories, 'price_range' => request()->price_range]) }}">Name (Ascending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', ['sort' => 'name_desc', 'q' => request()->q, 'categories' => request()->categories, 'price_range' => request()->price_range]) }}">Name (Descending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', ['sort' => 'price_asc', 'q' => request()->q, 'categories' => request()->categories, 'price_range' => request()->price_range]) }}">Price (Ascending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', ['sort' => 'price_desc', 'q' => request()->q, 'categories' => request()->categories, 'price_range' => request()->price_range]) }}">Price (Descending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', ['sort' => 'username_asc', 'q' => request()->q, 'categories' => request()->categories, 'price_range' => request()->price_range]) }}">UserName (Ascending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', ['sort' => 'username_desc', 'q' => request()->q, 'categories' => request()->categories, 'price_range' => request()->price_range]) }}">UserName (Descending)</a></li>
                        <!-- Add more sorting options as needed -->
                    </ul>
                </div>
            </div>
            @php
                $hasSearchResults = false;
            @endphp
            @foreach ($packages as $result)
                @php
                    $hasSearchResults = true;
                @endphp
                <div class="col-md-3 mb-3 preview_card">
                    <div class="card">
                        @if ($result->asset_type_id === 2) <!-- Assuming you have a field named 'asset_type' in your Package model -->
                            <a href="{{ route('threeDim.show', ['id' => $result->id]) }}"> <!-- Use threeDim.show route -->
                        @else
                            <a href="{{ route('twoDim.show', ['id' => $result->id]) }}"> <!-- Use twoDim.show route -->
                        @endif
                            <img src="{{ Storage::url($result->Location) }}" class="card-img-top" alt="{{ $result->PackageName }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $result->PackageName }}</h5>
                                <p class="card-text">{{ $result->user->username }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
            @if (!$hasSearchResults)
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        No search results found.
                    </div>
                </div>
            @endif
        </div>
        {{ $packages->appends(request()->input())->links() }}
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

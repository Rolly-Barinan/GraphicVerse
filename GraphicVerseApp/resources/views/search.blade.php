<link href="{{ asset('css/index.css') }}" rel="stylesheet">

@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex">
    <div class="sticky-column filter_column">
        <div class="header">
            <h3>Refine by</h3>
            <a href="{{ route('search', ['q' => request()->input('q')]) }}" class="btn btn-link">Clear Filters</a>

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
                    {{ ($sortedResults->currentPage() - 1) * $sortedResults->perPage() + 1 }} -
                    {{ ($sortedResults->currentPage() - 1) * $sortedResults->perPage() + $sortedResults->count() }}
                    of {{ $sortedResults->total() }} results
                </p>
                <div class="sort-container">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', array_merge(request()->except(['sort', 'page']), ['sort' => 'name_asc'])) }}">Name (Ascending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', array_merge(request()->except(['sort', 'page']), ['sort' => 'name_desc'])) }}">Name (Descending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', array_merge(request()->except(['sort', 'page']), ['sort' => 'price_asc'])) }}">Price (Ascending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', array_merge(request()->except(['sort', 'page']), ['sort' => 'price_desc'])) }}">Price (Descending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', array_merge(request()->except(['sort', 'page']), ['sort' => 'username_asc'])) }}">UserName (Ascending)</a></li>
                        <li><a class="dropdown-item" href="{{ route('filtered-search-results', array_merge(request()->except(['sort', 'page']), ['sort' => 'username_desc'])) }}">UserName (Descending)</a></li>
                        <!-- Add more sorting options as needed -->
                    </ul>
                </div>
            </div>
            @php
                $hasSearchResults = false;
                $hasArtworks = false;
            @endphp
            @foreach ($sortedResults as $result)
                @if ($result instanceof \App\Models\Package)
                    @php
                        $hasSearchResults = true;
                    @endphp
                    <div class="col-md-3 mb-3 preview_card">
                        <div class="card">
                            @if ($result->asset_type_id === 3) <!-- Assuming you have a field named 'asset_type' in your Package model -->
                                <a href="{{ route('audio.show', ['id' => $result->id]) }}"> <!-- Use threeDim.show route -->
                            @elseif ($result->asset_type_id === 2)
                                <a href="{{ route('threeDim.show', ['id' => $result->id]) }}"> <!-- Use twoDim.show route -->
                            @elseif ($result->asset_type_id === 1)
                                <a href="{{ route('twoDim.show', ['id' => $result->id]) }}">
                            @endif
                                <img src="{{ Storage::url($result->Location) }}" class="card-img-top" alt="{{ $result->PackageName }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $result->PackageName }}</h5>
                                    <p class="card-text">{{ $result->user->username }}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @elseif ($result instanceof \App\Models\ImageAsset)
                    @if ($result->assetType && $result->assetType->asset_type === '2D')
                        @php
                            $hasArtworks = true;
                        @endphp
                        <div class="col-md-3 mb-3 preview_card">
                            <div class="card ">
                                <a href="{{ route('image.show', ['id' => $result->id]) }}">
                                    <img src="{{ Storage::url($result->watermarkedImage) }}" class="card-img-top"
                                        alt="{{ $result->ImageName }}">

                                    <div class="card-body">
                                        <h5 class="card-title">{{ $result->ImageName }}</h5>
                                        <p class="card-text">{{ $result->user->username }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
            
            @if (!$hasSearchResults && !$hasArtworks)
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        No search results found.
                    </div>
                </div>
            @endif
        </div>
        <div class="d-flex justify-content-center mt-5">
                {{ $sortedResults->links('pagination::bootstrap-4') }}
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
</script>
@endsection

@extends('layouts.app')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card-1">
                <div class="card-body mt-4 ms-4 me-4">
                    <div class="ref-head d-flex justify-content-between mb-2">
                        <h4 style="font-family: 'Roboto'; font-weight: Bold; color: #424382">Refine By</h4>
                        <a class="clr-button" type="link" style="color:#4CB9FF; margin-top: 3px; font-size: 14.5px; text-decoration: none; cursor: pointer;">Clear Filters</a>
                    </div>
                    <hr>
                    <div class="accordion" id="categoryAccordion" style="background-color: #DDDDE4; border-bottom: 1px;">
                        <div class="accordion-item" style= "background-color: #DDDDE4; border: none;">
                            <h2 class="accordion-header" id="categoryHeading">
                            <a class="accordion-button collapsed" type="button" data-toggle="collapse" data-target="#categoryCollapse" aria-expanded="false" aria-controls="categoryCollapse">
                                Categories
                            </a>
                            </h2>
                            <div id="categoryCollapse" class="accordion-collapse collapse" style="background-color: #DDDDE4; border: none;">
                                <div class="card card-body" style="background-color: #DDDDE4; border: none">
                                    <form id="filterForm" action="{{ route('twoD.index') }}" method="get">
                                        @foreach ($categories as $category)
                                            <label class="checkbox-label custom-checkbox-label pt-1 pb-1" style="font-family: 'Roboto'; color:">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="custom-checkbox {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}">
                                                {{ $category->cat_name }}
                                            </label><br>
                                        @endforeach
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card-2">
                <div class="card-body mt-4 ms-4 me-4">
                    <h1 class="card-header">2D Models</h1>
                        <!-- "n of n results" and Sorting -->
                        <div class="d-flex justify-content-between align-items-center">

                            <p class="n-results"><strong>{{ $models2D->firstItem() }} - {{ $models2D->lastItem() }}</strong> of <strong>{{ $models2D->total() }}</strong> results</p>
                            <!-- Sorting dropdown -->
                            <!-- Sorting dropdown (without Bootstrap) -->
                            <select id="sortDropdown" onchange="sortModels(this)" class="custom-dropdown">
                                <option value="default">Sort By</option>
                                <option value="date_desc">Published Date (Newest first)</option>
                                <option value="date_asc">Published Date (Oldest first)</option>
                                <option value="name_asc">Name (A to Z)</option>
                                <option value="name_desc">Name (Z to A)</option>
                                <!-- Add more sorting options as needed -->
                            </select>
                        </div>
                    <div class="row">
                        @if(count($models2D) > 0)
                            @foreach ($models2D as $model)
                                <div class="col-md-4 mb-4">
                                    <a href="{{ route('twoD.show', ['id' => $model->id]) }}" class="model-link">
                                        <div class="card model-card">
                                            <img class="card-img-top model-image" src="{{ Storage::url($model->filename) }}" alt="{{ $model->twoD_name }}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $model->twoD_name }}</h5>
                                                <p class="card-text">{{ $model->description }}</p>
                                                <p class="card-text">Creator: {{ $model->creator_name }}</p>
                                            </div>
                                        </div>
                                    </a>   
                                </div>
                            @endforeach
                        @else
                            <p style="text-align: center; font-style: italic; color: black;">No 2D models found.</p>
                        @endif
                    </div>

                    <div class="pagination-container justify-content-center">
                        {{ $models2D->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categoryAccordion = document.getElementById('categoryAccordion');
        const categoryAccordionHeaders = categoryAccordion.querySelectorAll('.accordion-button');
        const categoryAccordionContents = categoryAccordion.querySelectorAll('.accordion-collapse');

        categoryAccordionHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const expanded = header.getAttribute('aria-expanded') === 'true';

                // Toggle the class to switch between + and - icons
                header.classList.toggle('collapsed', !expanded);

                categoryAccordionContents.forEach(content => {
                    content.style.display = expanded ? 'none' : 'block';
                });

                categoryAccordionHeaders.forEach(h => {
                    h.setAttribute('aria-expanded', !expanded);
                });
            });
        });

        // Release Date Accordion
        const dateAccordion = document.getElementById('dateAccordion');
        const dateAccordionHeaders = dateAccordion.querySelectorAll('.accordion-button');
        const dateAccordionContents = dateAccordion.querySelectorAll('.accordion-collapse');

        dateAccordionHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const expanded = header.getAttribute('aria-expanded') === 'true';

                // Toggle the class to switch between + and - icons
                header.classList.toggle('collapsed', !expanded);

                dateAccordionContents.forEach(content => {
                    content.style.display = expanded ? 'none' : 'block';
                });

                dateAccordionHeaders.forEach(h => {
                    h.setAttribute('aria-expanded', !expanded);
                });
            });
        });
    });
</script>


<!-- JavaScript for sorting -->
<script>
    function sortModels(select) {
        const selectedValue = select.value;
        
        // Redirect to the index page with the selected sort option
        window.location.href = "{{ route('twoD.index') }}?sort=" + selectedValue;
    }
</script>

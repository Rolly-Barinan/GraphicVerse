@extends('layouts.app')
<link href="{{ asset('css/index.css') }}" rel="stylesheet">
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body mt-4 ms-4 me-4">
                    <div class="ref-head d-flex justify-content-between mb-2">
                        <h4 style="font-family: 'Roboto'; font-weight: Bold; color: #424382">Refine By</h4>
                        <a class="clr-button" type="link" style="color:#4CB9FF; margin-top: 3px; font-size: 14.5px; text-decoration: none; cursor: pointer;">Clear Filters</a>
                    </div>
                    <hr>
                    <div class="accordion" id="categoryAccordion" style="background-color: #DDDDE4; border-bottom: 1px;">
                        <div class="accordion-item" style= "background-color: #DDDDE4; border: none;">
                            <h2 class="accordion-header" id="categoryHeading">
                            <a class="accordion-button collapsed" type="link" data-toggle="collapse" data-target="#categoryCollapse" aria-expanded="false" aria-controls="categoryCollapse">
                                Categories
                            </a>
                            </h2>
                            <div id="categoryCollapse" class="accordion-collapse collapse" style="background-color: #DDDDE4; border: none;">
                                <div class="card-body d-flex justify-content-between align-items-center" style="background-color: #DDDDE4; border: none">
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
                <div class="card-body">
                    <h1 class="card-title">3D Models</h1>
                    <div class="card-body mt-4 ms-4 me-4">
                        <!-- "n of n results" and Sorting -->
                        <div class="d-flex justify-content-between align-items-center">

                        <p class="n-results"><strong>{{ $models3D->firstItem() }} - {{ $models3D->lastItem() }}</strong> of <strong>{{ $models3D->total() }}</strong> results</p>
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
                        @if(count($models3D) > 0)
                                           @foreach ($models3D as $model)
                                <div class="col-md-4 mb-4">
                                    <a href="{{ route('threeD.show', ['id' => $model->id]) }}">
                                        <div class="card model-card " style="text-decoration: none !important">
                                        <div class="model-viewer" data-model-path="{{ asset('storage/' . $model->filename) }}"></div>
                                            <div class="favorite-icon" style="position: absolute; top: 10px; right: 10px; cursor: pointer;">
                                                <i class="heart-icon fas fa-heart"></i>
                                            </div>
                                            <div class="card-body" style="text-decoration: none !important">
                                                <h5 class="card-title" style="text-decoration: none !important">{{ $model->threeD_name }}
                                                    <span class="heart float-end me-2" style="align-items: center;"><i class="fas fa-heart"></i>
                                                        <span style="font-family: 'Oswald'; font-size: 15px; color: #9494AD; ">(10)</span>
                                                    </span>
                                                </h5>
                                                <!-- <p class="card-text">{{ $model->creator_username }}
                                                    <span class="dl float-end me-2"><i class="dl-icon fas fa-download"></i>
                                                        <span style="font-family: 'Oswald'; font-size: 15px; color: #9494AD; ">(37)</span>
                                                    </span>
                                                </p> -->
                                            </div>
                                        </div>
                                    </a>   
                                </div>
                            @endforeach
                        @else
                            <p style="text-align: center; font-style: italic; color: black;">No 3D assets found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    function loadFBX(modelViewer) {
        const modelPath = modelViewer.getAttribute('data-model-path');

        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0xdddddd);

        const aspectRatio = window.innerWidth / window.innerHeight;
        const width = 300;
        const height = width / aspectRatio;

        const camera = new THREE.PerspectiveCamera(50, aspectRatio, 1, 5000);
        camera.position.set(0, 0, 1000);

        const renderer = new THREE.WebGLRenderer({
            antialias: true
        });
        renderer.setSize(width, height);
        modelViewer.appendChild(renderer.domElement);

        const controls = new THREE.OrbitControls(camera, renderer.domElement);
        controls.enableDamping = true;
        controls.dampingFactor = 0.05;
        controls.rotateSpeed = 0.2; // Adjust the rotate speed (sensitivity)

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 1);
        directionalLight.position.set(0, 1, 0);
        scene.add(directionalLight);

        const fbxLoader = new THREE.FBXLoader();

        fbxLoader.load(modelPath, (object) => {
            object.traverse((child) => {
                if (child.isMesh) {
                    child.material.side = THREE
                        .DoubleSide; // Ensure both sides of the mesh are visible
                }
            });

            scene.add(object);

            const box = new THREE.Box3().setFromObject(object);
            const center = box.getCenter(new THREE.Vector3());
            const size = box.getSize(new THREE.Vector3());
            const maxDim = Math.max(size.x, size.y, size.z);

            const fov = camera.fov * (Math.PI / 180);
            const cameraDistance = Math.abs(maxDim / Math.sin(fov / 2));

            camera.position.copy(center);
            camera.position.z += cameraDistance;
            camera.lookAt(center);

            animate();
        });

        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
    }

    function initFBXViewers() {
        const modelViewers = document.getElementsByClassName('model-viewer');
        Array.from(modelViewers).forEach(modelViewer => {
            loadFBX(modelViewer);
        });
    }

    initFBXViewers();
</script>

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

    // Add this JavaScript code at the end of your HTML body or in an external JS file
document.addEventListener('DOMContentLoaded', function () {
    const fileTypeAccordion = document.getElementById('fileTypeAccordion');
    const accordionButton = fileTypeAccordion.querySelector('.accordion-button');
    const accordionPanel = fileTypeAccordion.querySelector('.accordion-panel');
    const applyFilterButton = fileTypeAccordion.querySelector('.apply-filter-button');

    accordionButton.addEventListener('click', function () {
        const expanded = accordionButton.getAttribute('aria-expanded') === 'true';
        accordionButton.setAttribute('aria-expanded', !expanded);
        accordionPanel.style.display = expanded ? 'none' : 'block';
    });

    applyFilterButton.addEventListener('click', function () {
        // Handle the filter logic here
        const selectedFileTypes = Array.from(document.querySelectorAll('.file-type-checkbox:checked')).map(checkbox => checkbox.value);
        console.log(selectedFileTypes);
        // You can use the selected file types to filter your models
        // Update your models based on the selected file types and reload the page or update the view
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.favorite-icon').click(function(event) {
            event.preventDefault(); // Stop the click event from propagating
            $(this).toggleClass('active');
        });
    });
</script>

@section('styles')
<style>
    .checkbox-label {
        display: block;
        margin-bottom: 5px;
    }

    .model-card {
        height: 100%;
    }

    .model-image {
        max-width: 100%;
        max-height: 200px; /* Adjust the height as needed */
        object-fit: cover;
    }
</style>
@endsection



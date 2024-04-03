@extends('layouts.app')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>

    <style>
        .white-bg {
            background-color: #fff;
            /* White background color */
            margin-bottom: 20px;
            /* Margin to separate the sections */
            padding: 20px;
            /* Add padding for spacing inside the white divs */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: black;
            /* Set the background color to black */
            color: white;
            /* Set the arrow color to white */
            border-radius: 50%;
            /* Optional: Add some border-radius for rounded arrows */
            padding: 10px;
            /* Optional: Add padding to the arrows for better visibility */
        }

        /* CSS for your card elements */
        .card2d {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: 5px;
        }

        .card2d a {
            text-decoration: none;
            /* Remove underline from links */
            color: #333;
            /* Set the link color */
        }

        .card2d a:hover {
            color: #555;
            /* Change link color on hover if desired */
        }

        .card2d .card-title {
            font-size: 12px;
            /* Adjust the font size as desired */
            padding: 10px;
            /* Add padding to the card title */
        }

        /* CSS for your 3D asset cards */
        .card3d {
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: 5px;
            width: 100%;
            max-width: 300px;
        }

        .card3d a {
            text-decoration: none;
            color: #333;
        }

        .card3d a:hover {
            color: #555;
        }

        .card3d .model-viewer {
            width: 100%;
        }

        .title-container {
            background-color: #333;
            /* Background color for the title container */
            color: #fff;
            /* Text color for the title */
            padding: 10px;
            text-align: center;
            font-weight: bold;
            width: 100%;
            box-sizing: border-box;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>

    <div class="container-fluid py-50" style="background-color: #DDDDE4;">
        <div class="row-fluid image-container border-2">
            <img src="/svg/graphicVerse _background.png" class="img-fluid" alt="...">
        </div>

        <div class="row">
            <div class="col-12 col-md-3 p-2 d-flex justify-content-center align-items-start">
                <div class="rounded-circle-container">
                    <img src="{{ $user->profile->profileImage() }}" class="rounded-circle img-fluid"
                        alt="User Profile Image">
                </div>
            </div>

            <div class="col-12 col-md-5 pt-3">
                <div>
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="h4">{{ $user->name }}</div>
                            <a href="{{ route('asset.create') }}"><button type="button" class="btn btn-warning me-2">Upload
                        Package</button></a>
                <a href="{{ route('image.create') }}"><button type="button" class="btn btn-warning">Upload
                        Image</button></a>
                        </div>
                    </div>
                    <div class="">@ {{ $user->profile->title }}</div>
                    @can('update', $user->profile)
                        <a href="/profile/{{ $user->id }}/edit">Edit Profile</a>
                    @endcan
                    <div>{{ $user->profile->description }}</div>
                    <div><a href="">{{ $user->profile->url ?? 'N/A' }}</a></div>
                </div>
            </div>
            <div class="col-12 col-md-2 d-flex justify-content-end ps-md-5 pt-2 align-items-start">
                @can('update', $user->profile)
                    <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        style="--bs-btn-padding-y: .7rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .9rem;">Upload
                    </button>
                @endcan
            </div>
            <div class="col-12 col-md-2 pt-2 align-items-start">
                <button type="button" class="btn btn-primary btn-lg"
                    style="--bs-btn-padding-y: .7rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .9rem;">Connect</button>
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-10">
                <h4>Recently Uploaded</h4>

                <div class="white-bg">

                    <div class="container-fluid py-50" style="background-color: #DDDDE4;">
                        <div class="row">
                            <!-- Vertical Navbar -->
                            <div class="col-md-3">
                                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                                    <div class="container-fluid">
                                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                                            aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon"></span>
                                        </button>
                                        <div class="collapse navbar-collapse justify-content-start" id="navbarNav">
                                            <ul class="navbar-nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="2d">2d</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="3d">3d </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="audio">audio </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="image">image </a>
                                                </li>
                                                <!-- Add more links as needed -->
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                            <!-- Main Content Area -->
                            <div class="col-md-9" id="mainContent">
                                <!-- Content from twoDim.blade.php will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <script>
                        // Function to load content dynamically using AJAX
                        function loadContent(url) {
                            fetch(url)
                                .then(response => response.text())
                                .then(html => {
                                    document.getElementById('mainContent').innerHTML = html;
                                })
                                .catch(error => console.error('Error loading content:', error));
                        }

                        // Event listeners for each link
                        document.getElementById('2d').addEventListener('click', function(event) {
                            event.preventDefault();
                            loadContent('/profile/display/2d-models');
                            // Store the active link in localStorage
                            localStorage.setItem('activeLink', '2d');
                            // Update the active class for all links
                            updateActiveClass('2d');
                        });

                        document.getElementById('3d').addEventListener('click', function(event) {
                            event.preventDefault();
                            loadContent('/profile/display/3d-models');
                            // Store the active link in localStorage
                            localStorage.setItem('activeLink', '3d');
                            // Update the active class for all links
                            updateActiveClass('3d');
                        });

                        document.getElementById('audio').addEventListener('click', function(event) {
                            event.preventDefault();
                            loadContent('/profile/display/audio-models');

                            localStorage.setItem('activeLink', 'audio');

                            updateActiveClass('audio');
                        });

                        document.getElementById('image').addEventListener('click', function(event) {
                            event.preventDefault();
                            loadContent('/profile/display/image-models');

                            localStorage.setItem('activeLink', 'image');

                            updateActiveClass('image');
                        });

                        function updateActiveClass(activeLinkId) {
                            const links = document.querySelectorAll('.nav-link');
                            links.forEach(link => {
                                link.classList.remove('active');
                            });
                            document.getElementById(activeLinkId).classList.add('active');
                        }
                        document.addEventListener('DOMContentLoaded', function() {
                            const activeLink = localStorage.getItem('activeLink');
                            if (activeLink) {
                                loadContent(`/profile/display/${activeLink}-models`);
                                updateActiveClass(activeLink);
                            }
                        });
                    </script>

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

                </div>
            </div>

            <!-- Display user's teams -->
            {{-- <div class="col-md-2">
                <h4>Teams</h4>
                <div class="white-bg">
                    @if (count($userTeams) > 0)
                        @foreach ($userTeams as $team)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: auto; background-color: {{ $team->color }};">
                                    @php
                                        $words = explode(" ", $team->name); // Split the team name into an array of words
                                
                                        if (count($words) === 1) {
                                            echo strtoupper(substr($team->name, 0, 3)); // Use the first three letters for single-word team names
                                        } else {
                                            foreach ($words as $word) {
                                                echo strtoupper(substr($word, 0, 1)); // Output the first letter of each word for multi-word team names
                                            }
                                        }
                                    @endphp
                                </div>
                                <div class="col-md-4 col-sm-6 ml-2">{{ $team->name }}</div>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; font-style: italic; color: black;">No associated teams.</p>
                    @endif    
                </div>
            </div> --}}
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Select which asset type to upload</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex justify-content-center align-items-center">
                        <a href="/upload/2d" class="mx-3">
                            <button type="button" class="btn btn-primary">2D</button>
                        </a>
                        <a href="/upload/3d" class="mx-3">
                            <button type="button" class="btn btn-primary">3D</button>
                        </a>
                        <a href="/audios/create" class="mx-3">
                            <button type="button" class="btn btn-primary">Audio</button>
                        </a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

               <!-- Display user's teams -->
            <div class="">
                <h4>Teams</h4>
                <div class="white-bg">
                    @if (count($userTeams) > 0)
                        @foreach ($userTeams as $team)
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: auto; background-color: {{ $team->color }};">
                                    @php
                                        $words = explode(" ", $team->name); // Split the team name into an array of words
                                
                                        if (count($words) === 1) {
                                            echo strtoupper(substr($team->name, 0, 3)); // Use the first three letters for single-word team names
                                        } else {
                                            foreach ($words as $word) {
                                                echo strtoupper(substr($word, 0, 1)); // Output the first letter of each word for multi-word team names
                                            }
                                        }
                                    @endphp
                                </div>
                                <div class="col-md-4 col-sm-6 ml-2">{{ $team->name }}</div>
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; font-style: italic; color: black;">No associated teams.</p>
                    @endif    
                </div>
            </div>
@endsection

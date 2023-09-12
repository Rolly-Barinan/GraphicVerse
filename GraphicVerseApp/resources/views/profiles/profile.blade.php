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
            background-color: #fff; /* White background color */
            margin-bottom: 20px; /* Margin to separate the sections */
            padding: 20px; /* Add padding for spacing inside the white divs */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: black; /* Set the background color to black */
            color: white; /* Set the arrow color to white */
            border-radius: 50%; /* Optional: Add some border-radius for rounded arrows */
            padding: 10px; /* Optional: Add padding to the arrows for better visibility */
        }

        /* CSS for your card elements */
        .card2d {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin: 5px;
        }

        .card2d a {
            text-decoration: none; /* Remove underline from links */
            color: #333; /* Set the link color */
        }

        .card2d a:hover {
            color: #555; /* Change link color on hover if desired */
        }

        .card2d .card-title {
            font-size: 12px; /* Adjust the font size as desired */
            padding: 10px; /* Add padding to the card title */
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
            background-color: #333; /* Background color for the title container */
            color: #fff; /* Text color for the title */
            padding: 10px;
            text-align: center;
            font-weight: bold;
            width: 100%;
            box-sizing: border-box;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }
    </style>

    <div class="container-fluid py-50" style="background-color: #DDDDE4; height: 100%;">
        <div class="row-fluid image-container border-2">
            <img src="/svg/graphicVerse _background.png" class="img-fluid" alt="...">
        </div>

        <div class="row">
            <div class="col-12 col-md-3 p-2 d-flex justify-content-center align-items-start">
                <div class="rounded-circle-container">
                    <img src="{{ $user->profile->profileImage() }}" class="rounded-circle img-fluid" alt="User Profile Image">
                </div>
            </div>
            
            <div class="col-12 col-md-5 pt-3">
                <div>
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="h4">{{ $user->name }}</div>
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
                    <h5>2D ASSETS</h5>
                    @if(count($userUploads) > 0)
                        <div id="carousel2D" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($userUploads as $index => $upload)
                                    @if($index % 4 == 0)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <div class="row">
                                                @endif
                                                <div class="col-md-3 mb-3">
                                                    <div class="card2d">
                                                        <a href="{{ route('twoD.show', ['id' => $upload->id]) }}">
                                                            <img src="{{ asset('storage/' . $upload->filename) }}" class="card-img-top"
                                                                alt="{{ $upload->twoD_name }}" style="width: 100%; height: 150px;">
                                                            <div class="card-body">
                                                                <h5 class="title-container">{{ $upload->twoD_name }}</h5>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                                @if(($index + 1) % 4 == 0 || $loop->last)
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carousel2D" role="button" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel2D" role="button" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a>
                        </div>
                    @else
                        <p style="text-align: center; font-style: italic; color: black;">No 2D assets found.</p>
                    @endif

                    <h5>3D ASSETS</h5>
                    @if(count($userUploads3D) > 0)
                        <div id="carousel3D" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($userUploads3D as $index => $threeD)
                                    @if($index % 3 == 0)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <div class="row">
                                                @endif
                                                <div class="col-md-4 mb-3">
                                                    <div class="card3d">
                                                        <a href="{{ route('threeD.show', ['id' => $threeD->id]) }}">
                                                            <div class="model-viewer"
                                                                data-model-path="{{ asset('storage/' . $threeD->filename) }}"></div>
                                                            <div class="title-container">{{ $threeD->threeD_name }}</div>
                                                        </a>
                                                    </div>
                                                </div>
                                                @if(($index + 1) % 3 == 0 || $loop->last)
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <a class="carousel-control-prev" href="#carousel3D" role="button" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel3D" role="button" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </a>
                        </div>
                    @else
                        <p style="text-align: center; font-style: italic; color: black;">No 3D assets found.</p>
                    @endif

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
            <div class="col-md-2">
                <h4>Teams</h4>
                <div class="white-bg">
                    @if(count($userTeams) > 0)
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
@endsection

@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">

    <div class="container-fluid custom-container py-50 " style="background-color:#DDDDE4;">

        <img class="cover-photo" class- src="/svg/coverphoto.png" class="img-fluid" alt="...">
        <div class="row ">

            <div class="col-3 p-2 profile-img justify-content-center align-items-center">
                <div class="rounded-circle-container">
                    <img src="{{ $user->profile->profileImage() }}" class="rounded-circle  img-fluid" alt="...">
                </div>

            </div>
            <div class="col-5 pt-3  ">

                <div class="profile-description">
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <h2 class="usersname"> {{ $user->name }}
                            </h2>
                            @can('update', $user->profile)
                                <a href="/profile/{{ $user->id }}/edit"><img class="mt-3" src="/svg/pen-fill.svg"
                                        alt=""></i></a>
                            @endcan
                        </div>
                    </div>
                    <div class="">@ {{ $user->profile->title }}</div>
                    <div> {{ $user->profile->description }}</div>
                    <div> <a href=""> {{ $user->profile->url ?? 'N/A' }}</a></div>
                </div>

            </div>

            <div class="col-2 d-flex justify-content-end ps-5 pt-2 align-items-start ">
                @can('update', $user->profile)
                    <button type="button" class="btn btn-success btn-lg  " data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        style="--bs-btn-padding-y: .7rem; --bs-btn-padding-x: 3.5rem; --bs-btn-font-size: .9rem;">Upload
                    </button>
                @endcan
            </div>
            <div class="col-2 col-2 d-flex justify-content-top pt-2 align-items-start">
                <button type="button" class="btn btn-primary btn-lg"
                    style="--bs-btn-padding-y: .7rem; --bs-btn-padding-x: 3.5rem; --bs-btn-font-size: .9rem;">Add
                    Team</button>
            </div>

        </div>
        <div class="row ">
            <div class="col-8 recentlyuploaded">
                <h4 class="ps-2 bold"> <b>RECENTLY UPLOADED</b></h4>
            </div>
            <div class="col-4  teams-title">
                <h4 class="TEAMS"><b>TEAMS</b></h4>
            </div>
        </div>
        <div class="container">
            <div class="row ">
                <!-- Recent 2D Uploads -->
                <div class="col-lg-9">

                    <div class="row p-2 upload">
                        <h4>2D assets </h4>
                        @foreach ($userUploadsPaginated as $upload)
                            <div class="col-md-3 mb-3">
                                <div class="card2d">
                                    <a href="{{ route('twoD.show', ['id' => $upload->id]) }}">
                                        <img src="{{ asset('storage/' . $upload->filename) }}" class="card-img-top"
                                            alt="{{ $upload->twoD_name }}" style="width: 100%; height: 150px;">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $upload->twoD_name }}</h5>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-center">
                            {{ $userUploadsPaginated->links('pagination::bootstrap-4', ['class' => 'custom-pagination']) }}
                        </div>
                    </div>


                    <div class="row upload">
                        <h4>3D assets</h4>
                        @foreach ($userUploads3DPaginated as $threeD)
                            <div class="col-md-4 mb-3">
                                <div class="card2d">
                                    <a href="{{ route('threeD.show', ['id' => $threeD->id]) }}">
                                        <div class="model-viewer"
                                            data-model-path="{{ asset('storage/' . $threeD->filename) }}"></div>
                                        <div class="bg-gray">{{ $threeD->threeD_name }}</div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-center">
                            {{ $userUploads3DPaginated->links('pagination::bootstrap-4', ['class' => 'custom-pagination']) }}
                        </div>
                    </div>
                    <!-- Display pagination links for 3D Uploads -->


                </div>
                <!-- User's Teams -->
                <div class="col-lg-3 ">
                    <div class="teams">

                        @foreach ($userTeams as $team)
                            <div>{{ $team->name }}</div>
                            <hr>
                        @endforeach
                    </div>
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
    @endsection

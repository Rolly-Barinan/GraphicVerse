@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>

    <div class="container-fluid py-50 " style="background-color: #DDDDE4;">
        <div class="row-fluid image-container   border-2">
            <img src="/svg/1201120.jpg" class="img-fluid" alt="...">
        </div>

        <div class="row">
            <div class="col-3 p-2 d-flex justify-content-center align-items-start">
                <div class="rounded-circle-container">
                    <img src="{{ $user->profile->profileImage() }}" class="rounded-circle img-fluid" alt="...">
                </div>

            </div>
            <div class="col-5 pt-3 ">
                <div>
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="h4" class="ml-4 p"> {{ $user->name }} </div>
                        </div>
                    </div>
                    <div class="">@ {{ $user->profile->title }}</div>
                    <div class="d-flex p-4">
                        {{-- <div style=" padding-right:20px;"><strong>{{$user->posts->count()}} </strong> posts</div> --}}
                        <div style=" padding-right:20px;"><strong>20k</strong> followers</div>
                        <div style=" padding-right:20px;"><strong>400</strong> following</div>
                    </div>
                    @can('update', $user->profile)
                        <a href="/profile/{{ $user->id }}/edit">Edit Profile</a>
                    @endcan
                    
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
                    style="--bs-btn-padding-y: .7rem; --bs-btn-padding-x: 3.5rem; --bs-btn-font-size: .9rem;">Connect</button>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-10">
                <div class="row">
                    <h4>Recently Upload </h4>
                    <h5>2D</h5>
                    @foreach($userUploads as $upload)
                        <div class="col-3 p-">
                            <a href="{{ route('twoD.show', ['id' => $upload->id]) }}"> {{-- Link to the show route --}}
                                <img src="{{ asset('storage/' . $upload->filename) }}" class="card-img-top" alt="{{ $upload->twoD_name }}" style="width: 100%; height: 200px;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $upload->twoD_name }}</h5>
                                </div>
                            </a>
                        </div>
                    @endforeach
                    
                    <h5>3D</h5>
                    @foreach ($userUploads3D as $threeD)
                        <div class="col-4 p-4">
                            <a href="/3d/{{ $threeD->id }}">
                                <div class="model-viewer" data-model-path="{{ asset('storage/' . $threeD->filename) }}">
                                </div>
                                <div class="bg-gray"> {{ $threeD->threeD_name }}</div>
                            </a>
                        </div>
                    @endforeach

                    
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
            <div class="col-2">
                <div class="col-10">
                    <h4>Teams</h4>
                    @foreach ($userTeams as $team)
                        <div>{{ $team->name }}</div>
                    @endforeach
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
    </div>
@endsection

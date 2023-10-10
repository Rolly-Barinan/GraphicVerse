@extends('layouts.app')

@section('content')
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ $model3D->threeD_name }} Details</div>
                        
                        <div class="card-body">
                            <div class="text-center">
                                <div class="model-viewer" data-model-path="{{ asset('storage/' . $model3D->filename) }}"></div>
                            </div>
                            <hr>
                            <p><strong>Description:</strong> {{ $model3D->description }}</p>
                            <p><strong>Category:</strong>
                                @foreach ($model3D->categories3D as $index => $category)
                                    {{ $category->cat_name }}
                                    @if ($index < count($model3D->categories3D) - 1)
                                        
                                    @endif
                                @endforeach
                            </p>   
                            <p><strong>Creator:</strong> {{ $model3D->creator_username }}</p>
{{ $model3D->id }}
                            <a href="{{ route('threeD.download', $model3D->id) }}" class="btn btn-success">Download</a>

                            {{-- Add more details as needed --}}
                            @if(Auth::check() && Auth::user()->id === $model3D->user3d->user_id)
                                <a href="{{ route('threeD.edit', $model3D->id) }}" class="btn btn-primary">Edit</a>
                                {{-- Delete button --}}
                                <form action="{{ route('threeD.destroy', $model3D->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this 3D asset?')">Delete</button>
                                </form>
                            @endif
                        </div>
                       
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
                const width = 800;
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

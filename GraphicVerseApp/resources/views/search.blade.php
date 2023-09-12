@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h3>Filter by Category:</h3>
                    <form action="{{ route('search') }}" method="get">
                        <input type="hidden" name="q" value="{{ request('q') }}">
                        @foreach ($categories as $category)
                            <label class="checkbox-label">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                {{ $category->cat_name }}
                            </label><br>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </form>                    
                </div>
            </div>
        </div>
        

        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">Search Results</h1>
                    <div class="row">
                        @if(count($models2D) > 0)
                            @foreach ($models2D as $model)
                                <div class="col-md-4 mb-4">
                                    <a href="{{ route('twoD.show', ['id' => $model->id]) }}">
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
                        @endif
                        
                        @if(count($models3D) > 0)
                            @foreach ($models3D as $model)
                                <div class="col-md-4 mb-4">
                                    <a href="{{ route('threeD.show', ['id' => $model->id]) }}">
                                        <div class="card model-card">
                                            <div class="model-viewer" data-model-path="{{ asset('storage/' . $model->filename) }}"></div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $model->threeD_name }}</h5>
                                                <p class="card-text">{{ $model->description }}</p>
                                                <p class="card-text">Creator: {{ $model->creator_name }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                        
                        @if(count($models2D) === 0 && count($models3D) === 0)
                            <p style="text-align: center; font-style: italic; color: black;">No assets found.</p>
                        @endif
                    </div>
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

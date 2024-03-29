@extends('layouts.app')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>

    <div class="container">
        <h1>Package Details</h1>

        <div class="card">
            <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
            <div class="card-body">
                <h5 class="card-title">{{ $package->PackageName }}</h5>
                <p class="card-text">{{ $package->Description }}</p>
                <p>Price: ${{ $package->Price }}</p>
            </div>
        </div>

        <h2>Assets in this Package</h2>
        <ul>
            @if ($assets->count() > 0)
                @foreach ($assets->take(5) as $asset)
                    <li>{{ $asset->AssetName }}</li>
                    <div class="model-viewer" data-model-path="{{ Storage::url($asset->Location) }}"></div>
                @endforeach
            @else
                <li>No assets found for this package.</li>
            @endif
        </ul>

        <form action="{{ route('paypal') }}" method="POST">
            @csrf

            <input type="hidden" name="price" value={{ $package->Price }}>
            <button type="submit"> Pay with paypal</button>
        </form>


        <a href="{{ route('asset.index') }}" class="btn btn-primary">Back to Packages</a>
        <a href="{{ route('asset.download', $package->id) }}" class="btn btn-success">Download</a>

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
                        // Change the color to red
                        child.material = new THREE.MeshPhongMaterial({
                            color:0xff00ff
                        });
                        child.material.side = THREE.DoubleSide; // Ensure both sides of the mesh are visible
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

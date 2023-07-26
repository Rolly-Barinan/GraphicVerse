@extends('layouts.app')

@section('content')

    <!DOCTYPE html>
    <html>

    <head>
        <title>FBX File Loader</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            #model-viewer {
                /* width: 800px;
                                height: 600px;
                                margin: 0 auto; */
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
        <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css ">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
            integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous">
        </script>
    </head>

    <body>
        <div class="row">

            <div class="col-7">
      
                <div class="model-viewer" data-model-path="{{ asset('storage/' . $userThreeD->asset) }}"></div>

            </div>
            <div class="col-5">
      
            <div> {{ $userThreeD->asset_name }}</div>
            <p>{{ $userThreeD->description }}</p>
            <div>{{ $userThreeD->category }}</div>
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
    </body>

    </html>
@endsection

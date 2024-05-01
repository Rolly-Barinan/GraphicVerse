@extends('layouts.adminlayout')

@section('admin-content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right mb-4">
                        <h2>Package Information</h2>
                    </div>
                </div>
            </div>
            <div class="card rounded" style="border-left: 10px solid #333;">
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <div class="d-flex justify-content-center mb-4"> <!-- Centering the image -->
                                <a href="{{ Storage::url($package->Location) }}" target="_blank">
                                    <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
                                </a>
                            </div>
                            <tr>
                                <th>Package ID:</th>
                                <td>{{ $package->id }}</td>
                            </tr>
                            <tr>
                                <th>Belongs to User:</th>
                                <td>{{ $package->user->username }}</td>
                            </tr>
                            <tr>
                                <th>Package Name:</th>
                                <td>{{ $package->PackageName }}</td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $package->Description }}</td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td>${{ $package->Price }}</td>
                            </tr>
                            <tr>
                                <th>Asset type:</th>
                                <td>{{ $package->assetType->asset_type }}</td>
                            </tr>
                            <tr>
                                <th>Number of assets in this package:</th>
                                <td>{{ $userUploadsCountAssets }}</td>
                            </tr>
                            <tr>
                                <th>Created At:</th>
                                <td>{{ $package->created_at->format('Y-m-d') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At:</th>
                                <td>{{ $package->updated_at->format('Y-m-d') }}</td>
                            </tr>
                            <!-- Add more user details as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Assets Information -->
            @if($userUploadsCountAssets > 0)
            <div class="mt-4">
                <h3>Assets in this Package</h3>
                <div class="card rounded" style="border-left: 10px solid #333;">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Asset Name</th>
                                    <th>File Type</th>
                                    <th>File Size</th>
                                    <!-- Add more asset fields if needed -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($package->assets as $asset)
                                <tr>
                                    <td>
                                        @if ($package->asset_type_id === 3) <!-- Check if asset type is audio -->
                                            <!-- Add audio player -->
                                            <audio controls>
                                                <source src="{{ Storage::url($asset->Location) }}" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @elseif ($package->asset_type_id === 2)
                                            <div class="model-viewer" data-model-path="{{ Storage::url($asset->Location) }}" id="modelViewer"></div>
                                        @else
                                            <!-- Add a class to the link to trigger the modal -->
                                            <a href="#" class="image-modal-trigger" data-image="{{ Storage::url($asset->Location) }}">
                                                <img src="{{ Storage::url($asset->Location) }}" class="card-img-top"
                                                    alt="{{ $asset->AssetName }}" style="width: 100px; height: 100px;">
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $asset->AssetName }}</td>
                                    <td>{{ $asset->FileType }}</td>
                                    <td>{{ $asset->FileSize }}KB</td>
                                    <!-- Add more asset fields if needed -->
                                </tr>
                                @endforeach
                            </tbody>
                            
                            <!-- Modal markup -->
                            <div id="imageModal" class="modal">
                                <div class="modal-content">
                                    <span class="close">&times;</span>
                                    <img id="modalImage">
                                </div>
                            </div>                           
                        </table>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Back and Delete Buttons -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('admin.packages') }}" class="btn btn-primary">Back to Packages</a>
                <a href="{{ route('admin.deletePackage' , $package->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this package?')">Delete Package</a>
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
                    // Change the color to red
                    // child.material = new THREE.MeshPhongMaterial({
                    //     color: 0xff00ff
                    // });
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
<!-- Add this JavaScript code -->
<script>
    // Get the modal
    var modal = document.getElementById("imageModal");

    // Get the modal content div
    var modalContent = modal.querySelector('.modal-content');

    // Get the image element
    var modalImg = modalContent.querySelector("#modalImage");

    // Get all elements with class="image-modal-trigger"
    var triggers = document.getElementsByClassName("image-modal-trigger");

    // Get the <span> element that closes the modal
    var span = document.querySelector(".close");

    // Variable to store scroll position
    var scrollPosition = 0;

    // Loop through all triggers and attach click event listeners
    Array.from(triggers).forEach(function(trigger) {
        trigger.onclick = function(e) {
            e.preventDefault(); // Prevent default link behavior
            modal.style.display = "block";
            modalImg.src = this.dataset.image;
            modalContent.style.height = "auto";
            scrollPosition = window.scrollY; // Store scroll position
            document.body.classList.add('modal-open'); // Hide vertical scrollbar
        }
    });

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
        document.body.classList.remove('modal-open'); // Show vertical scrollbar
        window.scrollTo(0, scrollPosition); // Restore scroll position
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            document.body.classList.remove('modal-open'); // Show vertical scrollbar
            window.scrollTo(0, scrollPosition); // Restore scroll position
        }
    }
</script>
<!-- Add this CSS code -->
<style>
    /* Style the modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        overflow: auto;
    }

    /* Style the modal content */
    .modal-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: auto;
        display: table;
        width: auto;
        max-width: 100%;
        max-height: 100%;
    }

    /* Style the close button */
    .close {
        color: white;
        position: absolute;
        top: 15px;
        right: 35px;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
        z-index: 1001;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* Hide vertical scrollbar */
    body.modal-open {
        overflow: hidden;
    }
</style>
@endsection

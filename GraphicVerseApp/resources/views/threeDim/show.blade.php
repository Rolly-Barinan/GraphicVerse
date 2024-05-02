@extends('layouts.app')
<link href="{{ asset('css/show.css') }}" rel="stylesheet">
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include Bootstrap's JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@section('content')
    <div class="show container-fluid ">
        <div class="row">
            <div class="col-md-7">
                <div class="image-container mx-auto d-block">
                    <img id="mainImage" src="{{ Storage::url($package->Location) }}" class="card-img-top main-image "
                        alt="{{ $package->PackageName }}">
                </div>
                <div class="image-scroll-container carousel slide mx-auto d-block" id="assetCarousel" data-interval="false">
                    <div class="carousel-inner">
                        @foreach ($assets->chunk(4) as $chunk)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="row">
                                    @foreach ($chunk as $asset)
                                        <div class="col-md-3">
                                            <div class="card">
                                                <div class="model-viewer"
                                                    data-model-path="{{ Storage::url($asset->Location) }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a class="carousel-control-prev" href="#assetCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#assetCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-5">
                <div class="r-body">
                    @if ($user->teams->isNotEmpty())
                        <a href="{{ route('teams.details', ['team' => $user->teams->first()->name]) }}" style="text-decoration: none;"><h4>{{ $user->teams->first()->name }}</h4></a>
                    @endif
                    <h1 class="r-title">{{ $package->PackageName }}</h1>
                    <a href="{{ route('profile.show', ['user' => $user->id]) }}" style="text-decoration: none;"><p>{{ $user->username }}</p></a>
                    <hr>
                    <div class="buy">
                        @if (Auth::id() == $package->UserID)
                            <form action="/package/{{ $package->id }}/edit" method="GET">
                                <button type="submit" style="background-color: dodgerblue; "class="no-underline">
                                    Edit Package
                                </button>
                            </form>
                            <form action="{{ route('asset.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background-color: red; "class="no-underline">Delete Package</button>
                            </form>
                        @else
                            <h3>Download Asset</h3>
                            <p>For more information about the royalties for the asset, <a href="#">click here</a>.</p>
                            <form action="{{ route('paypal') }}" method="POST">
                                @csrf
                                <input type="hidden" name="price" value="{{ $package->Price }}">
                                <button type="submit">
                                    @if (!empty($package->Price) && $package->Price != '0')
                                        <form action="{{ route('paypal') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="price" value="{{ $package->Price }}">
                                            <a class = "no-underline">
                                                Pay ${{ $package->Price }} with PayPal
                                            </a>
                                        </form>
                                    @else
                                        <a href="{{ route('asset.download', $package->id) }}" class = "no-underline">Download
                                            for Free</a>
                                    @endif
                                </button>
                            </form>
                        @endif
                    </div>
                    <hr>
                    <p class="r-text">Package Description:</p>
                    <div class="file-list">
                        <ul>
                            @foreach ($package->assets as $asset)
                                <div class="row">
                                    <div class="col-md-6 size-info-left">
                                        <li>{{ $asset->AssetName }}
                                    </div>
                                    <div class="col-md-6 size-info-right">{{ implode(', ', $fileTypes->toArray()) }} /
                                        {{ number_format($totalSizeMB / 1000, 2) }}mb</div>
                                    </li>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                    <hr>
                    <h3>Tags</h3>
                    <p>
                        @foreach ($package->tags as $tag)
                            <p># {{ $tag->name }}</p>
                        @endforeach
                        @foreach ($package->categories as $category)
                            <p># {{ $category->cat_name }}</p>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this HTML markup -->
    <div id="modelModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalViewerContainer"></div>
        </div>
    </div>

    <!-- Add this CSS code -->
    <style>
        /* Add or adjust modal styles as needed */
        /* Style the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            width: 100%; /* Set width to 100% to cover the entire viewport */
            height: 100%; /* Set height to 100% to cover the entire viewport */
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
            width: 80%; /* Set the desired width */
            max-width: 800px; /* Set a max-width if needed */
            height: 80%; /* Set the desired height */
            max-height: 80vh; /* Set a max-height if needed, e.g., 80% of viewport height */
        }

        /* Style the close button */
        .close {
            color: darkred;
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
            color: orangered;
            text-decoration: none;
            cursor: pointer;
        }

        /* Hide vertical scrollbar */
        body.modal-open {
            overflow: hidden;
        }
    </style>

    <!-- Modify the JavaScript code -->
    <script>
        // Get the modal
        var modal = document.getElementById("modelModal");

        // Get the modal content div
        var modalContent = modal.querySelector('.modal-content');

        // Get the model viewer container
        var modalViewerContainer = modalContent.querySelector("#modalViewerContainer");

        // Get all elements with class="model-viewer"
        var triggers = document.getElementsByClassName("model-viewer");

        // Get the <span> element that closes the modal
        var span = document.querySelector(".close");

        // Variable to store scroll position
        var scrollPosition = 0;

        // Loop through all triggers and attach click event listeners
        Array.from(triggers).forEach(function(trigger) {
            trigger.onclick = function(e) {
                e.preventDefault(); // Prevent default link behavior
                modal.style.display = "block";
                loadFBXModal(this.dataset.modelPath);
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

        // Function to load 3D model into the modal
        function loadFBXModal(modelPath) {
            // Clear the modalViewerContainer before appending a new model viewer
            modalViewerContainer.innerHTML = '';
        
            const scene = new THREE.Scene();
            scene.background = new THREE.Color(0xdddddd);
            // Adjust width and height based on modal content size
            const modalWidth = modalContent.offsetWidth;
            const modalHeight = modalContent.offsetHeight;

            const aspectRatio = modalWidth / modalHeight;
            const width = modalWidth
            const height = width / aspectRatio;
            const camera = new THREE.PerspectiveCamera(50, aspectRatio, 1, 5000);
            camera.position.set(0, 0, 1000);
            const renderer = new THREE.WebGLRenderer({
                antialias: true
            });
            renderer.setSize(width, height);
            modalViewerContainer.appendChild(renderer.domElement);
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

    <script>
        $(document).ready(function() {
            $('.scroll-btn.left').click(function() {
                $('.image-scroll-container').animate({
                    scrollLeft: '-=100'
                }, 'slow');
            });

            $('.scroll-btn.right').click(function() {
                $('.image-scroll-container').animate({
                    scrollLeft: '+=100'
                }, 'slow');
            });
        });
    </script>
    <script>
        function changeMainImage(assetImage) {
            document.getElementById('mainImage').src = assetImage.src;
        }

        var mainImageOriginalSrc = document.getElementById('mainImage').src;

        function resetMainImage() {
            document.getElementById('mainImage').src = "{{ Storage::url($package->Location) }}";
        }
    </script>
@endsection

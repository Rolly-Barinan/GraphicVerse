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
                        <h4>{{ $user->teams->first()->name }}</h4>
                    @endif
                    <h1 class="r-title">{{ $package->PackageName }}</h1>
                    <p>{{ $user->name }}</p>
                    <hr>
                    <div class="buy">
                        <h3>Buy Asset</h3>
                        <p>For more information about the royalties for the asset, <a href="#">click here</a>.</p>
                        <form action="{{ route('paypal') }}" method="POST">
                            @csrf
                            <input type="hidden" name="price" value="{{ $package->Price }}">
                            <button type="submit">
                                @if (!empty($package->Price) && $package->Price != '0')
                                    <form action="{{ route('paypal') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="price" value="{{ $package->Price }}">
                                        <button type="submit">
                                            Pay ${{ $package->Price }} with PayPal
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('asset.download', $package->id) }}" class = "no-underline">Download
                                        for Free</a>
                                @endif
                            </button>
                        </form>
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
                    <p class="r-text">Tags:</p>
                    <!-- <p class="r-text">{{ $package->Description }}</p>
                    <p>File Types: {{ implode(', ', $fileTypes->toArray()) }}</p>
                    <p>File Size: {{ number_format($totalSizeMB / 1000, 2) }}mb</p> -->
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

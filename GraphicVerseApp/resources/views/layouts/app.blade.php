<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GraphicVerse</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/login.css') }}" rel="stylesheets">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css ">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="	https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css ">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"
        integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous">
    </script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   
    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const loginDropdown = document.getElementById('loginDropdown');
        const loginDropdownContent = document.getElementById('loginDropdownContent');
        
        const registerDropdown = document.getElementById('registerDropdown');
        const registerDropdownContent = document.getElementById('registerDropdownContent');

        loginDropdown.addEventListener('mousedown', function (e) {
            e.preventDefault(); // Prevent the anchor link's default behavior
            e.stopPropagation(); // Prevent the event from propagating to the anchor

            loginDropdownContent.classList.toggle('show');
        });

        registerDropdown.addEventListener('mousedown', function (e) {
            e.preventDefault(); // Prevent the anchor link's default behavior
            e.stopPropagation(); // Prevent the event from propagating to the anchor

            registerDropdownContent.classList.toggle('show');
        });

        // Close the login dropdown if the user clicks outside of it
        window.addEventListener('mousedown', function (event) {
            if (!event.target.matches('#loginDropdown')) {
                if (loginDropdownContent.classList.contains('show')) {
                    loginDropdownContent.classList.remove('show');
                }
            }
        });

        // Close the register dropdown if the user clicks outside of it
        window.addEventListener('mousedown', function (event) {
            if (!event.target.matches('#registerDropdown')) {
                if (registerDropdownContent.classList.contains('show')) {
                    registerDropdownContent.classList.remove('show');
                }
            }
        });
    });
</script>

</head>
<nav class="navbar navbar-expand-lg" >

    <div class="container-fluid ">
    <a href="/">
        <img src="/svg/logo.svg" class="logo" alt="Logo">
    <a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav2"
            aria-controls="navbarNav2" aria-expanded="false" aria-label="Toggle navigation">
        </button>
        <div class="collapse navbar-collapse" id="navbarNav2">
            <ul class="navbar-nav pt-2">
                <li class="nav-item pb-3 pe-3">
                    <a class="nav-link" href="/2d">2D</a>
                </li>
                <li class="nav-item pb-3 pe-3" style="">
                    <a class="nav-link" href="/3d">3D</a>
                </li>
                <li class="nav-item pb-3 pe-3" style="">
                    <a class="nav-link" href="/animation">Audio</a>
                </li>
                <li class="nav-item pb-3 pe-3">
                    <a class="nav-link" href="/music">Others</a>
                </li>
            </ul>
            <form class="custom-search-form MT-4" role="search" action="{{ route('search')}}">
                <input class="form-control me-2 custom-search-input" type="search" placeholder="Search assets" name="q"
                    aria-label="Search" style="width: 100%">
                    <!-- <button class="" -->
            </form>
            <ul class="navbar-nav ms-auto me-4 pt-2">
                @guest
                    @if (Route::has('login'))
                    <li class="nav-item pb-3 pe-3 position-relative">
                        <a id="loginDropdown" class="nav-link">
                            {{ __('Login') }}
                        </a>
                        <div id="loginDropdownContent" class="dropdown-content">
                            @include('auth.login')
                        </div>
                    </li>
                    @endif
                    @if (Route::has('register'))
                    <li class="nav-item pb-3 pe-3 position-relative">
                        <a id="registerDropdown" class="nav-link">
                            {{ __('Register') }}
                        </a>
                        <div id="registerDropdownContent" class="dropdown-content">
                            @include('auth.login')
                        </div>
                    </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->profile->profileImage() }}" class="img-fluid rounded-circle" style="height: 30px; width: 30px; margin-right: 5px;">
                            {{ Auth::user()->profile->title }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/profile/{{ Auth::user()->id }}">My Profile</a></li>
                            <li><a class="dropdown-item" href="#">Wishlist</a></li>
                            <li><a class="dropdown-item" href="/teams">Teams</a></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endguest
            </ul>
        </div>
    </div>
</nav>


<main class="">
    @yield('content')
</main>

<!-- ... (Your scripts) ... -->

</body>

</html>
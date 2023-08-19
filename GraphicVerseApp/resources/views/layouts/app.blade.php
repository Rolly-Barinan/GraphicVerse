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
    <!-- Scripts -->
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
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>

<style>
    body {
        font-family: 'Nunito', sans-serif;
        background-image: url('https://cdn.discordapp.com/attachments/1121006331323760680/1123571308496691210/Copy_of_GraphicVerse_Capstone_Hearing.png');
        background-size: cover;
        background-repeat: no-repeat;
    }

    .navbar-nav .nav-item {
        display: flex;
    }
</style>

<nav class="navbar-fluid navbar-expand-lg shadow">
    <div class="container-fluid pt-2">
        <div class="row ">


            <div class="col" style="display: flex; align-items: center;" href="{{ url('/') }}">
                <img src="/svg/GraphicVerse_Capstone.png" class="pr-3" style="height: 40px; margin-left: 4rem;">
                <a class="GraphicVerse navbar-brand text-white" style="margin-left: 10px;">
                    GraphicVerse
                </a>
            </div>

            <div class="col " style="margin-top: 10px">
                <form class="d-flex custom-search-form" role="search">
                    <input class="form-control me-2 custom-search-input" type="search" placeholder=" &#128269; Search"
                        aria-label="Search">
                </form>
            </div>


            <div class="col" style="margin-top: 10px">
                {{-- <div class="collapse navbar-collapse" id="navbarSupportedContent"> --}}
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-white pe-4" aria-current="page" href="/home">HOME</a>
                        <a class="nav-link text-white pe-4" href="/aboutus">ABOUT US</a>
                        <a class="nav-link text-white pe-4" href="/community">COMMUNITY</a>
                    </li>
                </ul>
                {{-- </div> --}}
            </div>
            <div class="mx-auto col-auto ps-5" style="margin-top: 10px">
                <ul class="navbar-nav ms-auto ps-5">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item pe-2">
                                <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item pe-2">
                                <a class="nav-link text-white" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-lg" type="button" data-bs-toggle="dropdown"
                                style="background-color: #499096;  border-bottom-right-radius: 25px;
                                border-bottom-left-radius: 25px; "
                                aria-expanded="false">
                                <div class="row" style="  text-align: center;">
                                    <div class="col"> {{ Auth::user()->name }}</div>
                                    <div class="col">
                                        <img src="/svg/GraphicVerse_Capstone.png" class="" style="height: 40px;">
                                    </div>
                                </div>

                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/profile/{{ Auth::user()->id }}">My Profile</a></li>
                                <li><a class="dropdown-item" href="#">My Portfolio</a></li>
                                <li><a class="dropdown-item" href="#">Wishlist</a></li>
                                <li><a class="dropdown-item" href="/teams">Teams</a></li>
                                <li> <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a></li>

                            </ul>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>


                    @endguest
                </ul>
            </div>

            <div class="row justify-content-center navTwo">
                <div class="col-auto navbar-nav ">
                    <div class="d-flex justify-content-center space-between">
                        <a class="twod nav-link text-white pe-5" href="/2d">2D</a>
                        <a class="threed nav-link text-white pe-5" href="/3d">3D</a>
                        <a class="others nav-link text-white pe-5  " href="animation">Animation</a>
                        <a class="others nav-link text-white pe-5  " href="music">Sound/Music</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>




<main class="">
    @yield('content')
</main>


</html>

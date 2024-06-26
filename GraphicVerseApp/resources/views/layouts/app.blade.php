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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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

</head>
<nav class="navbar navbar-expand-lg @if(!Request::is('/')) navbar-not-home @endif" id="navbar">
    <div class="container-fluid ps-5 pe-5 d-flex justify-content-between align-items-center">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link ms-5 me-5" href="/2d-models">2D</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-5 me-5" href="/3d-models">3D</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-5 me-5" href="/audio-models">Audio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link ms-5 me-5" href="/image">Artworks</a>
                </li>
            </ul>
        </div>
        <a class="navbar-brand" href="/">
            <img src="/svg/logo.svg" class="logo" alt="Logo">
        </a>
        <div class="d-flex justify-content-end align-items-center">
            <div class="collapse navbar-collapse ps-5" id="navbarNav2">
                <div class="test container-fluid pt-3 @if(!Request::is('/')) test container-fluid pt-0 @endif">
                    <form class="d-flex" action="{{ route('search') }}" method="GET">
                        <input class="search form-control" type="search" placeholder="Search assets" name="q"
                            aria-label="Search" style="width: 15vw; color: #E6E7FD;">
                    </form>
                </div>
                <ul class="navbar-nav">
            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link2">{{ __('Login') }}</a>
                    </li>
                @endif<nav class="navbar navbar-expand-lg @if(!Request::is('/') && !Request::is('home')) navbar-not-home @endif" id="navbar"><nav class="navbar navbar-expand-lg @if(!Request::is('/') && !Request::is('home')) navbar-not-home @endif" id="navbar">
            @else
            <!-- <div class="profile container"> -->
            <li class="nav-item dropdown">
                <a class="nav-link2 dropdown-toggle text-white" href="#" id="navbarDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->profile->title }}
                    <img src="{{ Auth::user()->profile->profileImage() }}" class="rounded-circle"
                        style="height: 50px; width: 50px; margin-right: 5px;">
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown" onclick="event.stopPropagation();" style="padding-top: 100px;">
                    <a class="dropdown-item" href="/profile/{{ Auth::user()->id }}">{{  Auth::user()->name }}
                    <p class="view">View Profile</p>
                    </a>
                    <a class="dropdown-item" href="/purchased">Purchase History</a>
                    <a class="dropdown-item"href="/profile/{{ Auth::user()->id }}/edit">Edit Profile</a>
                    <a class="dropdown-item teams-toggle" href="#">
                        Teams
                    </a>
                    <div class="teams-list" style="display: none;">
                        @foreach (Auth::user()->teams as $team)
                            <a class="dropdown-item" style="font-weight: normal !important; color: #5F5F79 !important;" href="{{ route('teams.details', ['team' => $team->name]) }}">{{ $team->name }}</a>
                        @endforeach
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <hr style="
                                width: 10% !important;
                                justify-content: center !important; 
                                margin: auto !important;
                                margin-bottom: 3px !important;
                            ">
                        <hr style="
                                width: 10% !important;
                                justify-content: center !important;
                                margin: auto !important;
                                margin-bottom: 3px !important;
                            ">
                </div>
            </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
            @endguest
        </ul>
            </div>
        </div>
    </div>
</nav>


<main class="" id="main-content">
    @yield('content')
</main>

</body>
</html>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    // $('.dropdown-toggle').on('click', function(e) {
    //     e.preventDefault();
    //     $(this).next('.dropdown-menu').stop(true, true).slideDown();
    // });

    // $(document).on('click', function(e) {
    //     if ($(e.target).closest('.dropdown').length === 0) {
    //         $('.dropdown-menu').stop(true, true).slideUp();
    //     }
    // });
    $(document).ready(function(){
        $('.dropdown').click(function(){
            $(this).find('.dropdown-menu').slideToggle();
        });
    });
});
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
$(document).ready(function() {
    $('.teams-toggle').on('click', function(e) {
        e.preventDefault();
        $('.teams-list').stop(true, true).slideToggle();
    });

    $(document).on('click', function(e) {
        if ($(e.target).closest('.dropdown').length === 0) {
            $('.teams-list').stop(true, true).slideUp();
        }
    });
});
</script>
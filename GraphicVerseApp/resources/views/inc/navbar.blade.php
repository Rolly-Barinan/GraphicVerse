<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
</head>

<body>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>


    <nav class="bg-danger navbarmain navbar navbar-expand-lg bg-body-tertiary ">
        <div class="container-fluid">
            <img src="/svg/GraphicVerse_Capstone.png" class="pr-3" style="height: 40px; margin-left: 4rem;">
            <a class="navbar-brand" href="#">GraphicVerse</a>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Community</a>
                    </li>


                    </li>

                </ul>

            </div>
        </div>
        <div class="mx-auto col-auto ps-5" style="margin-top: 10px">
            <ul class="navbar-nav ms-auto ps-5">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item pe-2">
                            <a class="nav-link " href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item pe-2">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-lg" type="button" data-bs-toggle="dropdown"
                            {{-- style="background-color: #499096;  border-bottom-right-radius: 25px;
                        border-bottom-left-radius: 25px; " --}} aria-expanded="false">
                            <div class="row" style="  text-align: center;">
                                <div class="col">
                                    <img src="/svg/GraphicVerse_Capstone.png" class="" style="height: 40px; ">
                                </div>
                                <div class="col"> {{ Auth::user()->name }}</div>

                            </div>

                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown -item" href="/profile/{{ Auth::user()->id }}">My Profile</a></li>
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
    </nav>


    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid d-flex center">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="#">2D</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">3D</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Animation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Others</a>
                    </li>


                    </li>

                </ul>

            </div>
        </div>
    </nav>
</body>

</html>

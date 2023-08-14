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
    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>

<style>
    body {
        background-image: url('https://cdn.discordapp.com/attachments/1121006331323760680/1123571308496691210/Copy_of_GraphicVerse_Capstone_Hearing.png');
        background-size: cover;
        background-repeat: no-repeat;
    }

    .navbar-nav .nav-item {
        display: flex;
    }

    .sidebar {
        background-color: #333;
        color: white;
        padding: 15px;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .profile {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 50px;
        align-self: center;
    }

    /* Style the profile section */
    .profile img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-bottom: 10px;
    }

    .profile h5 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
    }

    /* Style the navigation links */
    .nav-link {
        color: #ccc;
        transition: color 0.3s;
    }

    .nav-link.active {
        color: #fff;
        font-weight: bold;
    }

    /* Add spacing between navigation items */
    .nav-item {
        margin-bottom: 10px;
    }

    /* Style the logout link */
    .nav-link i {
        margin-right: 5px;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-2 sidebar bg-dark">
                <div class="profile text-center">
                    <img src="{{ asset('css\admin\admin-avatar.png') }}" alt="Admin Avatar">
                    <div class="mt-2">
                        <h5>{{ $admin->name }}</h5>
                    </div>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Add Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Delete Category</a>
                    </li>
                </ul>
                <div class="nav flex-column mt-auto">
                    <a class="nav-link" style="color: red" href="{{ route('admin.logout') }}">
                        LOGOUT
                    </a>
                </div>
            </div>

            <!-- Right Content -->
            <div class="col">
                <div class="content">
                    @yield('admin-content')
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>

<style>
    body {
        background-color: #dcdcdc;
        /* or any other shade of grey you prefer */
    }

    .content {
        color: black;
        /* Set the font color to black */
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
            <!-- Navbar with Collapse Button (updated structure) -->
            <nav class="navbar navbar-expand-md navbar-dark bg-dark d-md-none">
                <!-- Collapsible Sidebar Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                    aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </nav>

            <!-- Left Sidebar -->
            <div class="col-md-2 sidebar bg-dark">
                <div class="profile text-center">
                    <img src="{{ asset('css\admin\admin-avatar.png') }}" alt="Admin Avatar">
                    <div class="mt-2">
                        <h5>{{ $admin->name }}</h5>
                    </div>
                </div>
                <hr>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories') }}">
                            <i class="fas fa-solid fa-list"></i> Manage Categories
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories') }}">
                            <i class="fas fa-solid fa-box-open"></i> Manage Packages
                        </a>
                    </li>
                </ul>
                <div class="nav flex-column mt-auto">
                    <a class="nav-link mb-4" style="color: red" href="{{ route('admin.logout') }}">
                        <i class="fas fa-sign-out-alt"></i> LOGOUT
                    </a>
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('svg\GraphicVerse_Logo.png') }}" alt="Logo" style="max-width: 50%;">
                    </div>
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

    <!-- Bootstrap JS (added to enable responsive behavior) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JavaScript to handle sidebar toggling -->
    <script>
        $(document).ready(function () {
            // Function to check the screen size and toggle the sidebar accordingly
            function toggleSidebar() {
                if ($(window).width() < 768) {
                    $(".sidebar").addClass("d-none");
                } else {
                    $(".sidebar").removeClass("d-none");
                }
            }

            // Initial check when the page loads
            toggleSidebar();

            // Check the screen size when the window is resized
            $(window).resize(function () {
                toggleSidebar();
            });

            // Toggle sidebar when the button is clicked
            $(".navbar-toggler").click(function () {
                $(".sidebar").toggleClass("d-none");
            });
        });
    </script>
</body>

</html>
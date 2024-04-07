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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oswald&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .navbar-brand,
        .nav-link {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .avatar-container {
            width: 30px;
            height: 30px;
            background-color: #ccc;
            border-radius: 5px;
            overflow: hidden;
            margin-right: 10px;
        }

        .avatar-container img.avatar {
            width: 100%;
            height: auto;
        }

        .navbar-nav .dropdown-menu {
            top: 40px;
        }

        .navbar-nav {
            width: 100%;
            text-align: center;
        }

        .navbar-nav .nav-item {
            display: inline-block;
        }

        .navbar-nav .nav-link {
            margin-right: 15px;
        }

        @media (min-width: 576px) {
            .navbar-nav .nav-item {
                display: inline-block;
            }
        }

        @media (max-width: 575px) {
            .navbar-nav .nav-item {
                display: none;
            }

            .navbar-nav.collapsed .nav-item {
                display: inline-block;
            }
        }

        .navbar-nav .nav-item .nav-link.active {
            background-color: #6c757d; /* Choose your desired color */
            border-radius: 0.25rem; /* Optional: Add border radius for rounded corners */
        }

        body {
            background-color: gray;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-xl d-flex justify-content-between align-items-center">
            <!-- Navbar brand -->
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <img src="/svg/logo.svg" class="logo" alt="Logo" height="50px">
            </a>            
            <!-- Admin avatar and name -->
            <div class="nav-item dropdown">
                <div class="d-flex align-items-center">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                        <div class="avatar-container me-2">
                            <img src="{{ asset('css\admin\admin-avatar.png') }}" alt="Admin Avatar" class="avatar">
                        </div>
                        {{ $admin->name }}
                    </a>
                    
                    <!-- Dropdown menu -->
                    <div class="dropdown-menu dropdown-menu-end">
                        {{-- <a href="#" class="dropdown-item">Profile</a> --}}
                        <a href="#" class="dropdown-item"><i class="fas fa-cog"></i> Settings</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.logout') }}" class="dropdown-item">
                            <i class="fa fa-fw fa-power-off text-red"></i> Log Out
                        </a>
                        <!-- Logout form -->
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
            <!-- Navbar toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
                aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    
    <nav class="navbar navbar-expand-md navbar-dark bg-dark" style="border-top: 0.1px solid gray;">
        <div class="container-xl">
            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="navbar-nav mx-auto">
                    <!-- Dashboard link -->
                    <li class="nav-item">
                        <a class="nav-link{{ Request::is('admin/dashboard') ? ' active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    </li>
                    <!-- Manage Categories link -->
                    <li class="nav-item">
                        <a class="nav-link{{ Request::is('admin/categories') ? ' active' : '' }}" href="{{ route('admin.categories') }}"><i class="fas fa-solid fa-list"></i> Categories</a>
                    </li>
                    <!-- Manage Users link -->
                    <li class="nav-item">
                        <a class="nav-link{{ Request::is('admin/users*', 'admin/users/details/*') ? ' active' : '' }}" href="{{ route('admin.users') }}"><i class="fas fa-users"></i> Users</a>
                    </li>
                    <!-- Manage Packages link -->
                    <li class="nav-item">
                        <a class="nav-link{{ Request::is('admin/packages*', 'admin/packages/details/*') ? ' active' : '' }}" href="{{ route('admin.packages') }}"><i class="fas fa-solid fa-box-open"></i> Packages</a>
                    </li>
                    <!-- Manage Assets link -->
                    <li class="nav-item">
                        <a class="nav-link{{ Request::is('admin/images*', 'admin/images/details/*') ? ' active' : '' }}" href="{{ route('admin.imageAssets') }}"><i class="fas fa-solid fa-file-image"></i> Image Assets</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mb-5">
        <div class="row">
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

    <!-- JavaScript to handle navbar toggling -->
    <script>
        $(document).ready(function () {
            // Function to check the screen size and toggle the navbar accordingly
            function toggleNavbar() {
                if ($(window).width() < 576) {
                    $(".navbar-nav").addClass("collapsed");
                } else {
                    $(".navbar-nav").removeClass("collapsed");
                }
            }

            // Initial check when the page loads
            toggleNavbar();

            // Check the screen size when the window is resized
            $(window).resize(function () {
                toggleNavbar();
            });

            // Toggle navbar when the button is clicked
            $(".navbar-toggler").click(function () {
                $(".navbar-nav").toggleClass("collapsed");
            });
        });
    </script>
</body>

</html>

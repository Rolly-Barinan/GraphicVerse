@extends('layouts.app')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.15.0/font/bootstrap-icons.css" rel="stylesheet">

@section('content')
    <div class="container pt-4">
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    @include('auth.login')
                </div>
            </div>
        </div>

        <div class="row justify-content-center pt-4">
            <div class="col justify-content-center mt-5">
                <img src="/svg/dino.gif" class="dino">
                <h1 class="elevate-title">The one-stop shop for your multimedia asset needs.</h1>
                <p class="experience">Experience the power of efficient asset management, collaborative workspaces, and a stunning portfolio showcase. Join GraphicVerse and elevate your creative journey to new heights.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Get started</button><i class="bi bi-chevron-right"></i>
            </div>
        </div>
    </div>

    <div class="row-2 justify-content-center">
        <div class="col justify-content-center">
            <img src="/svg/astronautcaptain.png" class="astro">
            <h1 class="elevate-title text-white">Discover the creativity and skills of Josenian Multimedia Students.</h1>
            <a class="link text-white mt-5 me-5" href="/2d">2D</a>
            <a class="link text-white mb-5 me-5" href="/3d">3D</a>
            <a class="link text-white mb-5 me-5" href="/animation">Audio</a>
            <a class="link text-white mb-5" href="/music">Others</a>
        </div>
    </div>

    <div class="row-3 justify-content-center align-items-center">
        <div class="col justify-content-center">
            <h1 class="elevate-title pt-5 mt-5">Join our Discord Server!</h1>
            <p class="experience mt-4">Join our community to collaborate, communicate and exchange creative ideas with other artists!.</p>
            <button type="button" class="btn btn-primary mt-5" id="learnMoreButton">Join Discord</button>
        </div>
    </div>

    <script>
        document.getElementById('learnMoreButton').addEventListener('click', function (e) {
            e.preventDefault();
            var dino = document.querySelector('.dino');
            dino.classList.add('slide-out');
            dino.addEventListener('transitionend', function () {
                var href = e.target.getAttribute('href');
                window.location.href = href;
            });
        });
    </script>
@endsection
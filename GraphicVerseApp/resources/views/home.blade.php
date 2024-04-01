@extends('layouts.app')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.15.0/font/bootstrap-icons.css" rel="stylesheet">

@section('content')
<div class="container pt-5 mt-5">
    <div class="row  justify-content-center pt-4">
        <div class="col">
            <img src="/svg/astronautcaptain.png" alt="Image" class="astro">
        </div>
                <div class="col">
                    <h1 class="elevate-title">The one-stop shop for your multimedia asset needs.</h1>
                    <p class="experience">Experience the power of efficient asset management, collaborative workspaces, and a stunning portfolio showcase. Join GraphicVerse and elevate your creative journey to new heights.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">Get started <i class="bi bi-chevron-right"></i></a>
                </div>
        </div>
    </div>
    <div class="row-2 justify-content-center">
            <div class="col justify-content-center">
                <img src="/svg/astronautcaptain.png" class="astro">
                <h1 class="elevate-title text-white">Discover the creativity and skills of Josenian Multimedia Students.</h1>
                <a class="link text-white mt-5 me-5" href="/2d">2D</a>
                <a class="link text-white mb-5 me-5" href="/3d">3D</a>
                <a class="link text-white mb-5 me-5" href="/audio-models">Audio</a>
                <a class="link text-white mb-5" href="/music">Others</a>
            </div>
        </div>
    <div class="row-3 justify-content-center align-items-center ">
        <div class="col justify-content-center">
            <h1 class="elevate-title pt-5 mt-5">Join our Discord Server!</h1>
            <p class="experience mt-4"> Join our community to collaborate, communicate and exchange creative ideas with other artists!.
                </p>
            <button type="button" class="learnmore mt-5" id="learnMoreButton">Join Discord</button>
    
        </div>
    </div>
    <script>
    document.getElementById('learnMoreButton').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default link behavior

        var dino = document.querySelector('.dino');

        // Add a CSS class to trigger the animation
        dino.classList.add('slide-out');

        // Wait for the animation to finish, then navigate to the link
        dino.addEventListener('transitionend', function() {
            // Get the href attribute of the button
            var href = e.target.getAttribute('href');

            // Navigate to the link
            window.location.href = href;
        });
    });
</script>
<script>
    document.body.addEventListener("pointermove", (e)=>{
  const { currentTarget: el, clientX: x, clientY: y } = e;
  const { top: t, left: l, width: w, height: h } = el.getBoundingClientRect();
  el.style.setProperty('--posX',  x-l-w/2);
  el.style.setProperty('--posY',  y-t-h/2);
})
    </script>
    
@endsection

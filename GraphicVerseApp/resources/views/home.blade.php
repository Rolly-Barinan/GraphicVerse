@extends('layouts.app')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.15.0/font/bootstrap-icons.css" rel="stylesheet">

@section('content')
<div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
  <div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
  <div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
  <div class="particle pt-0 mt-0 justify-content-center inline-flex"></div>
<div class="container pt-5 mt-5 justify-content-center inline-flex">

    <div class="row justify-content-center">
            <div class="col">
                <h1 class="elevate-title">The one-stop shop for your multimedia asset needs.</h1>
                <p class="experience">Experience the power of efficient asset management, collaborative workspaces, and a stunning portfolio showcase. Join GraphicVerse and elevate your creative journey to new heights.</p>
                <button href="{{ route('login') }}" class="learnmore mt-5">Get started <i class="bi-chevron-right"></i></button>
            </div>
            <div class="col">
                <img src="/svg/astronautcaptain.png" alt="Image" class="astro">
            </div>
            
    </div>
    <div class="row row2 justify-content-center">
        <div class="col-6">
            <!-- <img src="/svg/astronautcaptain2.png" class="img2"> -->
        </div>
        <div class="col-6 linkCol" style="margin-top: -21cqw; padding-right: 5vw">
            <h1 class="discover">Discover the creativity and skills of Josenian Multimedia Students.</h1>
                <a class="link" href="/2d">2D</a>
                <a class="link" href="/3d">3D</a>
                <a class="link" href="/audio-models">Audio</a>
                <a class="link text-white" href="/music">Others</a>
        </div>
    </div>
    <div class="row-3 justify-content-center align-items-center ">
        <div class="col justify-content-center">
            <h1 class="elevate-title">Join our Discord Server!</h1>
            <p class="experience"> Join our community to collaborate, communicate and exchange creative ideas with other artists!.
                </p>
            <div class="discord-container">
                <button type="button" class="discord" id="learnMoreButton">Join Discord</button>
            </div>
</div>
            

    </div>

</div>

<script>
    document.body.addEventListener("pointermove", (e)=>{
  const { currentTarget: el, clientX: x, clientY: y } = e;
  const { top: t, left: l, width: w, height: h } = el.getBoundingClientRect();
  el.style.setProperty('--posX',  x-l-w/2);
  el.style.setProperty('--posY',  y-t-h/2);
})
    </script>
    
@endsection


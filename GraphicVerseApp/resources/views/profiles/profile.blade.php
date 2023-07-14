@extends('layouts.app')

@section('content')
    <div class="container-fluid py-50 " style="background-color: #DDDDE4;   height: 50rem;">
        <div class="row-fluid image-container   border-2">
            <img src="/svg/1201120.jpg" class="img-fluid" alt="...">
        </div>

        <div class="row">
            <div class="col-3 p-2 d-flex justify-content-center align-items-start">
                <div class="rounded-circle-container">
                    <img src="/storage/{{$user->profile->image }}" class="rounded-circle img-fluid" alt="...">
                </div>
            </div>
            <div class="col-5 pt-3 ">
                <div>
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="h4" class="ml-4 p"> {{ $user->username }} </div>


                        </div>

                    </div>
                    @can('update', $user->profile)
                        <a href="/profile/{{ $user->id }}/edit">edit profile</a>
                    @endcan


                    <div class="d-flex p-4">

                        {{-- <div style=" padding-right:20px;"><strong>{{$user->posts->count()}} </strong> posts</div> --}}
                        <div style=" padding-right:20px;"><strong>20k</strong>followers</div>
                        <div style=" padding-right:20px;"><strong>400</strong>following</div>

                    </div>
                    <div class="">{{ $user->profile->title }}</div>
                    <div> {{ $user->profile->description }}</div>
                    <div> <a href=""> {{ $user->profile->url ?? 'N/A' }}</a></div>
                    <div>hello</div>
                </div>

            </div>
            <div class="col-2 d-flex justify-content-end ps-5 pt-2 align-items-start ">


                @can('update', $user->profile)
                    <button type="button" class="btn btn-secondary btn-lg  " data-bs-toggle="modal"
                        data-bs-target="#exampleModal"
                        style="--bs-btn-padding-y: .7rem; --bs-btn-padding-x: 3.5rem; --bs-btn-font-size: .9rem;">Upload
                    </button>
                @endcan

            </div>
            <div class="col-2 col-2 d-flex justify-content-top pt-2 align-items-start">
                <button type="button" class="btn btn-secondary btn-lg"
                    style="--bs-btn-padding-y: .7rem; --bs-btn-padding-x: 3.5rem; --bs-btn-font-size: .9rem;">Connect</button>
            </div>
        </div>
        <div class="row pt-5">
            {{-- @foreach ($user->posts as $post)
        <div class="col-4 p-4">

            <a href="/p/{{ $post->id }}">
                <img src="/storage/{{ $post->image }}" class="w-100">
            </a>
        </div>
        @endforeach --}}

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Upload Asssets</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <button type="button" class="btn btn-primary">3D</button>
                            <button type="button" class="btn btn-secondary">2D</button>
                            <button type="button" class="btn btn-success">Audio</button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        </div>
                    </div>
                </div>
            </div>
        @endsection

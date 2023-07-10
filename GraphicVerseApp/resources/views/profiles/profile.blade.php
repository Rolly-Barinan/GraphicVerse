@extends('layouts.app')

@section('content')
    <div class="container-fluid py-50 " style="background-color: #DDDDE4;   height: 50rem;">
        <div class="row-fluid image-container   border-2">
            <img src="/svg/1201120.jpg" class="img-fluid" alt="...">
        </div>

        <div class="row">
            <div class="col-3 p-2 d-flex justify-content-center align-items-start">
                <div class="rounded-circle-container">
                    <img src="/svg/1201120.jpg" class="rounded-circle img-fluid" alt="...">
                </div>
            </div>
            <div class="col-5 pt-3 ">
                <div>
                    <div class="d-flex justify-content-between align-items-baseline">
                        <div class="d-flex">
                            <div class="h4" class="ml-4 p"> {{ $user->username }} </div>
                            <example-component></example-component>

                        </div>
                        @can('update', $user->profile)
                            <a href="/p/create">add new post</a>
                        @endcan


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
                </div>

            </div>
            <div class="col-2 d-flex justify-content-end ps-5 pt-2 align-items-start ">
                <button type="button" class="btn btn-secondary btn-lg "
                    style="--bs-btn-padding-y: .8rem; --bs-btn-padding-x: 3.5rem; --bs-btn-font-size: 1.2rem;">Follow</button>
            </div>
            <div class="col-2 col-2 d-flex justify-content-top pt-2 align-items-start">
                <button type="button" class="btn btn-secondary btn-lg"
                    style="--bs-btn-padding-y: .8rem; --bs-btn-padding-x: 3.5rem; --bs-btn-font-size: 1.2rem;">Connect</button>
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

        </div>
    @endsection

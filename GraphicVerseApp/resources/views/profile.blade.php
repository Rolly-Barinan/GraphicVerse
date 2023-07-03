@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-3 p-5">
                {{-- <img src="{{$user->profile->profileImage()}}" class=" rounded-circle w-100"> --}}
            </div>
            <div class="col-9 pt-5 ">
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

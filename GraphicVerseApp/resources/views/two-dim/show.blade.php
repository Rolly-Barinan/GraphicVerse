@extends('layouts.app')
<link href="{{ asset('css/show.css') }}" rel="stylesheet">
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8 vh-100">
                <div class="model-card">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ Storage::url($model2D->filename) }}" alt="{{ $model2D->twoD_name }} Image" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                    <div class="info-card">
                        <h3 class="user-team">Team Webslingers</h3>
                        <h2 class="model-name">{{ $model2D->twoD_name }}</h2> 
                        <h3 class="user-name"> {{ $model2D->creator_name }}
                            <span class="dl float-end me-2"><i class="dl-icon fas fa-download"></i>
                                <span style="font-family: 'Oswald'; font-size: 20px; color: #9494AD; ">(37)</span>
                            </span>
                            <span class="heart float-end me-2"><i class="fas fa-heart"></i>
                                <span style="font-family: 'Oswald'; font-size: 20px; color: #9494AD;">(10)</span>
                            </span>
                        </h3>
                        <hr>
                        <div class="buy-card">
                            <h3 class="bold-text">Buy Asset</h3>
                            <p class="buy-info">For more information about the royalties for the asset, <a href class="buy-info link">click here.</a>
                            <button class="buy-btn">P 499.99</button>
                        </div>
                        <hr>
                        <h3 class="category">
                            @foreach ($model2D->categories2D as $index => $category)
                                {{ $category->cat_name }}
                                @if ($index < count($model2D->categories2D) - 1)
                                    ,
                                @endif
                            @endforeach
                        </p>
                        </h3>                     
                        {{-- Add more details as needed --}}
                        @if(Auth::check() && Auth::user()->id === $model2D->user2d->user_id)
                            <a href="{{ route('twoD.edit', $model2D->id) }}" class="btn btn-primary">Edit</a>
                            {{-- Delete button --}}
                            <form action="{{ route('twoD.destroy', $model2D->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this 2D asset?')">Delete</button>
                            </form>
                        @endif
                    </div>
            </div>
        </div>
    </div>
@endsection

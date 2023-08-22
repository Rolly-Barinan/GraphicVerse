@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $model2D->twoD_name }} Details</div>
                    
                    <div class="card-body">
                        <div class="text-center">
                            <img src="{{ Storage::url($model2D->filename) }}" alt="{{ $model2D->twoD_name }} Image" class="img-fluid">
                        </div>
                        <hr>
                        <p><strong>Description:</strong> {{ $model2D->description }}</p>
                        <p><strong>Category:</strong>
                            @foreach ($model2D->categories2D as $index => $category)
                                {{ $category->cat_name }}
                                @if ($index < count($model2D->categories2D) - 1)
                                    ,
                                @endif
                            @endforeach
                        </p>                        
                        <p><strong>Creator:</strong> {{ $model2D->creator_name }}</p>
                        {{-- Add more details as needed --}}
                        @if(Auth::check() && Auth::user()->id === $model2D->user2d->user_id)
                            <a href="{{ route('twoD.edit', $model2D->id) }}" class="btn btn-primary">Edit</a>
                        @endif
                        <a href="/2d" class="btn btn-primary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Packages</h1>

    <div class="row">
        @foreach ($packages as $package)
            <div class="col-md-4 mb-4">
                <a href="{{ route('asset.show',  ['id' => $package->id]) }}">
                    <div class="card">
                        <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $package->PackageName }}</h5>
                            <p class="card-text">{{ $package->Description }}</p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>


    
</div>
@endsection

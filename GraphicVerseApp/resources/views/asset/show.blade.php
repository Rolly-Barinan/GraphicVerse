@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Package Details</h1>

    <div class="card">
        <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
        <div class="card-body">
            <h5 class="card-title">{{ $package->PackageName }}</h5>
            <p class="card-text">{{ $package->Description }}</p>
            <p>Price: ${{ $package->Price }}</p>
        </div>
    </div>

    <form action="{{ route('paypal') }}" method="POST">
    @csrf
    <input type="hidden" name="price" value={{ $package->Price  }}>
    <button type="submit"> Pay with paypal</button>
    </form>
    <a href="{{ route('asset.index') }}" class="btn btn-primary">Back to Packages</a>
    <a href="{{ route('asset.download', $package->id) }}" class="btn btn-success">Download</a>
</div>
@endsection

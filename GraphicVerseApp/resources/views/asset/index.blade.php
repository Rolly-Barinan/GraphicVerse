@extends('layouts.app') 

@section('content')
<div class="container">
    <h1>Packages</h1>

    <ul>
        @foreach ($packages as $package)
             <li>{{ $package->PackageName }}</li>
           
        @endforeach
    </ul>
</div>
@endsection

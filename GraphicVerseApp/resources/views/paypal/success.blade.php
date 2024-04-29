@extends('layouts.app')
@section('content')
    <div class="show container-fluid ">
        <h1>Payment Successful</h1>
        <h1>Receipt</h1>
        <h1>Payment Successful</h1>
        <p>Package ID: {{ $packageID }}</p>
        <p>Price: ${{ $price }}</p>     
        <a href="/home">back to  home</a>
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <h1>Packages</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Images</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $package)
                <tr>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->category }}</td>
                    <td>{{ $package->sub_category }}</td>
                    <td>
                        @foreach ($package->images as $image)
                            <img src="/storage/{{ $image->filename }}" alt="Package Image" width="100">
                            <p>{{ $image->filename }}</p>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>x`
    </table>



    <iframe width="560" height="315" src="https://www.youtube.com/embed/watch?v=QvBBaPHNabg&list=RDQvBBaPHNabg&start_radio=1"
        frameborder="0" allowfullscreen></iframe>
@endsection

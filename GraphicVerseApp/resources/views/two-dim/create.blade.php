@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white ">
        <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
        <div>
            <label for="name">Package Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="category">Category</label>
            <select name="category" id="category" required>
                <option value="2D">2D</option>
            </select>
        </div>
        <div>
            <label for="sub_category">select category</label>
            <select name="sub_category" id="sub_category">
                <option value="fonts">Fonts</option>
                <option value="character">Character</option>
                <option value="environment">Environment</option>
                <!-- Add more sub-category options as needed -->
            </select>
        </div>
        <div>
            <label for="images">Images</label>
            <input type="file" name="images[]" id="images" multiple required>
        </div>
        <button type="submit">Create Package</button>
            {{-- <div class="row">
                <div class="col-7">
                    <div class=""> <button type="button" class="btn btn-primary"
                            style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;">
                            Custom button
                        </button>
                    </div>
                </div>
                <div class="col-5">

                </div>

            </div> --}}


        </form>
    </div>
@endsection

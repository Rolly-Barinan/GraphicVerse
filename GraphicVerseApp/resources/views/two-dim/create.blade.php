@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white ">
        <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-8 offset-2">
                    <div class="row mb-3 pt-3">
                        <label for="package_name" class="col-md-4 col-form-label text-md-end ">Package Title </label>
                        <div class="col-md-6">
                            <input id="package_name" type="text"
                                class="form-control @error('package_name') is-invalid @enderror" name="package_name"
                                autocomplete="package_name" autofocus>

                            @error('package_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <input id="category" type="text" class="form-control @error('category') is-invalid @enderror"
                        name="category" value="2D" autocomplete="category" readonly hidden>

                    <div class="row mb-3">
                        <label for="sub_category">select category</label>
                        <select class="form-select" aria-label="Default select example" name="sub_category"
                            id="sub_category">
                            <option selected value="props">props</option>
                            <option value="fonts">Fonts</option>
                            <option value="character">Character</option>
                            <option value="environment">Environment</option>
                        </select>
                    </div>
                    <div class="row mb-3">
                        <label for="description" class="col-md-4 col-form-label text-md-end ">Description </label>

                        <div class="col-md-6">
                            <input id="description" type="text"
                                class="form-control @error('description') is-invalid @enderror" name="description"
                                value="{{ old('description') }}" autocomplete="description" autofocus>

                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="col-8 offset-2">
                    <div class="row mb-3">
                        <label for="images" class="col-md-4 col-form-label text-md-end ">images files</label>

                        <div class="col-md-6">
                            <input id="images" type="file"
                                class="form-control-file @error('images') is-invalid @enderror" name="images[]"
                                value="{{ old('images') }}" autocomplete="images" autofocus multiple required>

                            @error('images')
                                <strong>{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-2 offset-6">
                    <div class="row pt-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>

            </div>
        </form>
      
    </div>
@endsection

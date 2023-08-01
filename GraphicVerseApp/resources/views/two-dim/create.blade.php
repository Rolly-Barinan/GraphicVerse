@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Create a New Package</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="package_name" class="col-md-4 col-form-label text-md-right">Package Title</label>
                                <div class="col-md-6">
                                    <input id="package_name" type="text" class="form-control @error('package_name') is-invalid @enderror"
                                        name="package_name" value="{{ old('package_name') }}" autocomplete="package_name" autofocus>
                                    @error('package_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <input id="category" type="text" class="form-control @error('category') is-invalid @enderror"
                                name="category" value="2D" autocomplete="category" readonly hidden>

                            <div class="form-group row">
                                <label for="sub_category" class="col-md-4 col-form-label text-md-right">Select Category</label>
                                <div class="col-md-6">
                                    <select class="form-select @error('sub_category') is-invalid @enderror" aria-label="Default select example" name="sub_category"
                                        id="sub_category">
                                        <option selected value="props">Props</option>
                                        <option value="fonts">Fonts</option>
                                        <option value="character">Character</option>
                                        <option value="environment">Environment</option>
                                    </select>
                                    @error('sub_category')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                                <div class="col-md-6">
                                    <input id="description" type="text" class="form-control @error('description') is-invalid @enderror"
                                        name="description" value="{{ old('description') }}" autocomplete="description" autofocus>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="images" class="col-md-4 col-form-label text-md-right">Image Files</label>
                                <div class="col-md-6">
                                    <input id="images" type="file" class="form-control-file @error('images') is-invalid @enderror"
                                        name="images[]" value="{{ old('images') }}" autocomplete="images" autofocus multiple required>
                                    @error('images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Edit 3D Asset</h1>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('threeD.update', $model3D->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group row">
                                <label for="package_name" class="col-md-4 col-form-label text-md-right">Asset Name</label>
                                <div class="col-md-6">
                                    <input id="package_name" type="text" class="form-control @error('package_name') is-invalid @enderror"
                                        name="package_name" value="{{ $model3D->threeD_name }}" autocomplete="package_name" autofocus>
                                    @error('package_name')
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
                                        name="description" value="{{ $model3D->description }}" autocomplete="description" autofocus>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="categories" class="col-md-4 col-form-label text-md-right">Categories</label>
                                <div class="col-md-6">
                                    @foreach($categories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}"
                                                id="category{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category{{ $category->id }}">
                                                {{ $category->cat_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                    <a href="{{ route('threeD.show', ['id' => $model3D->id]) }}" class="btn btn-primary">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

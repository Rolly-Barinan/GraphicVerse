@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="text-white">Three Dimensional Objetcs</h2>


        <form action="/three-dim" enctype="multipart/form-data" method="post">
            @csrf
            <div class="row">
                <div class="col-8 offset-2">

                    <div class="row mb-3">
                        <label for="asset_name" class="col-md-4 col-form-label text-md-end text-white">Title </label>

                        <div class="col-md-6">
                            <input id="asset_name" type="text"
                                class="form-control @error('asset_name') is-invalid @enderror" name="asset_name"
                                value="{{ old('asset_name') }}" autocomplete="asset_name" autofocus>

                            @error('asset_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-white">Description </label>
                 
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

                    <div class="row mb-3">
                        <label for="category" class="col-md-4 col-form-label text-md-end text-white">category </label>

                        <div class="col-md-6">
                            <input id="category" type="text"
                                class="form-control @error('category') is-invalid @enderror" name="category"
                                value="{{ old('category') }}" autocomplete="category"
                                placeholder="ex.. trees, flower rocks">

                            @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>


                <div class="col-8 offset-2">
                    <div class="row mb-3">
                        <label for="asset" class="col-md-4 col-form-label text-md-end  text-white">Assets files</label>

                        <div class="col-md-6">
                            <input id="asset" type="file"
                                class="form-control-file @error('asset') is-invalid @enderror" name="asset"
                                value="{{ old('asset') }}" autocomplete="asset" autofocus>

                            @error('asset')
                                <strong>{{ $message }}</strong>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-2 offset-6">
                    <div class="row pt-2">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    </div>

    </div>
@endsection

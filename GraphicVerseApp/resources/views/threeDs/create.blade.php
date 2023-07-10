@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h2 class="text-white">3d assets</h2>
            <form action="/p" enctype="multipart/form-data" method="post">
                @csrf
                <div class="col-8 offset-2">

                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-white">name </label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" autocomplete="name" autofocus>

                            @error('name')
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

@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>two Dimensional Objects</h2>
                </div>
                
                <div class="card-body">
                    <form action="/two-dim" enctype="multipart/form-data" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="asset_name" class="form-label">Title</label>
                            <input id="asset_name" type="text" class="form-control @error('asset_name') is-invalid @enderror" name="asset_name"
                                value="{{ old('asset_name') }}" autocomplete="asset_name" autofocus>
                            @error('asset_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description"
                                value="{{ old('description') }}" autocomplete="description" autofocus>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        {{-- <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input id="category" type="text" class="form-control @error('category') is-invalid @enderror" name="category"
                                value="{{ old('category') }}" autocomplete="category" placeholder="e.g., trees, flowers, rocks">
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div> --}}

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select id="category" class="form-select @error('category') is-invalid @enderror" name="category" autocomplete="category">
                                <option value="" disabled selected>Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        
                        <div class="mb-3">
                            <label for="asset" class="form-label">Asset files</label>
                            <input id="asset" type="file" class="form-control-file @error('asset') is-invalid @enderror" name="asset"
                                value="{{ old('asset') }}" autocomplete="asset" autofocus>
                            @error('asset')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 col-6 mx-auto">
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

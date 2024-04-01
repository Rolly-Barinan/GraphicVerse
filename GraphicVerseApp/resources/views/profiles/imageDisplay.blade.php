<div class="container">
    <h1>Image Assets</h1>

    <div class="row">
        @foreach ($images as $image)
            <div class="col-md-4 mb-4">
                <a href="{{ route('image.show', ['id' => $image->id]) }}">
                    <div class="card">
                        <img src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top" alt="{{ $image->watermarkedImage }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $image->ImageName }}</h5>
                            <p class="card-text">{{ $image->ImageDescription }}</p>
                            <p class="card-text">Price: ${{ $image->Price }}</p>
                            <!-- Add more details as needed -->
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
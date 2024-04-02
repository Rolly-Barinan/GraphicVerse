@if ($images->isEmpty())
    <tr>
        <td colspan="7" class="text-center">No images found</td>
    </tr>
@else
    @foreach ($images as $image)
        <tr>
            <td>{{ $image->id }}</td>
            <td>{{ $image->userID }}</td>
            <td>{{ $image->ImageName }}</td>
            <td>{{ $image->assetType->asset_type }}</td>
            <td>{{ $image->created_at->format('Y-m-d') }}</td>
            <td>{{ $image->updated_at->format('Y-m-d') }}</td>
            <td class="text-center">
                <a href="{{ route('admin.imageDetails', $image->id) }}" class="btn btn-success">
                    View Details
                </a>                                        
            </td>  
        </tr>
    @endforeach
@endif

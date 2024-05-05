@if ($packages->isEmpty())
    <tr>
        <td colspan="7" class="text-center">No packages found</td>
    </tr>
@else
    @foreach ($packages as $package)
    <tr>
        <td>{{ $package->id }}</td>
        <td>{{ $package->UserID }}</td>
        <td>{{ $package->PackageName }}</td>
        <td>{{ $package->assetType->asset_type }}</td>
        <td>{{ $package->created_at->format('Y-m-d') }}</td>
        <td>{{ $package->updated_at->format('Y-m-d') }}</td>
        <td class="text-center">
            <a href="{{ route('admin.packageDetails', $package->id) }}" class="btn btn-success">
                View Details
            </a>                                        
        </td>  
    </tr>
    @endforeach
@endif

@if ($users->isEmpty())
    <tr>
        <td colspan="7" class="text-center">No users found</td>
    </tr>
@else
    @foreach ($users as $user)
    <tr>
        <td>{{ $user->id }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->created_at->format('Y-m-d') }}</td>
        <td class="text-center">
            <a href="{{ route('admin.userDetails', $user->id) }}" class="btn btn-success">
                View Details
            </a>                                        
        </td>  
    </tr>
    @endforeach
@endif

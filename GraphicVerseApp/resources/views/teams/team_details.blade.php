@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-3">
                {{-- Team Avatar and Name --}}
                <div class="card" style="background-color: #222344;">
                    <div class="card-body">
                        <a href="{{ route('teams.index') }}" class="btn btn-secondary"> < All Teams</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 32px; background-color: {{ $team->color }};">
                                @php
                                    $words = explode(" ", $team->name); // Split the team name into an array of words
                            
                                    if (count($words) === 1) {
                                        echo strtoupper(substr($team->name, 0, 3)); // Use the first three letters for single-word team names
                                    } else {
                                        foreach ($words as $word) {
                                            echo strtoupper(substr($word, 0, 1)); // Output the first letter of each word for multi-word team names
                                        }
                                    }
                                @endphp
                            </div>
                        </div>
                        <h5 class="card-title text-center mt-3" style="color:white">{{ $team->name }}</h5>
                    </div>
                </div>

                {{-- Team Management --}}
                <div class="card mt-3" style="background-color: #222344;">
                    <div class="card-header bg-info">
                        <h5 class="card-title">Team Management</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3" style="color:white"><strong>Options:</strong></p>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">Add Member/s</a>
                            <a href="#" class="list-group-item list-group-item-action">Add File/s</a>
                            <form method="POST" action="{{ route('teams.destroy', $team->id) }}" onsubmit="return confirm('Are you sure you want to delete this team?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger mt-3">Delete Team</button>
                            </form>
                        </div>
                    </div>
                </div>                
            </div>
            
            <div class="col-md-6">
                {{-- Chat Box --}}
                <div class="card chatbox-card" style="height: 80vh; background-color: #222344;">
                    <div class="card-header bg-info">
                        <h5 class="card-title">Chat</h5>
                    </div>
                    <div class="card-body chatbox">
                        <!-- Chat messages content here -->
                    </div>
                    <div class="card-footer">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Type a message...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3" style="display: flex; flex-direction: column; height: 80vh;">
                {{-- Members --}}
                <div class="card mb-3" style="flex: 1; background-color: #222344;">
                    <div class="card-header bg-info">
                        <h5 class="card-title">Members</h5>
                    </div>
                    <div class="card-body">
                        {{-- Members content here --}}
                    </div>
                </div>
                
                {{-- Files --}}
                <div class="card" style="flex: 1; background-color: #222344;">
                    <div class="card-header bg-info">
                        <h5 class="card-title">Files</h5>
                    </div>
                    <div class="card-body">
                        {{-- Files content here --}}
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection

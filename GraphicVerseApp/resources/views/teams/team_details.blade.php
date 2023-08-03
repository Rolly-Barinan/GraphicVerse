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
                            <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addMemberModal">Add Member/s</a>
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
                        <div class="notification" style="text-align: center; font-style: italic; color: #ccc;">
                            Chat feature is currently under development and not yet available.
                        </div>
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
                <div class="card mb-3" style="flex: 1; background-color: #222344; overflow-y: auto;">
                    <div class="card-header bg-info">
                        <h5 class="card-title">Members</h5>
                    </div>
                    <div class="card-body" style="overflow-y: auto;">
                        {{-- Members content here --}}
                        <!-- Example content -->
                        <ul style="list-style-type: none; padding: 0; color:white">
                            @foreach ($team->users as $member)
                            <li>{{ $member->name }}</li>
                        @endforeach
                        </ul>
                    </div>
                </div>
                
                {{-- Files --}}
                <div class="card" style="flex: 1; background-color: #222344; overflow-y: auto;">
                    <div class="card-header bg-info">
                        <h5 class="card-title">Files</h5>
                    </div>
                    <div class="card-body" style="overflow-y: auto;">
                        {{-- Files content here --}}
                        <!-- Example content -->
                        <ul style="list-style-type: none; padding: 0; color:white">
                            <li>File 1</li>
                            <li>File 2</li>
                            <li>File 3</li>
                            <li>File 4</li>
                            <li>File 5</li>
                            <li>File 6</li>
                            <li>File 7</li>
                            <li>File 8</li>
                            <li>File 9</li>
                            <li>File 10</li>
                            <li>File 11</li>
                            <li>File 12</li>
                            <li>File 13</li>
                            <li>File 14</li>
                            <!-- Add more files as needed -->
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Add Member Modal -->
            <div class="modal fade" id="addMemberModal" tabindex="-1" role="dialog" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addMemberModalLabel">Add Member to {{ $team->name }}</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form method="POST" action="{{ route('teams.addMembers', $team->id) }}">
                            @csrf
                            <div class="modal-body">
                                <p>Use Team Code: <strong>{{ $team->code }}</strong></p>
                                <p><strong>OR</strong></p>
                                <div class="form-group">
                                    <label for="email">Use Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Member</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

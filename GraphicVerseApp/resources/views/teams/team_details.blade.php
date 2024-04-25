@extends('layouts.app')

@section('content')
    <div class="container mt-4">
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

                {{-- Members --}}
                <div class="card mt-3" style="background-color: #222344; overflow-y: auto;">
                    <div class="card-header" style="background-color: #8B008B;">
                        <h5 class="card-title">Members</h5>
                    </div>
                    <div class="card-body" style="height: 46vh; overflow-y: auto;">
                        {{-- Members content here --}}
                        <!-- Example content -->
                        <ul class="member-list">
                            @foreach ($team->users as $member)
                                <li class="member-item">
                                    <a class="member-link" href="{{ route('profile.show', ['user' => $member->id]) }}">
                                        <span class="name">{{ $member->username }}</span>
                                        <span class="role">{{ $member->pivot->role }}</span>
                                    </a>                                                                      
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-{{ $userIsTeamMember ? '6' : '9' }}">
                {{-- Files --}}
                <div class="card" style="height: 80vh; background-color: #222344;">
                    <div class="card-header" style="background-color: #8B008B;">
                        <h5 class="card-title">Files</h5>
                    </div>
                    <div class="card-body" style="overflow-y: auto;">
                        {{-- Files content here --}}
                        <div class="row">
                            @php
                                $hasSearchResults = false;
                            @endphp
                            @foreach ($packages as $result)
                                @php
                                    $hasSearchResults = true;
                                @endphp
                                <div class="col-md-3 mb-3 preview_card">
                                    <div class="card">
                                        @if ($result->asset_type_id === 2) <!-- Assuming you have a field named 'asset_type' in your Package model -->
                                            <a href="{{ route('threeDim.show', ['id' => $result->id]) }}"> <!-- Use threeDim.show route -->
                                        @else
                                            <a href="{{ route('twoDim.show', ['id' => $result->id]) }}"> <!-- Use twoDim.show route -->
                                        @endif
                                            <img src="{{ Storage::url($result->Location) }}" class="card-img-top" alt="{{ $result->PackageName }}">
                                            <div class="card-body">
                                                <h4 class="card-title">{{ $result->PackageName }}</h4>
                                                <p class="card-text">{{ $result->user->username }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            @if (!$hasSearchResults)
                                <div class="col-md-12">
                                    <div class="alert alert-info" role="alert">
                                        No assets uploaded.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Chat Box --}}
            @if ($userIsTeamMember)
                <div class="col-md-3">
                    <div class="card chatbox-card" style="background-color: #222344;">
                        <div class="card-header" style="background-color: #8B008B;">
                            <h5 class="card-title">Chat</h5>
                        </div>
                        <!-- Chat messages content here -->
                        <div class="card-body chatbox" style="color: white; height: 40vh; overflow-y: auto;">
                            @if($team->messages && $team->messages->count() > 0)
                                @foreach($team->messages as $message)
                                    <div class="message-container @if($message->user && $message->user->id === Auth::id()) text-right @else text-left @endif">
                                        <strong>
                                            @if($message->user)
                                                @if($message->user->id === Auth::id())
                                                    Me
                                                @else
                                                    {{ $message->user->name }}
                                                @endif
                                            @else
                                                Unknown User
                                            @endif
                                        </strong>{{ $message->message }}
                                    </div>
                                @endforeach
                            @else
                                <div>
                                    No messages yet.
                                </div>
                            @endif
                        </div>

                        <div class="card-footer">
                            <form method="POST" action="{{ route('teams.sendMessage', $team->id) }}">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Type a message..." name="message">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Send</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Team Management --}}
                    <div class="card mt-3" style="background-color: #222344;">
                        <div class="card-header" style="background-color: #8B008B;">
                            <h5 class="card-title">Options</h5>
                        </div>
                        <div class="card-body">
                            {{-- Inside your Blade template --}}
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#addMemberModal">Add Member/s</a>
                                <a href="#" class="list-group-item list-group-item-action">Add File/s</a>
                                @if ($userRole === 'Creator')
                                    <form method="POST" action="{{ route('teams.destroy', $team->id) }}" onsubmit="return confirm('Are you sure you want to delete this team?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mt-3">Delete Team</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('teams.leave', $team->id) }}" onsubmit="return confirm('Are you sure you want to leave this team?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-warning mt-3">Leave Team</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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


    <style>
        .member-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
    
        .member-item {
            margin-bottom: 10px;
        }
    
        .member-link {
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 8px 12px;
            border-radius: 5px;
            display: flex;
            width: 100%; /* Fill the entire width */
            transition: background-color 0.3s ease;
            justify-content: space-between;
            align-items: center;
        }
        
        .name {
            order: 1; /* Display name on the left */
        }

        .role {
            order: 2; /* Display role on the right */
        }

        .member-link:hover {
            background-color: #555;
        }

        .message-container {
            padding: 5px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .input-group {
            height: 100%; /* Set the height of the input group */
        }

        .input-group .form-control {
            height: 100%; /* Set the height of the input field */
            border-top-right-radius: 0; /* Remove border radius from the top right corner */
            border-bottom-right-radius: 0; /* Remove border radius from the bottom right corner */
        }

        .input-group-append .btn {
            height: 95%; /* Set the height of the button */
            border-top-left-radius: 0; /* Remove border radius from the top left corner */
            border-bottom-left-radius: 0; /* Remove border radius from the bottom left corner */
        }

        .text-right {
            background-color: #007bff; /* Blue background for current user's messages */
            text-align: right;
            max-width: 60%; /* Adjust the max-width as needed */
            margin-left: auto; /* Push the message container to the right */
        }

        .text-left {
            background-color: #28a745; /* Green background for other users' messages */
            max-width: 60%; /* Adjust the max-width as needed */
            margin-right: auto; /* Push the message container to the left */
        }

        .message-container strong {
            display: block;
            margin-bottom: 5px;
        }

        /* Adjusted CSS for package cards */
        /* .preview_card {
            width: calc(50% - 10px);
            margin-bottom: 20px;
            padding: 10px;
        } */

        .col-md-6 .preview_card {
            width: calc(50% - 10px); /* 50% width with 10px margin between cards */
            margin-bottom: 20px;
            padding: 10px;
        }

        .col-md-9 .preview_card {
            width: calc(33.3333% - 5px); /* 33.3333% width with 5px margin between cards */
            margin-bottom: 20px;
            padding: 10px;
        }

        .preview_card a {
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit color from parent */
        }

        .card img {
            border-radius: 5px;
            width: 100%;
            height: 230px; /* Adjust as needed */
            object-fit: cover; /* This will ensure the image scales to fit the card */
        }

        .card h4 {
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            color: #5F5F79;
            font-size: 20px;
            margin-bottom: 0;
        }

        .card h5 {
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            color: white;
            font-size: 20px;
            margin-bottom: 0;
        }
    </style>
@endsection

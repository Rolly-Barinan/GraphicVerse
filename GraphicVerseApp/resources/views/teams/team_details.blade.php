@extends('layouts.app')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
@section('content')
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<!-- Include jQuery UI CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- jQuery UI (for draggable functionality) -->
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/FBXLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/libs/fflate.min.js"></script>
    <!-- Include jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Include Bootstrap's JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="cover-photo" style="background-image: url('{{ Storage::url($team->cover_picture) }}')"></div>
<div class="container-fluid py-50"style="background-color: #DDDDE4 !important;">
    <div class="container-fluid d-lg-inline p-2">
        <div class="row" >
            <div class="col-6 user-info-1 d-flex flex-row justify-content-start">
                <div class="rounded-circle-container">
                    <img src="{{ Storage::url($team->profile_picture) }}" class="rounded-circle img-fluid" alt="User Profile Image">
                </div>
                <div class="text1">
                    <h1 class="name">{{ $team->name }}</h1>
                </div>
            </div>
            <div class="col-6 d-flex justify-content-end">
                @if ($userRole === 'Creator')
                    <form method="POST" action="{{ route('teams.destroy', $team->id) }}" onsubmit="return confirm('Are you sure you want to delete this team?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="uploadBtn">Delete Team</button>
                    </form>
                    <button type="submit" class="connectBtn" id="chatHead">Chat</button>
                @elseif ($userRole === 'Member')
                    <form method="POST" action="{{ route('teams.leave', $team->id) }}" onsubmit="return confirm('Are you sure you want to leave this team?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="uploadBtn">Leave Team</button>
                    </form>
                    <button type="submit" class="connectBtn" id="chatHead">Chat</button>
                @endif
            </div>
        </div>
        <div class="row" style="margin-top: -100px !important">
            <div class="col-9">
                <div class="scrollable-column packages_column">
                    <div class="row package_row">
                        <h1 class="text-start w-100">TEAM ASSETS</h1>
                    </div>
                    <div class="image-scroll-container overflow-x-hidden">
                        <div class="row">
                            @foreach ($packages as $result)
                                <div class="col-md-3 mb-3 preview_card">
                                    <div class="card">
                                        @if ($result->asset_type_id === 3)
                                            <a href="{{ route('audio.show', ['id' => $result->id]) }}">
                                        @elseif ($result->asset_type_id === 2)
                                            <a href="{{ route('threeDim.show', ['id' => $result->id]) }}">
                                        @else
                                            <a href="{{ route('twoDim.show', ['id' => $result->id]) }}">
                                        @endif
                                            <div class="card-image">
                                                <img src="{{ Storage::url($result->Location) }}" class="card-img-top" alt="{{ $result->PackageName }}">
                                            </div>
                                            <div class="card-body p-1">
                                                <h5 class="card-title">{{ $result->PackageName }}</h5>
                                                <p class="card-text">{{ $result->user->username }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                            @foreach ($images as $image)
                                <div class="col-md-3 mb-3 preview_card">
                                    <div class="card ">
                                        <a href="{{ route('image.show', ['id' => $image->id]) }}">
                                            <img src="{{ Storage::url($image->watermarkedImage) }}" class="card-img-top"
                                                alt="{{ $image->ImageName }}">

                                            <div class="card-body">
                                                <h5 class="card-title">{{ $image->ImageName }}</h5>
                                                <p class="card-text">{{ $image->user->username }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <h1 class="text-start">MEMBERS</h1>
                <ul class="member-list">
                    @foreach ($team->users as $member)
                        <li class="member-item d-flex align-items-center justify-content-between">
                            <a class="member-link d-flex align-items-center" href="{{ route('profile.show', ['user' => $member->id]) }}" style="text-decoration: none;">
                                <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; font-size: 32px; background-color: {{ $team->color }};">
                                    <img src="{{ $member->profile->profileImage() }}" alt="{{ $member->username }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; object-position: center;">
                                </div>
                                <div class="d-flex flex-column align-items-center mt-2 ms-5">
                                    <span class="name" style="font-size: 30px !important">{{ $member->username }}</span>
                                    <span class="role">{{ $member->pivot->role }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                    @if ($userRole === 'Creator' || $userRole === 'Member') 
                        <li class="member-item d-flex align-items-center justify-content-between">
                            <a class="member-link d-flex align-items-center" href="#" data-bs-toggle="modal" data-bs-target="#addMemberModal" style="text-decoration: none;">
                                <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; font-size: 32px; background-color: gray;">
                                    +
                                </div>
                                <div class="d-flex flex-column align-items-center ms-3">
                                    <span class="name" style="font-size: 30px !important">Add Member/s</span>    
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <!-- Chat Modal -->
    <div id="chatModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chat</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Add your chat modal content here -->
                    <div class="chat-messages">
                        @if($team->messages && $team->messages->count() > 0)
                            @foreach($team->messages as $message)
                                <div class="@if($message->user && $message->user->id === Auth::id()) text-right @else text-left @endif">
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
                    <div class="chat-input">
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Initialize draggable chat head
        $( function() {
            $( "#chatHead" ).draggable();
        });

        // Show modal when chat head is clicked
        $(document).on("click", "#chatHead", function() {
            $("#chatModal").modal("show");
        });

        // Hide modal when close button is clicked
        $(document).on("click", ".close", function() {
            $("#chatModal").modal("hide");
        });
    </script>
    <style>
    .chat-head {
        height: 50px;
        width: 50px;
        z-index: 9999;
        cursor: pointer;
        color: #fff; /* Set text color */
        padding: 10px 20px; /* Add padding */
        border-radius: 50px; /* Add border-radius for rounded corners */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add box-shadow for depth */
    }

    .chat-head:hover {
        background-color: #0056b3; /* Change background color on hover */
    }

    /* Adjust the appearance of the chat head when it's active (clicked) */
    .chat-head:active {
        background-color: #004080; /* Darker background color when clicked */
        box-shadow: none; /* Remove box-shadow when clicked */
    }
    </style>
@endsection


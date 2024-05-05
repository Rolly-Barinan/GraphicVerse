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
                            @if ($packages->count() > 0 || $artworks->count() > 0)
                                @foreach ($packages as $package)
                                    <div class="col-md-3 mb-3 preview_card">
                                        <div class="card">
                                            @if ($package->asset_type_id === 3)
                                                <a href="{{ route('audio.show', ['id' => $package->id]) }}">
                                            @elseif ($package->asset_type_id === 2)
                                                <a href="{{ route('threeDim.show', ['id' => $package->id]) }}">
                                            @else
                                                <a href="{{ route('twoDim.show', ['id' => $package->id]) }}">
                                            @endif
                                                <img src="{{ Storage::url($package->Location) }}" class="card-img-top" alt="{{ $package->PackageName }}">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="card-title">{{ $package->PackageName }}</h5>
                                                        <p class="card-text">{{ $package->user->username }}</p>
                                                    </div>
                                                    <div>
                                                        <!-- Form for liking an image -->
                                                        <form action="{{ route('package.like', ['id' => $package->id]) }}" method="POST" style="text-decoration: none;">
                                                            @csrf
                                                            <button type="submit" class="btn">
                                                                <!-- Check if the user is authenticated and if the image is liked by the user -->
                                                                @if(auth()->check() && $package->likes()->where('user_id', auth()->user()->id)->exists())
                                                                    <i class="fas fa-heart" style="color: #e52424;"></i><!-- Show filled heart icon if the image is liked -->                    
                                                                @else 
                                                                    <i class="far fa-heart" style="color: #e52424;"></i> <!-- Show heart outline icon if the image is not liked -->
                                                                @endif
                                                                <!-- Display the number of likes -->
                                                                <span>{{ $package->likes }}</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($artworks as $artwork)
                                    <div class="col-md-3 mb-3 preview_card">
                                        <div class="card">
                                            <a href="{{ route('image.show', ['id' => $artwork->id]) }}">
                                                <img src="{{ Storage::url($artwork->watermarkedImage) }}" class="card-img-top" alt="{{ $artwork->ImageName }}">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="card-title">{{ $artwork->ImageName }}</h5>
                                                        <p class="card-text">{{ $artwork->user->username }}</p>
                                                    </div>
                                                    <div>
                                                        <!-- Form for liking an image -->
                                                        <form action="{{ route('image.like', ['id' => $artwork->id]) }}" method="POST" style="text-decoration: none;">
                                                            @csrf
                                                            <button type="submit" class="btn">
                                                                <!-- Check if the user is authenticated and if the image is liked by the user -->
                                                                @if(auth()->check() && $artwork->likes()->where('user_id', auth()->user()->id)->exists())
                                                                    <i class="fas fa-heart" style="color: #e52424;"></i><!-- Show filled heart icon if the image is liked -->                    
                                                                @else 
                                                                    <i class="far fa-heart" style="color: #e52424;"></i> <!-- Show heart outline icon if the image is not liked -->
                                                                @endif
                                                                <!-- Display the number of likes -->
                                                                <span>{{ $artwork->likes }}</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <h3> No assets found for this team.</h3>
                            @endif
                            
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
                    <button type="button" class="close" data-dismiss="modal">&minus;</button>
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
                                                {{ $message->user->username }}
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
        <div class="modal-dialog" role="document" >
            <div class="modal-content" style="height: 450px !important;">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">ADD MEMBER TO {{ $team->name }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('teams.addMembers', $team->id) }}">
                    @csrf
                    <div class="modal-body" style="height: 600px !important;">
                        <p class="card-text" style="font-family: 'Roboto'; color: #5F5F79;font-size: 30px;">Use Team Code: <strong>{{ $team->code }}</strong></p>
                        <p class="card-text" style="font-family: 'Roboto'; color: #5F5F79;font-size: 40px;"><strong>OR</strong></p>
                        <div class="form-group">
                            <label for="email" class="card-text" style="font-family: 'Roboto'; color: #5F5F79;font-size: 30px;">Use Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="uploadBtn" style="position: absolute;right: 10px;margin-top:10px;">Add Member</button>
                        </div>
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
        $(function() {
            $("#chatHead").draggable();
        });

        // Show modal when chat head is clicked
        $(document).on("click", "#chatHead", function() {
            $("#chatModal").modal("show");
        });

        // Submit form asynchronously when send button is clicked
        $(document).on("submit", "#chatModal form", function(event) {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Get the form data
            var formData = $(this).serialize();

            // Send the form data asynchronously via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: $(this).attr('method'),
                data: formData,
                success: function(response) {
                    // Append the new message to the chat messages container
                    $(".chat-messages").append('<div class="text-right"><strong>Me</strong>: ' + response.message + '</div>');

                    // Clear the message input field after successful submission
                    $("#chatModal input[name='message']").val('');
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(xhr.responseText);
                }
            });
        });
    </script>
    <style>
        /* Chat Modal Styles */
        .modal-dialog {
            margin: auto;
            min-width: 1000px !important;
            padding: 20px !important;
        }
        .modal-content {
            background-color: #f4f4f4;
            min-width: 1000px !important;
        }

        .modal-header {
            background-color: #5F5F79;
            font-family: oswald, sans-serif;
            font-size: 1000px !important;
            color: #fff;
            border-bottom: none;
            align-items: center;
            
        }

        .modal-title {
            font-size: 40px;
        }

        .modal-body {
            padding: 20px;
            min-height: 800px;
            min-width: 1000px;
        }

        .chat-messages {
            min-height: 800px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .chat-messages div {
            margin-bottom: 10px;
        }

        .chat-input {
            margin-top: 20px;
        }

        .chat-input input[type="text"] {
            width: calc(100% - 90px);
            padding: 10px;
            border-radius: 5px;
            height: 40px;
            border: 1px solid #ccc;
            outline: none;
        }

        .chat-input button {
            height: 40px;
            width: 80px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #5F5F79;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .chat-input button:hover {
            background-color: #4F4F69;
        }

        .chat-input button:focus {
            outline: none;
            background-color: #3f3f53;
        }

        /* Close button */
        .close {
            color: #fff;
            opacity: 1;
            font-size: 40px !important;
            margin-right: 15px !important;
            margin-top: auto !important;
            margin-bottom: auto !important;
        }

        .close:hover {
            color: #ccc;
            text-decoration: none;
            opacity: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .modal-dialog {
                margin: auto;
                    max-width: none;
            }

            .modal-content {
                border-radius: 0;
            }
        }
    </style>

@endsection


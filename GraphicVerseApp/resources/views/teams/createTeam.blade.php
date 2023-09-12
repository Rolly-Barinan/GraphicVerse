@extends('layouts.app')
<style>
    body {
        font-family: 'Nunito', sans-serif;
        background-image: url('https://cdn.discordapp.com/attachments/1121006331323760680/1123571308496691210/Copy_of_GraphicVerse_Capstone_Hearing.png');
        background-size: cover;
        background-repeat: no-repeat;
    }

    .navbar-nav .nav-item {
        display: flex;
    }
</style>
@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" style="background-color: #222344;">
                    <div class="card-header d-flex justify-content-between">
                        <h2 style="color: white">Teams</h2>
                        <a href="{{ route('teams.index') }}" class="btn btn-primary">Back</a>
                    </div>

                    <div class="card-body d-flex justify-content-between">
                        {{-- Create a new team form --}}
                        <div class="create-team-box p-4 bg-light rounded">
                            <h2>Create a team</h2>
                            <label>Bring everyone together and get to work!</label>
                            <form method="POST" action="{{ route('teams.store') }}">
                                @csrf
                                <div class="form-group mt-3">
                                    <label for="team_name">Team Name</label>
                                    <input type="text" name="team_name" id="team_name" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Create Team</button>
                            </form>
                        </div>

                        <div style="width: 20px;"></div> {{-- Add some space between the boxes --}}

                        {{-- Join an existing team form --}}
                        <div class="join-team-box p-4 bg-light rounded">
                            <h2>Join an existing team</h2>
                            <form method="POST" action="{{ route('teams.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="team_code">Team Code</label>
                                    <input type="text" name="team_code" id="team_code" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success mt-3">Join Team</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

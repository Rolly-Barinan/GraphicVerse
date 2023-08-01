@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Teams Management</h1>
                    </div>

                    <div class="card-body">
                        {{-- Display success messages --}}
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        {{-- Display error messages --}}
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        {{-- Create a new team form --}}
                        <h2>Create a new team</h2>
                        <form method="POST" action="{{ route('teams.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="team_name">Team Name</label>
                                <input type="text" name="team_name" id="team_name" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Create Team</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- List of teams --}}
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Teams List</h2>
                    </div>

                    <div class="card-body">
                        @if(count($teams) > 0)
                            <ul class="list-group">
                                @foreach($teams as $team)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $team->name }}
                                        <form method="POST" action="{{ route('teams.destroy', $team->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this team?')">Delete</button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No teams found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

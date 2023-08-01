@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        {{-- List of teams --}}
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Teams</h2>
                        <a href="{{ route('teams.create') }}" class="btn btn-primary">Join or Create Team</a>
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
                        
                        @if(count($teams) > 0)
                            <div class="row">
                                @foreach($teams as $team)
                                    <div class="col-md-6 mb-4">
                                        <div class="card text-center">
                                            <div class="d-flex align-items-center justify-content-center mt-2">
                                                <div class="avatar text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 32px; background-color: {{ $team->color }};">
                                                    @php
                                                        $words = explode(" ", $team->name); // Split the team name into an array of words
                                                
                                                        if (count($words) === 1) {
                                                            echo strtoupper(substr($team->name, 0, 2)); // Use the first two letters for single-word team names
                                                        } else {
                                                            foreach ($words as $word) {
                                                                echo strtoupper(substr($word, 0, 1)); // Output the first letter of each word for multi-word team names
                                                            }
                                                        }
                                                    @endphp
                                                </div>                                                
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $team->name }}</h5>
                                                <form method="POST" action="{{ route('teams.destroy', $team->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this team?')">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>No teams found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

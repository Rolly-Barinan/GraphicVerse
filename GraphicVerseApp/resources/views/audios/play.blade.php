@extends('layouts.app')
@section('content')
    <a href="{{ route('audios.play', $audio->id) }}">Play Audio</a>

    <audio controls>
        <source src="{{ asset($audio->file_path) }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
@endsection
    
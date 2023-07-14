<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudiosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /** 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('audios.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category' => 'required|in:music,sound,ambient',
            'audio' => 'required|mimes:audio/mpeg,mpga,mp3,wav',
        ]);


        $audio = new Audio();
        $audio->user_id = Auth::id();
        $audio->name = $request->name;
        $audio->category = $request->category;

        $audioPath = $request->file('audio')->store('audio', 'public');
        $audio->file_path = $audioPath;

        $audio->save();
        return redirect()->route('audios.index')->with('success', 'Audio uploaded successfully!');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $audio = Audio::findOrFail($id);
        return view('audios.show', compact('audio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $requ est
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function play($id)
    {
        $audio = Audio::findOrFail($id);
        $filePath = storage_path('app/' . $audio->file_path);

        return response()->file($filePath, [
            'Content-Type' => 'audio/mpeg',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

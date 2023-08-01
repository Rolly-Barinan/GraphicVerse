<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();

        return view('Teams.indexTeam', compact('teams'));
    }

    public function create()
    {
        return view('Teams.createTeam');
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,name',
        ]);

        $randomColor = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

        Team::create([
            'name' => $request->input('team_name'),
            'color' => $randomColor,
        ]);

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
        if (Auth::check()) {
            $request->validate([
                'team_name' => 'required|string|max:255|unique:teams,name',
            ]);

            $randomColor = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);

            // Generate a 6-letter random code
            $randomCode = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

            $team = Team::create([
                'name' => $request->input('team_name'),
                'color' => $randomColor,
                'code' => $randomCode, // Add the random code to the team
            ]);

            // Add the logged-in user as a member of the newly created team
            $user = Auth::user(); // Get the logged-in user
            $team->users()->attach($user);

            return redirect()->route('teams.index')->with('success', 'Team created successfully.');
        }
        // Handle the case when the user is not authenticated
        return redirect()->route('login')->with('error', 'Please log in to create a team.');
    }

    public function details($teamName)
    {
        $team = Team::where('name', $teamName)->firstOrFail();

        return view('Teams.team_details', compact('team'));
    }
    
    public function storeMembers(Request $request, Team $team)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            $team->users()->attach($user);
            return redirect()->route('teams.details', $team->name)->with('success', 'Member added successfully.');
        }

        return redirect()->route('teams.details', $team->name)->with('error', 'User not found.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}

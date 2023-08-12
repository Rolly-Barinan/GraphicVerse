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
        $user = Auth::user();
        $teams = $user->teams; // Assuming you have a 'teams' relationship on the User model
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

            // Add the logged-in user as a member of the newly created team with role "Creator"
            $user = Auth::user(); // Get the logged-in user
            $team->users()->attach($user, ['role' => 'Creator']); // Attach the role "Creator" here

            return redirect()->route('teams.index')->with('success', 'Team created successfully.');
        }
        // Handle the case when the user is not authenticated
        return redirect()->route('login')->with('error', 'Please log in to create a team.');
    }


    public function details($teamName)
    {
        $team = Team::where('name', $teamName)->firstOrFail();
        $userRole = $this->getUserRoleForTeam($team); // Get the user's role
        return view('Teams.team_details', compact('team', 'userRole'));
    }
    
    public function storeMembers(Request $request, Team $team)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if ($user) {
            $role = ($team->users->contains(Auth::user())) ? 'Member' : 'Creator'; // Check if logged-in user is already a member
        $team->users()->attach($user, ['role' => $role]); // Attach the appropriate role

        return redirect()->route('teams.details', $team->name)->with('success', 'Member added successfully.');
        }

        return redirect()->route('teams.details', $team->name)->with('error', 'User not found.');
    }

    public function getUserRoleForTeam(Team $team)
    {
        $user = Auth::user();
        return $team->users->contains($user) ? $team->users->find($user)->pivot->role : null;
    }

    public function leaveTeam(Team $team)
    {
        $user = Auth::user();
        $team->users()->detach($user);

        return redirect()->route('teams.index')->with('success', 'Left the team successfully.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }
}

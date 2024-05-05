<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ChatMessage;

class TeamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teams = $user->teams;
        return view('Teams.indexTeam', compact('teams'));
    }

    public function create()
    {
        return view('Teams.createTeam');
    }

    public function store(Request $request)
    {
        \Log::info($request->all());

        $request->validate([
            'team_name' => 'required|string|max:255|unique:teams,name',
            'profile_picture' => 'required| image|mimes:jpeg,png,jpg|max:5120', // Max 5 MB
            'cover_picture' => 'required| image|mimes:jpeg,png,jpg|max:5120', // Max 5 MB
        ]);

        // Handle profile picture upload
        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            if ($request->file('profile_picture')->isValid()) {
                $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            }
        }

        // Handle cover picture upload
        $coverPicturePath = null;
        if ($request->hasFile('cover_picture')) {
            if ($request->file('cover_picture')->isValid()) {
                $coverPicturePath = $request->file('cover_picture')->store('cover_pictures', 'public');
            }
        }

        $randomColor = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        $randomCode = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

        \Log::info('Cover Picture Path: ' . $coverPicturePath);

        $team = Team::create([
            'name' => $request->input('team_name'),
            'color' => $randomColor,
            'code' => $randomCode,
            'profile_picture' => $profilePicturePath,
            'cover_picture' => $coverPicturePath, // Save the cover picture path
        ]);

        $user = Auth::user();
        $team->users()->attach($user, ['role' => 'Creator']);

        return redirect()->route('team.details', ['teamName' => $request->input('team_name')])
            ->with('success', 'Team created successfully.');
    }



    public function details($teamName)
    {
        $team = Team::where('name', $teamName)->firstOrFail();
        $user = Auth::user();
        $userRole = $this->getUserRoleForTeam($team); // Get the user's role

        // Check if the logged-in user is a member of the team
        $userIsTeamMember = $team->users->contains($user);

        // Fetch the packages and assets associated with the team's users
        $packages = $team->packages;
        $artworks = $team->images;
        // $images = [];
        // foreach ($team->users as $user) {
        //     $userImages = $user->images;
        //     foreach ($userImages as $image) {
        //         $images[] = $image;
        //     }
        // }

        return view('Teams.team_details', compact('team', 'userRole', 'packages', 'artworks', 'userIsTeamMember'));
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

    public function sendMessage(Request $request, Team $team)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        $message = new ChatMessage();
        $message->team_id = $team->id;
        $message->user_id = $user->id;
        $message->message = $request->input('message');
        $message->save();

        // Return JSON response with the message
        return response()->json(['message' => $request->input('message')]);
    }

    public function fetchMessages(Team $team)
    {
        $messages = $team->messages()->with('user')->latest()->get();

        return $messages;
    }

    public function leaveTeam(Team $team)
    {
        $user = Auth::user();
        $team->users()->detach($user);

        return redirect()->route('teams.index')->with('success', 'Left the team successfully.');
    }

    public function destroy(Team $team)
    {
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to register route if user is not authenticated
        }
        $team->delete();
        $userId = Auth::id();
        return redirect()->route('profile.show', ['user' => $userId])->with('success', 'Team deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
class AppUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::where('is_deleted', false)->get();
        $user = auth()->user();
        $sports = Sport::all();

        if ($user->role != 'admin' ) {

            return redirect()->route('teams.index')->with('error', 'You are not allowed to access this!');

        }



        return view('admin.users.index', compact('users', 'sports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password1' => 'required|min:8',
            'password2' => 'required|same:password1',
            'role' => 'required|string|in:admin,encoder,tournament_director',
        ];

        // Add conditional validation for sport_id
        if ($request->role === 'tournament_director') {
            $validationRules['sport_id'] = 'required|exists:sports,id';
        }

        $request->validate($validationRules);

        // Create new user
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password1),
        ];


        if ($request->role === 'tournament_director') {
            $userData['sport_id'] = $request->sport_id;
        }

        $user = User::create($userData);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Validate request data
    $validationRules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|string|in:admin,encoder,tournament_director',
    ];

    // Add conditional validation for sport_id
    if ($request->role === 'tournament_director') {
        $validationRules['sport_id'] = 'required|exists:sports,id';
    }

    $request->validate($validationRules);

    // Find user by ID
    $user = User::findOrFail($id);

    // Update user data
    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;

    // Update sport_id if role is tournament_director
    if ($request->role === 'tournament_director') {
        $user->sport_id = $request->sport_id;
    } else {
        $user->sport_id = null; // Clear sport_id if role is not tournament_director
    }

    // Save changes
    $user->save();

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = true;
        $user->save();
        return redirect()->route('users.index')->with('success', 'User updated Deleted.');
    }
}

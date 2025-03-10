<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
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
        
        if ($user->role != 'admin' ) {
            
            return redirect()->route('teams.index')->with('error', 'You are not allowed u paking shiet!');

        }
    


        return view('admin.users.index', compact('users'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password1' => 'required|min:8',
            'password2' => 'required|same:password1',
            'role' => 'required|string|in:admin,encoder,tournament_director',
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password1), 
        ]);

        // Mail::to($request->user())->send(new WelcomeMail($user));
        // Redirect with success message
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string|in:admin,encoder,tournament_director',
        ]);

        // Find user by ID
        $user = User::findOrFail($id);

        // Update user data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

    
        // Save changes
        $user->save();

        // Redirect with success message
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

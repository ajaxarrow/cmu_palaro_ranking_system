<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        $user = Auth::user();
        if ($user && in_array($user->role, ['assistant_tournament_director', 'tournament_director', 'user', 'encoder']) &&
        $user->is_changed && !$user->is_first_time) {
        return redirect()->route('teams.index');
    }
        return view('auth.force-reset-password');
    }

    public function resetPassword(Request $request)
    {
        $user = Auth::user();
        if ($user && in_array($user->role, ['assistant_tournament_director', 'tournament_director', 'user', 'encoder']) &&
            $user->is_changed && !$user->is_first_time) {
            return redirect()->route('teams.index');
        }
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->is_changed = true;
        $user->is_first_time = false;
        $user->save();

        return redirect()->route('teams.index')->with('status', 'Password reset successfully!');
    }
}

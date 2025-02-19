<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\TeamComposition;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::all();
        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'college_ids' => 'required|array',
            'college_ids.*' => 'exists:colleges,id',
        ]);

        // Create the team
        $team = Team::create([
            'name' => $request->name,
            'desc' => $request->desc,
        ]);

        // Loop through college_ids and insert into team_compositions
        foreach ($request->college_ids as $college_id) {
            TeamComposition::create([
                'team_id' => $team->id,
                'college_id' => $college_id,
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $team = Team::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'college_ids' => 'required|array',
            'college_ids.*' => 'exists:colleges,id',
        ]);

        // Update team info
        $team->update([
            'name' => $request->name,
            'desc' => $request->desc,
        ]);

        // Delete old compositions and insert new ones
        TeamComposition::where('team_id', $team->id)->delete();

        foreach ($request->college_ids as $college_id) {
            TeamComposition::create([
                'team_id' => $team->id,
                'college_id' => $college_id,
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index');
    }
}

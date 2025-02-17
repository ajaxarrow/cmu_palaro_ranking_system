<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamComposition;
use App\Models\College;
use App\Models\Team;

class TeamCompositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teamCompositions = TeamComposition::with(['college', 'team'])->get();
        return view('admin.team_compositions.index', compact('teamCompositions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $colleges = College::all();
        $teams = Team::all();
        return view('team_compositions.create', compact('colleges', 'teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'team_id' => 'required|exists:teams,id',
        ]);

        TeamComposition::create($request->all());
        return redirect()->route('team_compositions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamComposition $teamComposition)
    {
        return view('team_compositions.show', compact('teamComposition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamComposition $teamComposition)
    {
        $colleges = College::all();
        $teams = Team::all();
        return view('team_compositions.edit', compact('teamComposition', 'colleges', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamComposition $teamComposition)
    {
        $request->validate([
            'college_id' => 'required|exists:colleges,id',
            'team_id' => 'required|exists:teams,id',
        ]);

        $teamComposition->update($request->all());
        return redirect()->route('team_compositions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamComposition $teamComposition)
    {
        $teamComposition->delete();
        return redirect()->route('team_compositions.index');
    }
}

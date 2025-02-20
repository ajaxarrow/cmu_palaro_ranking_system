<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedalRecord;
use App\Models\Team;
use App\Models\EventCategory;
use Carbon\Carbon;

class MedalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medalRecords = MedalRecord::with(['team', 'event'])->get();
        $teams = Team::all();
        $events = EventCategory::all();
        return view('admin.medal_records.index', compact('medalRecords', 'teams', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::all();
        $events = EventCategory::all();
        $year = Carbon::now()->year;
        return view('admin.medal_records.create', compact('teams', 'events', 'year'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'event_category_id' => 'required|exists:event_categories,id',
            'gold_tally' => 'required|integer',
            'silver_tally' => 'required|integer',
            'bronze_tally' => 'required|integer',
            'number_of_participant' => 'required|numeric',
            'year' => 'required|integer'
        ]);

        $validatedData['entry_by'] = auth()->id();
        $validatedData['posted_by'] = auth()->id();

        MedalRecord::create($validatedData);
        return redirect()->route('medal_records.index')->with('success', 'Medal record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedalRecord $medalRecord)
    {
        return view('medal_records.show', compact('medalRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedalRecord $medalRecord)
    {
        $teams = Team::all();
        $events = EventCategory::all();
        return view('admin.medal_records.edit', compact('medalRecord', 'teams', 'events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'event_category_id' => 'required|exists:event_categories,id',
            'gold_tally' => 'required|integer',
            'silver_tally' => 'required|integer',
            'bronze_tally' => 'required|integer',
            'number_of_participant' => 'required|numeric',
            'year' =>  'required|numeric'
        ]);


        $medalRecord = MedalRecord::findOrFail($id);
        $medalRecord->update($validatedData);
        return redirect()->route('medal_records.index')->with('success', 'Medal record updated successfully.');
    }

    /**

     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {

        $medalRecord = MedalRecord::findOrFail($id);
    
        $medalRecord->delete();
        return redirect()->route('medal_records.index')->with('success', 'Medal record deleted successfully.');
    }
}

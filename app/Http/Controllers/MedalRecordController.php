<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedalRecord;
use App\Models\Team;
use App\Models\EventCategory;
use App\Models\Sport;
use Carbon\Carbon;

class MedalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function create()
     {
         $teams = Team::all();
         $events = EventCategory::with('medalWeightCategory')->get();
         $year = Carbon::now()->year;
         return view('admin.medal_records.create', compact('teams', 'events', 'year'));
     }


    public function index()
    {
        $user = auth()->user();

        // Get all sports with their event categories
        $sports = Sport::with(['eventCategories' => function($query) {
            $query->orderBy('name');
        }])->orderBy('name')->get();

        // Get all teams
        $teams = Team::orderBy('name')->get();

        // Get all event categories for the form
        $events = EventCategory::orderBy('name')->get();

        // Get all medal records
        $medalRecords = MedalRecord::with(['team', 'event'])->get();

        return view('admin.medal_records.index', compact(
            'user',
            'sports',
            'teams',
            'events',
            'medalRecords'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'event_category_id' => 'required|exists:event_categories,id',
            'champion_team_id' => 'required|exists:teams,id',
            'first_runner_up_team_id' => 'required|exists:teams,id',
            'second_runner_up_team_id' => 'required|exists:teams,id',

            'number_of_participant' => 'required|integer|min:1'
        ]);

        // Generate the year in the backend
        $currentYear = Carbon::now()->year;

        // Get event category to get medal count and check if double bronze is allowed
        $eventCategory = EventCategory::findOrFail($request->event_category_id);
        $medalCount = $eventCategory->medal_count;
        $isDoubleBronze = $eventCategory->is_double_bronze;

        // Check for duplicate team selections
        $teams = [
            $request->champion_team_id,
            $request->first_runner_up_team_id,
            $request->second_runner_up_team_id
        ];

        if ($isDoubleBronze && $request->has('second_runner_up_team_id_2')) {
            $teams[] = $request->second_runner_up_team_id_2;
        }

        if (count(array_unique($teams)) !== count($teams)) {
            return redirect()->back()->withErrors(['error' => 'Each medal position must have a different team.'])->withInput();
        }

        // Create champion (gold) record
        MedalRecord::create([
            'team_id' => $request->champion_team_id,
            'event_category_id' => $request->event_category_id,
            'gold_tally' => $medalCount,
            'silver_tally' => 0,
            'bronze_tally' => 0,
            'number_of_participant' => $request->number_of_participant,
            'year' => $currentYear,
            'entry_by' => auth()->id(),
            'posted_by' => auth()->id()
        ]);

        // Create 1st runner up (silver) record
        MedalRecord::create([
            'team_id' => $request->first_runner_up_team_id,
            'event_category_id' => $request->event_category_id,
            'gold_tally' => 0,
            'silver_tally' => $medalCount,
            'bronze_tally' => 0,
            'number_of_participant' => $request->number_of_participant,
            'year' => $currentYear,
            'entry_by' => auth()->id(),
            'posted_by' => auth()->id()
        ]);

        // Create 2nd runner up (bronze) record(s)
        if ($isDoubleBronze) {
            // Create first bronze record - split the bronze medal count
            MedalRecord::create([
                'team_id' => $request->second_runner_up_team_id,
                'event_category_id' => $request->event_category_id,
                'gold_tally' => 0,
                'silver_tally' => 0,
                'bronze_tally' => $medalCount / 2,
                'number_of_participant' => $request->number_of_participant,
                'year' => $currentYear,
                'entry_by' => auth()->id(),
                'posted_by' => auth()->id()
            ]);

            // Create second bronze record - split the bronze medal count
            MedalRecord::create([
                'team_id' => $request->second_runner_up_team_id_2,
                'event_category_id' => $request->event_category_id,
                'gold_tally' => 0,
                'silver_tally' => 0,
                'bronze_tally' => $medalCount / 2,
                'number_of_participant' => $request->number_of_participant,
                'year' => $currentYear,
                'entry_by' => auth()->id(),
                'posted_by' => auth()->id()
            ]);
        } else {
            // Create single bronze record
            MedalRecord::create([
                'team_id' => $request->second_runner_up_team_id,
                'event_category_id' => $request->event_category_id,
                'gold_tally' => 0,
                'silver_tally' => 0,
                'bronze_tally' => $medalCount,
                'number_of_participant' => $request->number_of_participant,
                'year' => $currentYear,
                'entry_by' => auth()->id(),
                'posted_by' => auth()->id()
            ]);
        }

        return redirect()->route('medal_records.index')->with('success', 'Medal records created successfully.');
    }
    public function approve(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'is_posted' => 'required|boolean',
        ]);

        $medalRecord = MedalRecord::findOrFail($id);
        $validatedData['posted_by'] = auth()->id();
        $medalRecord->update($validatedData);

        return redirect()->route('medal_records.index')->with('success', 'Medal record approved successfully.');

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

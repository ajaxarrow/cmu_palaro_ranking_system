<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\College;
use App\Models\TeamComposition;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Team::with('colleges')->latest()->get(); 
        $colleges = College::all(); 

        return view('admin.teams.index', compact('teams', 'colleges'));
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
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'desc' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'college_ids' => 'required|array',
                'college_ids.*' => 'exists:colleges,id',
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('teams', 'public'); 
                
            }
    
            // Create the team
            $team = Team::create([
                'name' => $request->name,
                'desc' => $request->desc,
                'image' => $imagePath,
            ]);

            // Loop through college_ids and insert into team_compositions
            foreach ($request->college_ids as $college_id) {
                TeamComposition::create([
                    'team_id' => $team->id,
                    'college_id' => $college_id,
                ]);
            }

            return redirect()->route('teams.index')->with('success', 'Team created successfully!');

        } catch (\Exception $e) {
            return redirect()->route('teams.index')->with('error', 'An error occurred while creating the team.');
        }
    }
    /**
     * Display the specified resource.
     * This is for tallying scores of each team
     */
    public function show(int $id){
        $team = Team::with([
            'medalRecords.eventCategory.sport',
            'medalRecords.eventCategory.medalWeightCategory'
        ])->findOrFail($id);

        $medalTally = [];
        $overallTotal = [
            'gold_tally' => 0, 'gold_weight' => 0,
            'silver_tally' => 0, 'silver_weight' => 0,
            'bronze_tally' => 0, 'bronze_weight' => 0,
            'total_medals' => 0, 'total_weight' => 0
        ];

        foreach ($team->medalRecords as $record) {

            $sportName = $record->eventCategory->sport->name;
            $eventCategoryName = $record->eventCategory->name;
            $medalWeights = $record->eventCategory->medalWeightCategory;

            // Check if the event has double bronze rule
            $isDoubleBronze = $record->eventCategory->is_double_bronze;
            $bronzeTally = $record->bronze_tally;

            if ($isDoubleBronze && $bronzeTally > 0) {
                $medalWeights->bronze_weight = $medalWeights->bronze_weight / 2;
            }

            // Calculate weighted medal tallies
            $goldWeight = $record->gold_tally * $medalWeights->gold_weight;
            $silverWeight = $record->silver_tally * $medalWeights->silver_weight;
            $bronzeWeight = $bronzeTally * $medalWeights->bronze_weight;
            $totalWeight = $goldWeight + $silverWeight + $bronzeWeight;
            $totalMedals = $record->gold_tally + $record->silver_tally + $bronzeTally;




            // Organize data for the view
            $medalTally[$sportName]['events'][] = [
                'event_category' => $eventCategoryName,
                'gold_tally' => $record->gold_tally,
                'gold_weight' => $goldWeight,
                'silver_tally' => $record->silver_tally,
                'silver_weight' => $silverWeight,
                'bronze_tally' => $bronzeTally, // Updated tally
                'bronze_weight' => $bronzeWeight,
                'total_medals' => $totalMedals,
                'total_weight' => $totalWeight
            ];

            // Initialize per-sport totals
            if (!isset($medalTally[$sportName]['total'])) {
                $medalTally[$sportName]['total'] = [
                    'gold_tally' => 0, 'gold_weight' => 0,
                    'silver_tally' => 0, 'silver_weight' => 0,
                    'bronze_tally' => 0, 'bronze_weight' => 0,
                    'total_medals' => 0, 'total_weight' => 0
                ];
            }

            // Aggregate per-sport totals
            $medalTally[$sportName]['total']['gold_tally'] += $record->gold_tally;
            $medalTally[$sportName]['total']['gold_weight'] += $goldWeight;
            $medalTally[$sportName]['total']['silver_tally'] += $record->silver_tally;
            $medalTally[$sportName]['total']['silver_weight'] += $silverWeight;
            $medalTally[$sportName]['total']['bronze_tally'] += $bronzeTally; // Updated tally
            $medalTally[$sportName]['total']['bronze_weight'] += $bronzeWeight;
            $medalTally[$sportName]['total']['total_medals'] += $totalMedals;
            $medalTally[$sportName]['total']['total_weight'] += $totalWeight;

            // Aggregate overall total
            $overallTotal['gold_tally'] += $record->gold_tally;
            $overallTotal['gold_weight'] += $goldWeight;
            $overallTotal['silver_tally'] += $record->silver_tally;
            $overallTotal['silver_weight'] += $silverWeight;
            $overallTotal['bronze_tally'] += $bronzeTally; // Updated tally
            $overallTotal['bronze_weight'] += $bronzeWeight;
            $overallTotal['total_medals'] += $totalMedals;
            $overallTotal['total_weight'] += $totalWeight;
        }

        return view('admin.teams.show', compact('team', 'medalTally', 'overallTotal'));
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
        try {
            $team = Team::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'desc' => 'nullable|string',
                'college_ids' => 'required|array',
                'college_ids.*' => 'exists:colleges,id',
            ]);
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('teams', 'public'); 
                
            }

            // Update team info
            $team->update([
                'name' => $request->name,
                'desc' => $request->desc,
                'image' => $imagePath,
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
        } catch (\Exception $e) {
            return redirect()->route('teams.index', $id)->with('error', 'An error occurred while updating the team.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        try {
            $team->delete();
            return redirect()->route('teams.index');
        } catch (\Exception $e) {
            return redirect()->route('teams.index')->with('error', 'An error occurred while deleting the team.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Team;
use Illuminate\Http\Request;

class ResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        $medalTally = [];
        $overallTotal = [
            'gold_tally' => 0, 'gold_weight' => 0,
            'silver_tally' => 0, 'silver_weight' => 0,
            'bronze_tally' => 0, 'bronze_weight' => 0,
            'total_medals' => 0, 'total_weight' => 0
        ];

        $teams = Team::with([
            'medalRecords.eventCategory.sport',
            'medalRecords.eventCategory.medalWeightCategory'
        ])->get();

        foreach ($teams as $team) {  // <-- FIX: Loop through each team
            $teamTotal = [
                'gold_tally' => 0, 'gold_weight' => 0,
                'silver_tally' => 0, 'silver_weight' => 0,
                'bronze_tally' => 0, 'bronze_weight' => 0,
                'total_medals' => 0, 'total_weight' => 0
            ];

            foreach ($team->medalRecords as $record) {
                $sportName = $record->eventCategory->sport->name;
                $eventCategoryName = $record->eventCategory->name;
                $medalWeights = $record->eventCategory->medalWeightCategory;

                $isDoubleBronze = $record->eventCategory->is_double_bronze;
                $bronzeTally = $record->bronze_tally;

                if ($isDoubleBronze && $bronzeTally > 0) {
                    $medalWeights->bronze_weight = $medalWeights->bronze_weight / 2;
                }

                // Calculate weighted medal tallies
                $goldWeight = $record->gold_tally * $medalWeights->gold_weight;
                $silverWeight = $record->silver_tally * $medalWeights->silver_weight;
                $bronzeWeight = $record->bronze_tally * $medalWeights->bronze_weight;
                $totalWeight = $goldWeight + $silverWeight + $bronzeWeight;
                $totalMedals = $record->gold_tally + $record->silver_tally + $record->bronze_tally;

                // Organize data for the view
                $medalTally[$team->id]['sports'][$sportName]['events'][] = [
                    'event_category' => $eventCategoryName,
                    'gold_tally' => $record->gold_tally,
                    'gold_weight' => $goldWeight,
                    'silver_tally' => $record->silver_tally,
                    'silver_weight' => $silverWeight,
                    'bronze_tally' => $record->bronze_tally,
                    'bronze_weight' => $bronzeWeight,
                    'total_medals' => $totalMedals,
                    'total_weight' => $totalWeight
                ];

                // Calculate per-team total
                $teamTotal['gold_tally'] += $record->gold_tally;
                $teamTotal['gold_weight'] += $goldWeight;
                $teamTotal['silver_tally'] += $record->silver_tally;
                $teamTotal['silver_weight'] += $silverWeight;
                $teamTotal['bronze_tally'] += $record->bronze_tally;
                $teamTotal['bronze_weight'] += $bronzeWeight;
                $teamTotal['total_medals'] += $totalMedals;
                $teamTotal['total_weight'] += $totalWeight;

                // Calculate overall total
                $overallTotal['gold_tally'] += $record->gold_tally;
                $overallTotal['gold_weight'] += $goldWeight;
                $overallTotal['silver_tally'] += $record->silver_tally;
                $overallTotal['silver_weight'] += $silverWeight;
                $overallTotal['bronze_tally'] += $record->bronze_tally;
                $overallTotal['bronze_weight'] += $bronzeWeight;
                $overallTotal['total_medals'] += $totalMedals;
                $overallTotal['total_weight'] += $totalWeight;
            }

            // Store total score per team
            $medalTally[$team->id]['total'] = $teamTotal;
            $medalTally[$team->id]['team'] = $team['name'];
        }

        return view('admin.results.index',compact('teams', 'medalTally', 'overallTotal'));

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

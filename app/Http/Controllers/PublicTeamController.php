<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Log;

class PublicTeamController extends Controller
{
    /**
     * Get all team standings sorted by medal ranking.
     */
    public function index()
    {
        $teams = Team::with([
            'medalRecords' => function ($query) {
                $query->where('is_posted', true);
            },
            'medalRecords.eventCategory.sport',
            'medalRecords.eventCategory.medalWeightCategory'
        ])
        ->get()
        ->sortByDesc(fn($team) => $team->medalRecords->sum('gold_tally') * 3 +
                                   $team->medalRecords->sum('silver_tally') * 2 +
                                   $team->medalRecords->sum('bronze_tally'));

        return view('public.standings.index', compact('teams'));
    }

    /**
     * Get team standings by ID with detailed medal breakdown.
     */
    public function show(int $id)
    {
        try {
            $team = Team::with([
                'medalRecords' => function ($query) {
                    $query->where('is_posted', true);
                },
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

            $sortedMedalRecords = $team->medalRecords->sortBy(function ($record) {
                return $record->eventCategory->sport->name;
            });

            foreach ($sortedMedalRecords as $record) {
                $sportName = $record->eventCategory->sport->name;
                $eventCategoryName = $record->eventCategory->name;
                $medalWeights = $record->eventCategory->medalWeightCategory;
                $isDoubleBronze = $record->eventCategory->is_double_bronze;
                $bronzeTally = $record->bronze_tally;

                // Apply double bronze logic correctly
                $bronzeWeight = $bronzeTally * ($isDoubleBronze ? $medalWeights->bronze_weight / 2 : $medalWeights->bronze_weight);
                $goldWeight = $record->gold_tally * $medalWeights->gold_weight;
                $silverWeight = $record->silver_tally * $medalWeights->silver_weight;
                $totalWeight = $goldWeight + $silverWeight + $bronzeWeight;
                $totalMedals = $record->gold_tally + $record->silver_tally + $bronzeTally;

                $medalTally[$sportName]['events'][] = [
                    'event_category' => $eventCategoryName,
                    'gold_tally' => $record->gold_tally,
                    'gold_weight' => $goldWeight,
                    'silver_tally' => $record->silver_tally,
                    'silver_weight' => $silverWeight,
                    'bronze_tally' => $bronzeTally,
                    'bronze_weight' => $bronzeWeight,
                    'total_medals' => $totalMedals,
                    'total_weight' => $totalWeight
                ];

                if (!isset($medalTally[$sportName]['total'])) {
                    $medalTally[$sportName]['total'] = [
                        'gold_tally' => 0, 'gold_weight' => 0,
                        'silver_tally' => 0, 'silver_weight' => 0,
                        'bronze_tally' => 0, 'bronze_weight' => 0,
                        'total_medals' => 0, 'total_weight' => 0
                    ];
                }

                // Sum totals per sport
                $medalTally[$sportName]['total']['gold_tally'] += $record->gold_tally;
                $medalTally[$sportName]['total']['gold_weight'] += $goldWeight;
                $medalTally[$sportName]['total']['silver_tally'] += $record->silver_tally;
                $medalTally[$sportName]['total']['silver_weight'] += $silverWeight;
                $medalTally[$sportName]['total']['bronze_tally'] += $bronzeTally;
                $medalTally[$sportName]['total']['bronze_weight'] += $bronzeWeight;
                $medalTally[$sportName]['total']['total_medals'] += $totalMedals;
                $medalTally[$sportName]['total']['total_weight'] += $totalWeight;

                // Sum overall totals
                $overallTotal['gold_tally'] += $record->gold_tally;
                $overallTotal['gold_weight'] += $goldWeight;
                $overallTotal['silver_tally'] += $record->silver_tally;
                $overallTotal['silver_weight'] += $silverWeight;
                $overallTotal['bronze_tally'] += $bronzeTally;
                $overallTotal['bronze_weight'] += $bronzeWeight;
                $overallTotal['total_medals'] += $totalMedals;
                $overallTotal['total_weight'] += $totalWeight;
            }

            foreach ($medalTally as $sport => &$data) {
                $data['events'] = collect($data['events'])->sortBy('event_category')->values()->toArray();
            }

            // Change from JSON response to view response
            return view('public.standings.show', [
                'team' => $team,

                'medalTally' => $medalTally,
                'overallTotal' => $overallTotal
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching team standings: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Create an empty team object to prevent undefined variable error
            $team = new Team();
            $medalTally = [];
            $overallTotal = [
                'gold_tally' => 0, 'gold_weight' => 0,
                'silver_tally' => 0, 'silver_weight' => 0,
                'bronze_tally' => 0, 'bronze_weight' => 0,
                'total_medals' => 0, 'total_weight' => 0
            ];

            return view('public.standings.show', [
                'team' => $team,
                'medalTally' => $medalTally,
                'overallTotal' => $overallTotal,
                'error' => 'An error occurred while retrieving team standings.'
            ]);
        }
    }
}

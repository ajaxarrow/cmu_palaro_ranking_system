<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Sport;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;

class LandingPageController extends Controller
{
    public function index()
    {
        $medalTally = [];
        $overallTotal = [
            'gold_tally' => 0, 'gold_weight' => 0,
            'silver_tally' => 0, 'silver_weight' => 0,
            'bronze_tally' => 0, 'bronze_weight' => 0,
            'total_medals' => 0, 'total_weight' => 0
        ];

        // Fetch all event categories with their sports and medal records
        $eventCategories = EventCategory::with([
            'sport',
            'medalWeightCategory',
            'medalRecords' => function ($query) {
                $query->where('is_posted', true);
            },
            'medalRecords.team.colleges'
        ])->get();

        // Prepare sportResults array
        $sportResults = [];

        // Initialize sports structure
        $sports = Sport::all();
        foreach ($sports as $sport) {
            $sportResults[$sport->id] = [
                'name' => $sport->name,
                'icon' => $sport->icon,
                'events' => [],
            ];
        }

        // Process event categories and their winners
        foreach ($eventCategories as $eventCategory) {
            $sportId = $eventCategory->sport_id;
            $eventId = $eventCategory->id;
            $medalWeights = $eventCategory->medalWeightCategory;
            $isDoubleBronze = $eventCategory->is_double_bronze;

            // Initialize event in sport results
            $sportResults[$sportId]['events'][$eventId] = [
                'id' => $eventId,
                'name' => $eventCategory->name,
                'gold_winner' => null,
                'silver_winner' => null,
                'bronze_winners' => [],
                'medal_records' => []
            ];

            // Group medal records by team
            $teamMedals = [];
            foreach ($eventCategory->medalRecords as $record) {
                if (!isset($teamMedals[$record->team_id])) {
                    $teamMedals[$record->team_id] = [
                        'team' => $record->team,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                        'medal_weight' => 0
                    ];
                }

                $bronzeMedalWeight = 0;
                // Calculate medal weights
                $bronzeWeight = $medalWeights->bronze_weight;
                $isDoubleBronze = $record->eventCategory->is_double_bronze;
                if ($isDoubleBronze) {
                    $bronzeMedalWeight = $medalWeights-> bronze_weight /2;
                } else {
                    $bronzeMedalWeight = $medalWeights-> bronze_weight;
                }

                $goldWeight = $record->gold_tally * $medalWeights->gold_weight;
                $silverWeight = $record->silver_tally * $medalWeights->silver_weight;
                $bronzeWeight = $record->bronze_tally * $bronzeMedalWeight;
                $totalWeight = $goldWeight + $silverWeight + $bronzeWeight;

                // Update team medals for this event
                $teamMedals[$record->team_id]['gold_tally'] += $record->gold_tally;
                $teamMedals[$record->team_id]['silver_tally'] += $record->silver_tally;
                $teamMedals[$record->team_id]['bronze_tally'] += $record->bronze_tally;
                $teamMedals[$record->team_id]['medal_weight'] += $totalWeight;

                // Add record to event's medal records
                $sportResults[$sportId]['events'][$eventId]['medal_records'][] = [
                    'id' => $record->id,
                    'team_id' => $record->team_id,
                    'team_name' => $record->team->name,
                    'gold_tally' => $record->gold_tally,
                    'silver_tally' => $record->silver_tally,
                    'bronze_tally' => $record->bronze_tally,
                    'total_weight' => $totalWeight
                ];
            }

            // Sort teams by medals (gold first, then silver, then bronze)
            uasort($teamMedals, function($a, $b) {
                if ($a['gold_tally'] != $b['gold_tally']) {
                    return $b['gold_tally'] <=> $a['gold_tally'];
                }
                if ($a['silver_tally'] != $b['silver_tally']) {
                    return $b['silver_tally'] <=> $a['silver_tally'];
                }
                return $b['bronze_tally'] <=> $a['bronze_tally'];
            });

            // Assign winners
            $winners = array_values($teamMedals);
            if (count($winners) > 0) {
                if ($winners[0]['gold_tally'] > 0) {
                    $sportResults[$sportId]['events'][$eventId]['gold_winner'] = [
                        'team_id' => $winners[0]['team']->id,
                        'team_name' => $winners[0]['team']->name,
                        'team_image' => $winners[0]['team']->image,
                        'colleges' => $winners[0]['team']->colleges->pluck('name'),
                    ];
                }

                if (count($winners) > 1 && $winners[1]['silver_tally'] > 0) {
                    $sportResults[$sportId]['events'][$eventId]['silver_winner'] = [
                        'team_id' => $winners[1]['team']->id,
                        'team_name' => $winners[1]['team']->name,
                        'team_image' => $winners[1]['team']->image,
                        'colleges' => $winners[1]['team']->colleges->pluck('name'),
                    ];
                }

                // For bronze, we might have multiple winners (especially with double bronze)
                $bronzeWinners = [];
                for ($i = 2; $i < count($winners); $i++) {
                    if ($winners[$i]['bronze_tally'] > 0) {
                        $bronzeWinners[] = [
                            'team_id' => $winners[$i]['team']->id,
                            'team_name' => $winners[$i]['team']->name,
                            'team_image' => $winners[$i]['team']->image,
                            'colleges' => $winners[$i]['team']->colleges->pluck('name'),
                        ];
                    }
                }
                $sportResults[$sportId]['events'][$eventId]['bronze_winners'] = $bronzeWinners;
            }
            $bronzeMedalWeight = 0;
        }

        // Now calculate the overall team totals as before
        $teams = Team::with([
            'medalRecords' => function ($query) {
                $query->where('is_posted', true);
            },
            'medalRecords.eventCategory.sport',
            'medalRecords.eventCategory.medalWeightCategory',
            'colleges'
        ])->get();

        foreach ($teams as $team) {
            $teamTotal = [
                'gold_tally' => 0, 'gold_weight' => 0,
                'silver_tally' => 0, 'silver_weight' => 0,
                'bronze_tally' => 0, 'bronze_weight' => 0,
                'total_medals' => 0, 'total_weight' => 0
            ];

            foreach ($team->medalRecords as $record) {
                $medalWeights = $record->eventCategory->medalWeightCategory;

                $bronzeMedalWeight = 0;
                // Calculate medal weights
                $bronzeWeight = $medalWeights->bronze_weight;
                $isDoubleBronze = $record->eventCategory->is_double_bronze;
                if ($isDoubleBronze) {
                    $bronzeMedalWeight = $medalWeights-> bronze_weight /2;
                } else {
                    $bronzeMedalWeight = $medalWeights-> bronze_weight;
                }
                // Calculate weighted tallies
                $goldWeight = $record->gold_tally * $medalWeights->gold_weight;
                $silverWeight = $record->silver_tally * $medalWeights->silver_weight;
                $bronzeWeight = $record->bronze_tally * $bronzeMedalWeight;
                $totalWeight = $goldWeight + $silverWeight + $bronzeWeight;
                $totalMedals = $record->gold_tally + $record->silver_tally + $record->bronze_tally;

                // Update team totals
                $teamTotal['gold_tally'] += $record->gold_tally;
                $teamTotal['gold_weight'] += $goldWeight;
                $teamTotal['silver_tally'] += $record->silver_tally;
                $teamTotal['silver_weight'] += $silverWeight;
                $teamTotal['bronze_tally'] += $record->bronze_tally;
                $teamTotal['bronze_weight'] += $bronzeWeight;
                $teamTotal['total_medals'] += $totalMedals;
                $teamTotal['total_weight'] += $totalWeight;

                // Update overall total
                $overallTotal['gold_tally'] += $record->gold_tally;
                $overallTotal['gold_weight'] += $goldWeight;
                $overallTotal['silver_tally'] += $record->silver_tally;
                $overallTotal['silver_weight'] += $silverWeight;
                $overallTotal['bronze_tally'] += $record->bronze_tally;
                $overallTotal['bronze_weight'] += $bronzeWeight;
                $overallTotal['total_medals'] += $totalMedals;
                $overallTotal['total_weight'] += $totalWeight;

                $bronzeMedalWeight = 0;
            }

            $medalTally[$team->id] = [
                'team' => $team['name'],
                'team_image' => $team['image'],
                'team_id' => $team['id'],
                'description' => $team['desc'],
                'colleges' => $team->colleges->pluck('name'),
                'total' => $teamTotal
            ];
        }

        // Convert events from associative to indexed arrays
        foreach ($sportResults as &$sport) {
            $sport['events'] = array_values($sport['events']);
        }

        // Sort by total weight in descending order
        usort($medalTally, function ($a, $b) {
            return $b['total']['total_weight'] <=> $a['total']['total_weight'];
        });

        // Convert associative array to indexed array for frontend usage
        $medalTally = array_values($medalTally);

        // Assign ordinal rankings
        $rank = 1;
        foreach ($medalTally as &$team) {
            $team['rank'] = $this->ordinal($rank);
            $rank++;
        }

// Add this code right before the return statement in the index() method
// This will flatten the nested sports/events structure into a single array of events
        $flattenedEvents = [];
        foreach ($sportResults as $sport) {
            foreach ($sport['events'] as $event) {
                $flattenedEvents[] = array_merge($event, [
                    'sport_name' => $sport['name'],
                    'sport_icon' => $sport['icon']
                ]);
            }
        }

        return view('public.landing.index', [
            'medalTally' => $medalTally,
            'overallTotal' => $overallTotal,
            'sportResults' => $sportResults,
            'flattenedEvents' => $flattenedEvents,  // Add this line
            'Vite' => app(Vite::class)
        ]);
    }

    private function ordinal($number)
    {
        $suffixes = ['th', 'st', 'nd', 'rd'];
        $mod100 = $number % 100;
        return $number . ($suffixes[($mod100 - 20) % 10] ?? $suffixes[$mod100] ?? $suffixes[0]) . ' place';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Sport;
use App\Models\MedalRecord;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;

class LandingPageController extends Controller
{
    public function index()
    {

        $results = MedalRecord::where('is_posted', true)->get();
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
            if (!isset($sportResults[$sportId]['events'][$eventId])) {
                $sportResults[$sportId]['events'][$eventId] = [
                    'id' => $eventId,
                    'name' => $eventCategory->name,
                    'sport_name' => $eventCategory->sport->name,
                    'sport_icon' => $eventCategory->sport->icon,
                    'gold_winners' => [],
                    'silver_winners' => [],
                    'bronze_winners' => [],
                    'medal_records' => []
                ];
            }

            // Process medal records
            $teamMedals = [];
            foreach ($eventCategory->medalRecords as $record) {
                $team = $record->team;
                
                // Calculate medal weights
                $bronzeMedalWeight = 0;
                $bronzeWeight = $medalWeights->bronze_weight;
                if ($isDoubleBronze) {
                    $bronzeMedalWeight = $medalWeights->bronze_weight / 2;
                } else {
                    $bronzeMedalWeight = $medalWeights->bronze_weight;
                }

                $goldWeight = $record->gold_tally * $medalWeights->gold_weight;
                $silverWeight = $record->silver_tally * $medalWeights->silver_weight;
                $bronzeWeight = $record->bronze_tally * $bronzeMedalWeight;
                $totalWeight = $goldWeight + $silverWeight + $bronzeWeight;

                // Add record to event's medal records
                $sportResults[$sportId]['events'][$eventId]['medal_records'][] = [
                    'id' => $record->id,
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                    'gold_tally' => $record->gold_tally,
                    'silver_tally' => $record->silver_tally,
                    'bronze_tally' => $record->bronze_tally,
                    'total_weight' => $totalWeight
                ];

                // Track team medals for winner assignment
                if (!isset($teamMedals[$team->id])) {
                    $teamMedals[$team->id] = [
                        'team' => $team,
                        'gold_entries' => [],
                        'silver_entries' => [],
                        'bronze_entries' => [],
                        'medal_weight' => 0
                    ];
                }

                // Add medal entries for each medal type
                if ($record->gold_tally > 0) {
                    $teamMedals[$team->id]['gold_entries'][] = [
                        'gold_tally' => $record->gold_tally,
                        'silver_tally' => 0,
                        'bronze_tally' => 0
                    ];
                }
                if ($record->silver_tally > 0) {
                    $teamMedals[$team->id]['silver_entries'][] = [
                        'gold_tally' => 0,
                        'silver_tally' => $record->silver_tally,
                        'bronze_tally' => 0
                    ];
                }
                if ($record->bronze_tally > 0) {
                    $teamMedals[$team->id]['bronze_entries'][] = [
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => $record->bronze_tally
                    ];
                }

                $teamMedals[$team->id]['medal_weight'] += $totalWeight;
            }

            // Assign winners based on medal entries
            foreach ($teamMedals as $teamId => $teamData) {
                $team = $teamData['team'];
                
                // Add gold winner entries
                foreach ($teamData['gold_entries'] as $entry) {
                    $sportResults[$sportId]['events'][$eventId]['gold_winners'][] = [
                        'team_id' => $team->id,
                        'team_name' => $team->name,
                        'team_image' => $team->image,
                        'colleges' => $team->colleges->pluck('name'),
                        'gold_tally' => $entry['gold_tally'],
                        'silver_tally' => $entry['silver_tally'],
                        'bronze_tally' => $entry['bronze_tally']
                    ];
                }

                // Add silver winner entries
                foreach ($teamData['silver_entries'] as $entry) {
                    $sportResults[$sportId]['events'][$eventId]['silver_winners'][] = [
                        'team_id' => $team->id,
                        'team_name' => $team->name,
                        'team_image' => $team->image,
                        'colleges' => $team->colleges->pluck('name'),
                        'gold_tally' => $entry['gold_tally'],
                        'silver_tally' => $entry['silver_tally'],
                        'bronze_tally' => $entry['bronze_tally']
                    ];
                }

                // Add bronze winner entries
                foreach ($teamData['bronze_entries'] as $entry) {
                    $sportResults[$sportId]['events'][$eventId]['bronze_winners'][] = [
                        'team_id' => $team->id,
                        'team_name' => $team->name,
                        'team_image' => $team->image,
                        'colleges' => $team->colleges->pluck('name'),
                        'gold_tally' => $entry['gold_tally'],
                        'silver_tally' => $entry['silver_tally'],
                        'bronze_tally' => $entry['bronze_tally']
                    ];
                }
            }
        }

        // Convert events from associative to indexed arrays
        foreach ($sportResults as &$sport) {
            $sport['events'] = array_values($sport['events']);
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
                    $bronzeMedalWeight = $medalWeights->bronze_weight / 2;
                } else {
                    $bronzeMedalWeight = $medalWeights->bronze_weight;
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

        // Consolidate events
        $consolidatedEvents = [];
        foreach ($sportResults as $sport) {
            foreach ($sport['events'] as $event) {
                $eventName = $event['name'];
                
                // If event doesn't exist in consolidated events, create it
                if (!isset($consolidatedEvents[$eventName])) {
                    $consolidatedEvents[$eventName] = [
                        'name' => $eventName,
                        'sport_name' => $sport['name'],
                        'sport_icon' => $sport['icon'],
                        'gold_winners' => [],
                        'silver_winners' => [],
                        'bronze_winners' => [],
                        'medal_records' => []
                    ];
                }

                // Merge medal records
                $consolidatedEvents[$eventName]['medal_records'] = array_merge(
                    $consolidatedEvents[$eventName]['medal_records'],
                    $event['medal_records']
                );

                // Add all winners without checking for duplicates
                foreach (['gold', 'silver', 'bronze'] as $medalType) {
                    if (isset($event[$medalType . '_winners']) && is_array($event[$medalType . '_winners'])) {
                        foreach ($event[$medalType . '_winners'] as $winner) {
                            $consolidatedEvents[$eventName][$medalType . '_winners'][] = $winner;
                        }
                    }
                }
            }
        }

        // Convert consolidated events to a flat array
        $flattenedEvents = array_values($consolidatedEvents);

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

        return view('public.landing.index', [
            'results' => $results,
            'medalTally' => $medalTally,
            'overallTotal' => $overallTotal,
            'sportResults' => $sportResults,
            'flattenedEvents' => $flattenedEvents,
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
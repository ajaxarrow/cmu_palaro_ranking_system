<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedalRecord;
use App\Models\Team;
use App\Models\EventCategory;
use App\Models\Sport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


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
        $sportId = null;
        $sports = collect();

        if ($user->role == 'tournament_director' || $user->role == 'assistant_tournament_director') {
            // Filter sports to only show the sport associated with this tournament director
            $sport_data_id = $user->sport_id;
            $sports = Sport::where('id', $sport_data_id)
                ->with(['eventCategories' => function($query) {
                    $query->orderBy('name');
                }])
                ->orderBy('name')
                ->get();
        } else {
            // For other roles, show all sports
            $sports = Sport::with(['eventCategories' => function($query) {
                $query->orderBy('name');
            }])
            ->orderBy('name')
            ->get();
        }

        // Get all teams
        $teams = Team::orderBy('name')->get();

        // Get all event categories for the form
        $events = EventCategory::orderBy('name')->get();
        $eventCategories = $sports->flatMap->eventCategories->unique('id');

        // Get all medal records
        $medalRecords = MedalRecord::with(['team', 'event'])->get();

        return view('admin.medal_records.index', compact(
            'sportId',
            'eventCategories',
            'user',
            'sports',
            'teams',
            'events',
            'medalRecords'
        ));
    }


    public function recordsBySport(int $sportId)
    {
        $user = auth()->user();

        // Get all sports with their event categories
        $sports = Sport::with(['eventCategories' => function($query) {
            $query->orderBy('name');
        }])->orderBy('name')->get();

        // Get all teams
        $teams = Team::orderBy('name')->get();

        // Get all event categories for the form
        $events = EventCategory::where('sport_id', $sportId)->orderBy('name')->get();
        $eventCategories = $sports->flatMap->eventCategories->unique('id');

        // Get all medal records
        $medalRecords = MedalRecord::with(['team', 'event'])
            ->whereHas('event', function($query) use ($sportId) {
                $query->where('sport_id', $sportId);
            })
            ->get();

        return view('admin.medal_records.index', compact(
            'sportId',
            'eventCategories',
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

        if (count(array_unique($teams)) !== count($teams) && !$request->allowTeamReuse) {
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
                'bronze_tally' => $medalCount,
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
                'bronze_tally' => $medalCount,
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
    public function update(Request $request, int $eventCategoryId)
    {
        $validatedData = $request->validate([
            'champion_team_id' => 'required|exists:teams,id',
            'first_runner_up_team_id' => 'required|exists:teams,id',
            'second_runner_up_team_id' => 'required|exists:teams,id',
            'second_runner_up_team_id_2' => 'nullable|exists:teams,id',
            'event_category_id' => 'required|exists:event_categories,id',
            'medal_count' => 'required|integer|min:1',
            'number_of_participant' => 'required|integer|min:1',
            'is_double_bronze' => 'required|in:0,1',
            'allow_team_reuse' => 'nullable|boolean',
            'is_posted' => 'nullable'
        ]);

        $eventCategory = EventCategory::findOrFail($eventCategoryId);

        DB::beginTransaction();

        try {
            // Fetch existing records for this event category
            $existingRecords = MedalRecord::where('event_category_id', $eventCategoryId)->get();
            $isPosted = isset($validatedData['is_posted']) && $validatedData['is_posted'] == 1;

            // Delete all existing records for this event category
            // We'll create new ones for all positions rather than trying to update existing ones
            MedalRecord::where('event_category_id', $eventCategoryId)->delete();

            // Create gold medal record
            $this->createMedalRecord(
                $eventCategoryId,
                $validatedData['champion_team_id'],
                $validatedData['medal_count'],
                0,
                0,
                $validatedData['number_of_participant'],
                $isPosted
            );

            // Create silver medal record
            $this->createMedalRecord(
                $eventCategoryId,
                $validatedData['first_runner_up_team_id'],
                0,
                $validatedData['medal_count'],
                0,
                $validatedData['number_of_participant'],
                $isPosted
            );

            // Process bronze medal(s)
            if ($validatedData['is_double_bronze'] == '1') {

                $this->createMedalRecord(
                    $eventCategoryId,
                    $validatedData['second_runner_up_team_id'],
                    0,
                    0,
                    $validatedData['medal_count'],
                    $validatedData['number_of_participant'],
                    $isPosted
                );

                if (!empty($validatedData['second_runner_up_team_id_2'])) {
                    $this->createMedalRecord(
                        $eventCategoryId,
                        $validatedData['second_runner_up_team_id_2'],
                        0,
                        0,
                        $validatedData['medal_count'],
                        $validatedData['number_of_participant'],
                        $isPosted
                    );
                }
            } else {
                // Create a single bronze medal record
                $this->createMedalRecord(
                    $eventCategoryId,
                    $validatedData['second_runner_up_team_id'],
                    0,
                    0,
                    $validatedData['medal_count'],
                    $validatedData['number_of_participant'],
                    $isPosted
                );
            }

            DB::commit();
            return redirect()->route('medal_records.index')->with('success', 'Medal records updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('medal_records.index')->with('error', 'Error updating medal records: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to create medal records
     */
    private function createMedalRecord($eventCategoryId, $teamId, $goldTally, $silverTally, $bronzeTally, $participants, $isPosted)
    {
        $record = new MedalRecord();
        $record->event_category_id = $eventCategoryId;
        $record->team_id = $teamId;
        $record->year = date('Y'); // Current year
        $record->gold_tally = $goldTally;
        $record->silver_tally = $silverTally;
        $record->bronze_tally = $bronzeTally;
        $record->number_of_participant = $participants;
        $record->is_posted = $isPosted;
        $record->entry_by = auth()->id();
        $record->posted_by = auth()->id();
        $record->save();

        return $record;
    }


    /**
     * Helper method to update or create medal records
     */
    private function updateMedalRecord($existingRecords, $eventCategoryId, $teamId, $goldTally, $silverTally, $bronzeTally, $participants, $isPosted)
    {
        // Try to find an existing record for this team
        $record = $existingRecords->first(function($item) use ($teamId) {
            return $item->team_id == $teamId;
        });

        // If no record exists, create a new one
        if (!$record) {
            $record = new MedalRecord();
            $record->event_category_id = $eventCategoryId;
            $record->team_id = $teamId;
            $record->year = date('Y'); // Current year
        }

        // Update the record
        $record->gold_tally = $goldTally;
        $record->silver_tally = $silverTally;
        $record->bronze_tally = $bronzeTally;
        $record->number_of_participant = $participants;
        $record->is_posted = $isPosted;
        $record->entry_by = auth()->id();
        $record->posted_by = auth()->id();


        $record->save();

        return $record;
    }

    /**

     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        // Delete all records where event_category_id matches $id
        MedalRecord::where('event_category_id', $id)->delete();

        return redirect()->route('medal_records.index')->with('success', 'All medal records under this event category have been deleted.');
    }

    public function approve(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'is_posted' => 'required|boolean',
        ]);

        // Update all records where event_category_id matches $id
        MedalRecord::where('event_category_id', $id)->update([
            'is_posted' => $validatedData['is_posted'],
            'posted_by' => auth()->id(),
        ]);

        return redirect()->route('medal_records.index')->with('success', 'All medal records under this event category have been approved.');
    }

    public function approveAll(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'is_posted' => 'required|boolean',
        ]);

        // Find all event category IDs that belong to the given sport_id
        $eventCategoryIds = EventCategory::where('sport_id', $id)->pluck('id');

        // Update medal records where event_category_id is in the list and is_posted is false
        MedalRecord::whereIn('event_category_id', $eventCategoryIds)
            ->where('is_posted', false)
            ->update([
                'is_posted' => $validatedData['is_posted'],
                'posted_by' => auth()->id(),
            ]);

        return redirect()->route('medal_records.index')->with('success', 'All unposted medal records under this sport category have been approved.');
    }
}

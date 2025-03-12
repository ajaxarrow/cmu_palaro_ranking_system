@extends('layouts.admin', ['title' => 'Medal Tally'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
@endsection

@section('content')

<!-- Start Content-->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container-fluid">

    <div class="py-4 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h3 class="fw-bold mb-0">Medal Tally</h3>
            <p class="text-muted">Track medal performances across all events</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <!-- Sport Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sportFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-filter-outline me-1"></i> Filter by Sport
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sportFilterDropdown">
                        <li><a class="dropdown-item active" href="#" onclick="filterSport('all')">All Sports</a></li>
                        @foreach($sports as $sport)
                            @php
                                $sportHasRecords = false;
                                foreach($sport->eventCategories as $eventCategory) {
                                    $eventRecords = $medalRecords->where('event_category_id', $eventCategory->id);
                                    if($eventRecords->count() > 0) {
                                        $sportHasRecords = true;
                                        break;
                                    }
                                }
                            @endphp

                            @if($sportHasRecords)
                                <li><a class="dropdown-item" href="#" onclick="filterSport('{{ $sport->id }}')">{{ $sport->name }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                <!-- Reset Filter Button (Initially Hidden) -->
                <button id="resetFilterBtn" class="btn btn-light" onclick="resetFilter()" style="display: none;">
                    <i class="mdi mdi-refresh me-1"></i> Reset Filter
                </button>
            </div>
            <!-- View toggle buttons -->
            <div class="view-tabs">
                <div class="view-tab card-view active" onclick="toggleView('card')">Card View</div>
                <div class="view-tab table-view" onclick="toggleView('table')">Table View</div>
            </div>

            @if($user->role != 'user')
                <button type="button" class="btn btn-primary custom-primary-background" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                    <i class="mdi mdi-plus me-1"></i> Add Record
                </button>
            @endif
        </div>
    </div>

    <!-- Card View -->
    <div id="cardView" class="view-content">
        @php
            $hasRecords = false;
        @endphp

        @foreach($sports as $sport)
            @php
                $sportHasRecords = false;
                foreach($sport->eventCategories as $eventCategory) {
                    $eventRecords = $medalRecords->where('event_category_id', $eventCategory->id);
                    if($eventRecords->count() > 0) {
                        $sportHasRecords = true;
                        $hasRecords = true;
                        break;
                    }
                }
            @endphp

            @if($sportHasRecords)
                <div class="sport-header mb-4">
                    <h4 class="fs-16 m-0">{{ $sport->name }}</h4>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4 mb-5">
                    @foreach($sport->eventCategories as $eventCategory)
                        @php
                            $eventRecords = $medalRecords->where('event_category_id', $eventCategory->id);
                            if($eventRecords->count() == 0) continue;

                            // Find gold, silver, and bronze winners
                            $goldWinner = $silverWinner = null;
                            $bronzeWinners = [];
                            $goldWeight = $silverWeight = $bronzeWeight = null;
                            $goldTally = $silverTally = $bronzeTally = 0;
                            $participants = 0;
                            $isPosted = false;

                            foreach ($eventRecords as $record) {
                                $team = $teams->find($record->team_id);

                                if ($record->gold_tally > 0) {
                                    $goldWinner = $team;
                                    $goldWeight = $record->gold_tally * $eventCategory->medalWeightCategory->gold_weight;
                                    $goldTally = $record->gold_tally;
                                }

                                if ($record->silver_tally > 0) {
                                    $silverWinner = $team;
                                    $silverWeight = $record->silver_tally * $eventCategory->medalWeightCategory->silver_weight;
                                    $silverTally = $record->silver_tally;
                                }

                                if ($record->bronze_tally > 0) {
                                    $bronzeWinners[] = $team;
                                    if($eventCategory->is_double_bronze){
                                        $bronzeWeight = $record->bronze_tally * $eventCategory->medalWeightCategory->bronze_weight / 2;
                                    } else {
                                        $bronzeWeight = $record->bronze_tally * $eventCategory->medalWeightCategory->bronze_weight;
                                    }
                                    $bronzeTally = $record->bronze_tally;
                                }

                                // Track the total number of participants and posting status
                                $participants += $record->number_of_participant;
                                $isPosted = $record->is_posted;
                            }
                        @endphp

                        <div class="col">
                            <div class="card medal-card h-100">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">{{ $eventCategory->name }}</h5>
                                    <span class="status-badge {{ $isPosted ? 'status-posted' : 'status-pending' }}">
                                        {{ $isPosted ? 'Posted' : 'Pending' }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="medal-position mb-3">
                                            <span class="medal-badge medal-gold">
                                                <i class="mdi mdi-medal medal-icon"></i> Champion
                                            </span>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="d-flex align-items-center gap-1">
                                                    @if($goldWinner->image)
                                                        <img src="{{ asset('storage/' . $goldWinner->image) }}" width="36" height="36" class="rounded-circle object-cover" style="object-fit: cover; object-position: top;">
                                                    @else
                                                        <i class="ph-duotone fs-24 ph-ranking mb-0"></i>
                                                    @endif
                                                    <span class="team-name">{{ $silverWinner ? $silverWinner->name : '-' }}</span>
                                                </div>
                                                <div class="medal-stats">
                                                    <div class="medal-stat-item">
                                                        <span class="medal-stat-value">{{ $goldTally }}</span>
                                                        <span class="medal-stat-label">Count</span>
                                                    </div>
                                                    <div class="medal-stat-item">
                                                        <span class="medal-stat-value">{{ $goldWeight }}</span>
                                                        <span class="medal-stat-label">Weight</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="medal-position mb-3">
                                            <span class="medal-badge medal-silver">
                                                <i class="mdi mdi-medal medal-icon"></i> 1st Runner Up
                                            </span>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div class="d-flex align-items-center gap-1">
                                                    @if($silverWinner->image)
                                                        <img src="{{ asset('storage/' . $silverWinner->image) }}" width="36" height="36" class="rounded-circle object-cover" style="object-fit: cover; object-position: top;">
                                                    @else
                                                        <i class="ph-duotone fs-24 ph-ranking mb-0"></i>
                                                    @endif
                                                    <span class="team-name">{{ $silverWinner ? $silverWinner->name : '-' }}</span>
                                                </div>
                                                <div class="medal-stats">
                                                    <div class="medal-stat-item">
                                                        <span class="medal-stat-value">{{ $silverTally }}</span>
                                                        <span class="medal-stat-label">Count</span>
                                                    </div>
                                                    <div class="medal-stat-item">
                                                        <span class="medal-stat-value">{{ $silverWeight }}</span>
                                                        <span class="medal-stat-label">Weight</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="medal-position">
                                            <span class="medal-badge medal-bronze">
                                                <i class="mdi mdi-medal medal-icon"></i> 2nd Runner Up
                                            </span>
                                            @if(count($bronzeWinners) > 0)
                                                @foreach($bronzeWinners as $bronzeWinner)
                                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                                        <div class="d-flex align-items-center gap-1">
                                                            @if($bronzeWinner->image)
                                                                <img src="{{ asset('storage/' . $bronzeWinner->image) }}" width="36" height="36" class="rounded-circle object-cover" style="object-fit: cover; object-position: top;">
                                                            @else
                                                                <i class="ph-duotone fs-24 ph-ranking mb-0"></i>
                                                            @endif
                                                            <span class="team-name">{{ $bronzeWinner ? $bronzeWinner->name : '-' }}</span>
                                                        </div>
                                                        <div class="medal-stats">
                                                            <div class="medal-stat-item">
                                                                <span class="medal-stat-value">{{ $bronzeTally }}</span>
                                                                <span class="medal-stat-label">Count</span>
                                                            </div>
                                                            <div class="medal-stat-item">
                                                                <span class="medal-stat-value">{{ $bronzeWeight }}</span>
                                                                <span class="medal-stat-label">Weight</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span class="team-name">-</span>
                                                    <div class="medal-stats">
                                                        <div class="medal-stat-item">
                                                            <span class="medal-stat-value">{{ $bronzeTally }}</span>
                                                            <span class="medal-stat-label">Count</span>
                                                        </div>
                                                        <div class="medal-stat-item">
                                                            <span class="medal-stat-value">{{ $bronzeWeight }}</span>
                                                            <span class="medal-stat-label">Weight</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        <i class="mdi mdi-account-group me-1"></i> {{ $participants }} Participants
                                    </div>
                                    @if($user->role != 'user')
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-floating"
                                                data-bs-toggle="modal" data-bs-target="#editRecordModal"
                                                onclick="prepareEditForm('{{ $eventCategory->id }}')">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach

        @if(!$hasRecords)
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <img src="{{ asset('assets/images/no-data.svg') }}" alt="No Data" class="img-fluid mb-3" style="max-width: 200px;">
                    <h4>No Medal Records Found</h4>
                    <p class="text-muted">Medal records will appear here once they are added to the system.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Table View -->
    <div id="tableView" class="view-content" style="display: none;">
        <div class="card modern-table">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="medalTable">
                        <thead>
                            <tr>
                                <th>Sport</th>
                                <th>Event Category</th>
                                <th>Rank</th>
                                <th>Team</th>
                                <th>Medal Count</th>
                                <th>Medal Weight</th>
                                <th>Participants</th>
                                <th>Status</th>
                                @if($user->role != 'user')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $hasTableRecords = false;
                                $currentSport = null;
                                $sportRowspan = 0;
                                $eventRowspan = 0;
                            @endphp

                            @foreach($sports as $sport)
                                @php
                                    $sportHasRecords = false;
                                    $sportEventCount = 0;
                                    $totalRowsForSport = 0;

                                    // First, count how many rows this sport will need
                                    foreach($sport->eventCategories as $eventCategory) {
                                        $eventRecords = $medalRecords->where('event_category_id', $eventCategory->id);
                                        if($eventRecords->count() > 0) {
                                            $sportHasRecords = true;
                                            $sportEventCount++;

                                            // Count rows for this event (gold, silver, bronze winners)
                                            $bronzeWinnersCount = 0;
                                            foreach($eventRecords as $record) {
                                                if($record->bronze_tally > 0) {
                                                    $bronzeWinnersCount++;
                                                }
                                            }
                                            // Each event needs at least 3 rows (gold, silver, bronze)
                                            // If double bronze, it might need 4 rows
                                            $rowsForEvent = 2 + $bronzeWinnersCount; // gold + silver + bronze winners
                                            $totalRowsForSport += $rowsForEvent;
                                        }
                                    }
                                @endphp

                                @if($sportHasRecords)
                                    @php $hasTableRecords = true; @endphp

                                    <!-- Loop through event categories within the sport -->
                                    @php $eventCounter = 0; @endphp
                                    @foreach($sport->eventCategories as $eventCategory)
                                        @php
                                            // Get all medal records for this event category
                                            $eventRecords = $medalRecords->where('event_category_id', $eventCategory->id);

                                            // Skip if no records
                                            if($eventRecords->count() == 0) continue;

                                            $eventCounter++;

                                            // Find gold, silver, and bronze winners
                                            $goldWinner = $silverWinner = null;
                                            $bronzeWinners = [];
                                            $goldWeight = $silverWeight = $bronzeWeight = null;
                                            $goldTally = $silverTally = $bronzeTally = 0;
                                            $participants = 0;
                                            $isPosted = false;

                                            foreach ($eventRecords as $record) {
                                                $team = $teams->find($record->team_id);

                                                if ($record->gold_tally > 0) {
                                                    $goldWinner = $team;
                                                    $goldWeight = $record->gold_tally * $eventCategory->medalWeightCategory->gold_weight;
                                                    $goldTally = $record->gold_tally;
                                                }

                                                if ($record->silver_tally > 0) {
                                                    $silverWinner = $team;
                                                    $silverWeight = $record->silver_tally * $eventCategory->medalWeightCategory->silver_weight;
                                                    $silverTally = $record->silver_tally;
                                                }

                                                if ($record->bronze_tally > 0) {
                                                    $bronzeWinners[] = $team;
                                                    if($eventCategory->is_double_bronze){
                                                        $bronzeWeight = $record->bronze_tally * $eventCategory->medalWeightCategory->bronze_weight / 2;
                                                    } else {
                                                        $bronzeWeight = $record->bronze_tally * $eventCategory->medalWeightCategory->bronze_weight;
                                                    }
                                                    $bronzeTally = $record->bronze_tally;
                                                }

                                                // Track the total number of participants and posting status
                                                $participants += $record->number_of_participant;
                                                $isPosted = $record->is_posted;
                                            }

                                            $eventRowspan = 2 + count($bronzeWinners);
                                        @endphp

                                        <!-- Gold Winner Row -->
                                        <tr>
                                            @if($eventCounter === 1)
                                                <td rowspan="{{ $totalRowsForSport }}" class="align-middle">
                                                    <h5 class="m-0 fw-bold">{{ $sport->name }}</h5>
                                                </td>
                                            @endif

                                            <td rowspan="{{ $eventRowspan }}" class="align-middle">{{ $eventCategory->name }}</td>
                                            <td class="text-center">
                                                <span class="medal-badge medal-gold">Champion</span>
                                            </td>
                                            <td>{{ $goldWinner ? $goldWinner->name : '-' }}</td>
                                            <td>{{ $goldTally }}</td>
                                            <td>{{ $goldWeight }}</td>
                                            <td rowspan="{{ $eventRowspan }}" class="align-middle text-center">{{ $participants }}</td>
                                            <td rowspan="{{ $eventRowspan }}" class="align-middle text-center">
                                                <span class="status-badge {{ $isPosted ? 'status-posted' : 'status-pending' }}">
                                                    {{ $isPosted ? 'Posted' : 'Pending' }}
                                                </span>
                                            </td>
                                            @if($user->role != 'user')
                                                <td rowspan="{{ $eventRowspan }}" class="align-middle text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-floating"
                                                            data-bs-toggle="modal" data-bs-target="#editRecordModal"
                                                            onclick="prepareEditForm('{{ $eventCategory->id }}')">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>

                                        <!-- Silver Winner Row -->
                                        <tr>
                                            <td class="text-center">
                                                <span class="medal-badge medal-silver">1st Runner Up</span>
                                            </td>
                                            <td>{{ $silverWinner ? $silverWinner->name : '-' }}</td>
                                            <td>{{ $silverTally }}</td>
                                            <td>{{ $silverWeight }}</td>
                                        </tr>

                                        <!-- Bronze Winner Row(s) -->
                                        @if(count($bronzeWinners) > 0)
                                            @foreach($bronzeWinners as $index => $bronzeWinner)
                                                <tr>
                                                    <td class="text-center">
                                                        <span class="medal-badge medal-bronze">2nd Runner Up</span>
                                                    </td>
                                                    <td>{{ $bronzeWinner->name }}</td>
                                                    <td>{{ $bronzeTally }}</td>
                                                    <td>{{ $bronzeWeight }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center">
                                                    <span class="medal-badge medal-bronze">2nd Runner Up</span>
                                                </td>
                                                <td>-</td>
                                                <td>{{ $bronzeTally }}</td>
                                                <td>{{ $bronzeWeight }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            @if(!$hasTableRecords)
                                <tr>
                                    <td colspan="{{ $user->role != 'user' ? 9 : 8 }}" class="text-center py-5">
                                        <img src="{{ asset('assets/images/no-data.svg') }}" alt="No Data" class="img-fluid mb-3" style="max-width: 100px;">
                                        <h5>No medal records found</h5>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->role != 'user')
    <!-- Add Record Modal -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRecordModalLabel">Add Medal Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medal_records.store') }}" method="POST" onsubmit="disableSubmitButton(this)">
                        @csrf
                        <div class="mb-4">
                            <label for="event" class="form-label">Event Category</label>
                            <select name="event_category_id" class="form-select" id="event" required onchange="handleEventChange(this)">
                                <option value="">Select Event</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}"
                                        data-double-bronze="{{ $event->is_double_bronze }}"
                                        data-medal-count="{{ $event->medal_count }}">
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="medal-card">
                            <h6 class="section-title">Winners & Runners Up</h6>

                            <!-- Champion (Gold) Row -->
                            <div class="medal-row">
                                <div class="row w-100 align-items-center">
                                    <div class="col-4 text-end">
                                        <span class="medal-badge medal-gold">Champion</span>
                                    </div>
                                    <div class="col-8">
                                        <select name="champion_team_id" class="form-select" id="championTeam" required onchange="updateTeamSelections()">
                                            <option value="">Select Team</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 1st Runner Up (Silver) Row -->
                            <div class="medal-row">
                                <div class="row w-100 align-items-center">
                                    <div class="col-4 text-end">
                                        <span class="medal-badge medal-silver">1st Runner Up</span>
                                    </div>
                                    <div class="col-8">
                                        <select name="first_runner_up_team_id" class="form-select" id="firstRunnerUp" required onchange="updateTeamSelections()">
                                            <option value="">Select Team</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Runner Up (Bronze) Row -->
                            <div class="medal-row">
                                <div class="row w-100 align-items-center">
                                    <div class="col-4 text-end">
                                        <span class="medal-badge medal-bronze">2nd Runner Up</span>
                                    </div>
                                    <div class="col-8">
                                        <select name="second_runner_up_team_id" class="form-select" id="secondRunnerUp" required onchange="updateTeamSelections()">
                                            <option value="">Select Team</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Bronze Medal Row (Initially Hidden) -->
                            <div class="medal-row second-bronze-div" style="display: none;">
                                <div class="row w-100 align-items-center">
                                    <div class="col-4 text-end">
                                        <span class="medal-badge medal-bronze">2nd Runner Up (2)</span>
                                    </div>
                                    <div class="col-8">
                                        <select name="second_runner_up_team_id_2" class="form-select" id="secondRunnerUp2" onchange="updateTeamSelections()">
                                            <option value="">Select Team</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-divider"></div>

                        <div class="additional-fields">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="medalCount" class="form-label">Medal Count</label>
                                    <input type="number" name="medal_count" class="form-control" id="medalCount" readonly>
                                    <div class="text-hint">Determined by event category</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="medalWeight" class="form-label">Number of Participants</label>
                                    <input type="number" name="number_of_participant" class="form-control" id="medalWeight" value="1" min="1" required>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="is_double_bronze" id="isDoubleBronze" value="0">

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for handling team selections and double bronze -->
    <script>
        function updateEventCategoryOptions() {
            const selectElement = document.getElementById('event');

            // Get all event categories that already have results
            // This will be populated from your Blade template using the same logic
            const eventsWithRecords = [];

            @foreach($sports as $sport)
                @foreach($sport->eventCategories as $eventCategory)
                    @php
                        $eventRecords = $medalRecords->where('event_category_id', $eventCategory->id);
                    @endphp

                    @if($eventRecords->count() > 0)
                        eventsWithRecords.push({{ $eventCategory->id }});
                    @endif
                @endforeach
            @endforeach

            // Remove or disable options for events that already have results
            for (let i = selectElement.options.length - 1; i >= 0; i--) {
                const optionValue = parseInt(selectElement.options[i].value);
                if (optionValue && eventsWithRecords.includes(optionValue)) {
                    // Option 1: Remove the option completely
                    // selectElement.remove(i);

                    // Option 2: Disable the option and indicate why
                    selectElement.options[i].disabled = true;
                    selectElement.options[i].text = selectElement.options[i].text + ' (Results Exist)';
                }
            }
        }

        document.getElementById('addRecordModal').addEventListener('shown.bs.modal', function() {
            resetTeamSelections();
            document.getElementById('event').selectedIndex = 0;
            document.getElementById('medalCount').value = '';
            document.querySelector('.second-bronze-div').style.display = 'none';
            document.getElementById('secondRunnerUp2').removeAttribute('required');

            // Add this line to update event category options
            updateEventCategoryOptions();
        });

        function handleEventChange(selectElement) {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const isDoubleBronze = selectedOption.getAttribute('data-double-bronze') === '1';
            const medalCount = selectedOption.getAttribute('data-medal-count');

            // Set medal count from event category
            document.getElementById('medalCount').value = medalCount || 1;

            // Handle double bronze settings
            document.getElementById('isDoubleBronze').value = isDoubleBronze ? '1' : '0';
            const secondBronzeDiv = document.querySelector('.second-bronze-div');

            if (isDoubleBronze) {
                secondBronzeDiv.style.display = 'block';
                document.getElementById('secondRunnerUp2').setAttribute('required', 'required');
            } else {
                secondBronzeDiv.style.display = 'none';
                document.getElementById('secondRunnerUp2').removeAttribute('required');
                document.getElementById('secondRunnerUp2').value = '';
            }

            // Reset team selections when event changes
            resetTeamSelections();
        }

        function updateTeamSelections() {
            const selects = [
                document.getElementById('championTeam'),
                document.getElementById('firstRunnerUp'),
                document.getElementById('secondRunnerUp'),
                document.getElementById('secondRunnerUp2')
            ];

            const selectedValues = selects.map(select => select.value).filter(value => value !== '');

            // For each select box
            selects.forEach(currentSelect => {
                // For each option in current select box
                Array.from(currentSelect.options).forEach(option => {
                    if (option.value === '') return; // Skip empty option

                    // If this option is selected in a different dropdown, disable it
                    const isSelectedElsewhere = selectedValues.includes(option.value) &&
                                               option.value !== currentSelect.value;

                    option.disabled = isSelectedElsewhere;
                });
            });
        }

        function resetTeamSelections() {
            const selects = [
                document.getElementById('championTeam'),
                document.getElementById('firstRunnerUp'),
                document.getElementById('secondRunnerUp'),
                document.getElementById('secondRunnerUp2')
            ];

            selects.forEach(select => {
                select.value = '';
                Array.from(select.options).forEach(option => {
                    option.disabled = false;
                });
            });
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Setup the modal reset behavior
            document.getElementById('addRecordModal').addEventListener('shown.bs.modal', function() {
                resetTeamSelections();
                document.getElementById('event').selectedIndex = 0;
                document.getElementById('medalCount').value = '';
                document.querySelector('.second-bronze-div').style.display = 'none';
                document.getElementById('secondRunnerUp2').removeAttribute('required');
            });
        });

        function disableSubmitButton(form) {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerText = 'Submitting...';
        }
    </script>
@endif

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])
@endsection

@section('script-bottom')
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
@vite(['resources/js/pages/crm-dashboard.init.js'])
<script>
    function prepareEditForm(eventCategoryId) {
        // Your edit form preparation code goes here
    }

    function toggleView(viewType) {
    // Get view elements
    const cardView = document.getElementById('cardView');
    const tableView = document.getElementById('tableView');

    // Get tab elements
    const cardTab = document.querySelector('.view-tab.card-view');
    const tableTab = document.querySelector('.view-tab.table-view');

    // Toggle view display
    if (viewType === 'card') {
        cardView.style.display = 'block';
        tableView.style.display = 'none';
        cardTab.classList.add('active');
        tableTab.classList.remove('active');
    } else if (viewType === 'table') {
        cardView.style.display = 'none';
        tableView.style.display = 'block';
        cardTab.classList.remove('active');
        tableTab.classList.add('active');
    }

    // Store the preference in localStorage so it persists across page loads (optional)
    localStorage.setItem('preferredView', viewType);
}

// Add this script to the script section at the bottom of the page
function filterSport(sportId) {
    // Update the dropdown button text
    const dropdownButton = document.getElementById('sportFilterDropdown');
    const sportName = sportId === 'all' ? 'All Sports' : document.querySelector(`.dropdown-item[onclick="filterSport('${sportId}')"]`).textContent;
    dropdownButton.innerHTML = `<i class="mdi mdi-filter-outline me-1"></i> ${sportName}`;

    // Update active class in dropdown menu
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.classList.remove('active');
    });
    document.querySelector(sportId === 'all' ? '.dropdown-item[onclick="filterSport(\'all\')"]' : `.dropdown-item[onclick="filterSport('${sportId}')"]`).classList.add('active');

    // Show/hide reset button
    document.getElementById('resetFilterBtn').style.display = sportId === 'all' ? 'none' : 'block';

    // Card View Filtering - FIXED VERSION
    const cardView = document.getElementById('cardView');
    const sportSections = cardView.querySelectorAll('.sport-header');
    let anyVisibleSport = sportId === 'all'; // If "all", we know some will be visible

    sportSections.forEach(header => {
        // Get the sport name from the header
        const sportTitle = header.querySelector('h4').textContent.trim();
        const shouldShow = sportId === 'all' || (sportId !== 'all' && sportTitle === document.querySelector(`.dropdown-item[onclick="filterSport('${sportId}')"]`).textContent.trim());

        // Get all related elements: the header itself and the row of cards that follows it
        const cardRow = header.nextElementSibling;

        if (shouldShow) {
            header.style.display = 'block';
            cardRow.style.display = 'flex';
            anyVisibleSport = true;
        } else {
            header.style.display = 'none';
            cardRow.style.display = 'none';
        }
    });

    // Handle "no data" message in card view
    const noDataCard = cardView.querySelector('.card.shadow-sm');
    if (!anyVisibleSport && !noDataCard) {
        // Create a "no data" card if there's no visible sport
        const noDataElement = document.createElement('div');
        noDataElement.className = 'card shadow-sm no-data-filtered';
        noDataElement.innerHTML = `
            <div class="card-body text-center p-5">
                <img src="${document.querySelector('img[alt="No Data"]')?.src || '/assets/images/no-data.svg'}" alt="No Data" class="img-fluid mb-3" style="max-width: 200px;">
                <h4>No Medal Records Found for this Filter</h4>
                <p class="text-muted">Try a different sport filter or reset filters to view all records.</p>
            </div>
        `;
        cardView.appendChild(noDataElement);
    } else if (anyVisibleSport) {
        // Remove the "no data" card if we have visible sports
        const noDataFiltered = cardView.querySelector('.no-data-filtered');
        if (noDataFiltered) {
            noDataFiltered.remove();
        }
    }

    // Table View Filtering - Fixed version
    const table = document.getElementById('medalTable');
    const tbody = table.querySelector('tbody');
    const sportText = sportId === 'all' ? 'all' : document.querySelector(`.dropdown-item[onclick="filterSport('${sportId}')"]`).textContent.trim();

    // Store all sport sections to show/hide them together
    let tableSportSections = [];
    let currentSportRows = [];
    let currentSportName = '';

    // First pass: identify sport sections and group rows
    Array.from(tbody.rows).forEach(row => {
        const sportCell = row.querySelector('td:first-child h5');

        if (sportCell) {
            // Found a new sport section
            if (currentSportRows.length > 0) {
                // Save previous sport section
                tableSportSections.push({
                    name: currentSportName,
                    rows: [...currentSportRows]
                });
            }

            // Start new sport section
            currentSportName = sportCell.textContent.trim();
            currentSportRows = [row];
        } else {
            // Add to current sport section
            if (currentSportRows.length > 0) {
                currentSportRows.push(row);
            }
        }
    });

    // Add the last sport section if any
    if (currentSportRows.length > 0) {
        tableSportSections.push({
            name: currentSportName,
            rows: [...currentSportRows]
        });
    }

    // Second pass: show/hide sport sections
    tableSportSections.forEach(section => {
        const visible = sportId === 'all' || section.name === sportText;

        section.rows.forEach(row => {
            row.style.display = visible ? '' : 'none';
        });
    });

    // Show "no data" row if all sport sections are hidden
    const hasVisibleRows = Array.from(tbody.rows).some(row => row.style.display !== 'none');

    // Check if we already have a "no data found" row
    let noDataRow = Array.from(tbody.rows).find(row => {
        const cols = row.querySelectorAll('td');
        return cols.length === 1 && cols[0].textContent.includes('No medal records found');
    });

    if (!hasVisibleRows && !noDataRow) {
        // Create and append a "no data" row
        const colSpan = table.rows[0].cells.length;
        const newRow = tbody.insertRow();
        const cell = newRow.insertCell();
        cell.colSpan = colSpan;
        cell.className = 'text-center py-5';
        cell.innerHTML = `
            <img src="${document.querySelector('img[alt="No Data"]')?.src || '/assets/images/no-data.svg'}" alt="No Data" class="img-fluid mb-3" style="max-width: 100px;">
            <h5>No medal records found for this filter</h5>
        `;
    } else if (hasVisibleRows && noDataRow) {
        // Remove the "no data" row if we have visible rows
        noDataRow.remove();
    }
}

function resetFilter() {
    filterSport('all');
}
</script>
@endsection

@extends('layouts.admin', ['title' => 'Results Tally'])



@section('content')
<style>
    /* Fixed position export button */
    .export-btn {
        position: fixed;
        bottom: 30px;
        left: 5px;
        z-index: 2;
        padding: 10px 20px;
        background-color: #00491e; /* Use your primary color */
        color: white;
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        font-weight: 500;
        font-size: 16px;
    }
    .export-btn:hover {
        background-color: #02681e; /* Slightly lighter shade for hover */
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.2);
    }
    .export-btn:disabled {
        background-color: #6c757d;
        cursor: wait;
        transform: none;
    }

    /* Improved progress dialog */
    #exportProgress {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 30px rgba(0,0,0,0.3);
        display: none;
        z-index: 1002;
        width: 400px;
        text-align: center;
    }
    .progress-bar {
        width: 100%;
        height: 20px;
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
        margin: 20px 0 15px;
    }
    .progress-fill {
        height: 100%;
        background-color: #00491e;
        transition: width 1s ease; /* Slower transition */
    }
    .export-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1001;
        display: none;
        cursor: wait;
    }
    #progressText {
        margin-bottom: 0;
        font-size: 14px;
        color: #6c757d;
    }
</style>
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

    <div class="py-4 d-flex align-items-sm-start flex-sm-column flex-column">
        <div class="flex-grow-1">
            <h3 class="fw-bold mb-0">Results Tally</h3>
            <p class="text-muted">Track medal performances across all events</p>
        </div>
        <div class="d-flex align-items-center gap-3 flex-col">
        @if(!$sportId)
            <div class="d-flex align-items-center gap-3 flex-column">

            <!-- Add search input -->
       
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search events..." oninput="searchEvents()">
                        <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                            <i class="mdi mdi-close"></i>
                        </button>
                    </div>
       
               <div class="d-flex gap-2">
               <button id="resetFilterBtn" class="btn btn-light" onclick="resetFilter()" style="display: none;">
                    <i class="mdi mdi-refresh me-1"></i>
                </button>
                <!-- Add this right after the sport filter dropdown, before the close of the d-flex div -->
                <div class="dropdown ms-2">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="postedFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-check-circle-outline me-1"></i> Filter by Status
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="postedFilterDropdown">
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="posted-checkbox" value="all" onchange="filterByPosted()"> All Status
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="posted-checkbox" value="posted" onchange="filterByPosted()"> Posted
                            </label>
                        </li>
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="posted-checkbox" value="pending" onchange="filterByPosted()"> Pending
                            </label>
                        </li>
                        <li>
                            
                        </li>
                    </ul>
                </div>
                <!-- Reset Filter Button (Initially Hidden) -->

                <!-- Sport Filter Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sportFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-filter-outline me-1"></i> Filter by Sport
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="sportFilterDropdown">
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="sport-checkbox" value="all" onchange="filterSport()"> All Sports
                            </label>
                        </li>
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
                                <li>
                                    <label class="dropdown-item">
                                        <input type="checkbox" class="sport-checkbox" value="{{ $sport->id }}" onchange="filterSport()">
                                        {{ $sport->name }}
                                    </label>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>

                @if($user->role != 'user' || $user->role == 'tournament_director')
                <button type="button" class="btn btn-primary custom-primary-background" data-bs-toggle="modal" data-bs-target="#addRecordModal">
                    <i class="mdi mdi-plus me-1"></i> <span class="d-none d-lg-block d-xl-none">Add Record</span> 
                </button>
                @endif
               </div>

         

          


            </div>
        @else
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="eventCategoryFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-filter-outline me-1" ></i> Filter by Event Category
            </button>
            <ul class="dropdown-menu" aria-labelledby="eventCategoryFilterDropdown">
                <li>
                    <label class="dropdown-item">
                        <input type="checkbox" class="event-category-checkbox" value="all" onchange="filterCatRecords()"> All Categories
                    </label>
                </li>
                @foreach($eventCategories as $eventCategory)
                    @if($medalRecords->where('event_category_id', $eventCategory->id)->count() > 0)
                        <li>
                            <label class="dropdown-item">
                                <input type="checkbox" class="event-category-checkbox" value="{{ $eventCategory->id }}" onchange="filterCatRecords()">
                                {{ $eventCategory->name }}
                            </label>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>


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
                <div class="sport-header mb-4 d-flex justify-content-between align-items-center" data-sport-id="{{ $sport->id }}">
                    <h4 class="fs-16 m-0">{{ $sport->name }}</h4>
                    @if($user->role == 'admin' || $user->role == 'tournament_director')
                        @php
                            $hasUnapprovedRecords = \App\Models\MedalRecord::whereHas('eventCategory', function ($query) use ($sport) {
                                $query->where('sport_id', $sport->id);
                            })->where('is_posted', false)->exists();
                        @endphp

                        @if($hasUnapprovedRecords)
                            <button class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#approveAllRecordModal"
                                    title="Approve all records"
                                    onclick="setEventCategory('{{ $sport->id }}', 'approvedAll')">
                                Approve All
                            </button>
                        @endif
                    @endif
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
                                        $bronzeWeight = $record->bronze_tally * ($eventCategory->medalWeightCategory->bronze_weight/2);
                                    } else {
                                        $bronzeWeight = $record->bronze_tally * $eventCategory->medalWeightCategory->bronze_weight;
                                    }
                                    $bronzeTally = $record->bronze_tally;
                                }

                                // Track the total number of participants and posting status
                                $participants = $record->number_of_participant;
                                $isPosted = $record->is_posted;
                            }
                        @endphp

                        <div class="col">
                            <div class="card medal-card h-100">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0" data-event-category-id="{{ $eventCategory->id }}">{{ $eventCategory->name }}</h5>
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
                                                    <span class="team-name">{{ $goldWinner ? $goldWinner->name : '-' }}</span>
                                                </div>
                                                <div class="medal-stats">
                                                    <div class="medal-stat-item">
                                                        <span class="medal-stat-value" id="gold-count-value">{{ $goldTally }}</span>
                                                        <span class="medal-stat-label">Count</span>
                                                    </div>
                                                    <div class="medal-stat-item">
                                                        <span class="medal-stat-value" id="gold-weight-value">{{ $goldWeight }}</span>
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
                                                        <span class="medal-stat-value" id="silver-count-value">{{ $silverTally }}</span>
                                                        <span class="medal-stat-label">Count</span>
                                                    </div>
                                                    <div class="medal-stat-item">
                                                        <span class="medal-stat-value" id="silver-weight-value">{{ $silverWeight }}</span>
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
                                                                <span class="medal-stat-value bronze-count-value">{{ $bronzeTally }}</span>
                                                                <span class="medal-stat-label">Count</span>
                                                            </div>
                                                            <div class="medal-stat-item">
                                                                <span class="medal-stat-value bronze-weight-value">{{ $bronzeWeight }}</span>
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
                                                            <span class="medal-stat-value bronze-count-value">{{ $bronzeTally }}</span>
                                                            <span class="medal-stat-label">Count</span>
                                                        </div>
                                                        <div class="medal-stat-item">
                                                            <span class="medal-stat-value bronze-weight-value">{{ $bronzeWeight }}</span>
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
                                        <i class="mdi mdi-account-group me-1"></i> {{ $participants }} Participating Teams
                                    </div>

                                    <div class="d-flex align-items-center gap-1">
                                        @if(!$isPosted)
                                            @if($user->id == $record->posted_by && !$isPosted)
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary btn-floating"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRecordModal"
                                                    onclick="prepareEditForm('{{ $eventCategory->id }}')"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Edit record">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>


                                            @endif

                                            @if($user->role == 'admin' || $user->role == 'tournament_director')
                                                <!-- Approve Button -->
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success btn-floating"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#approveRecordModal"
                                                        {{ $isPosted ? 'disabled' : '' }}
                                                        onclick="setEventCategory('{{ $eventCategory->id }}', 'approve')"
                                                        title="Approve this record">
                                                    <i class="mdi mdi-check"></i>
                                                </button>

                                            @endif
                                        @endif

                                        @if($user->role == 'admin')
                                            <!-- Delete Button -->
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-floating"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteRecordModal"
                                                onclick="setEventCategory('{{ $eventCategory->id }}', 'delete')"
                                                title="Delete this record">
                                                <i class="mdi mdi-delete"></i>
                                            </button>

                                        @endif
                                    </div>


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



<!--Approve Modal-->
<div class="modal fade" id="approveRecordModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Warning:</strong> Once approved, this record cannot be edited.</p>
                <p>Are you sure you want to approve this record?</p>
                <div class="d-flex gap-2 justify-content-end">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="approveForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_posted" value="1">
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!--Approve ALL Modal-->
<div class="modal fade" id="approveAllRecordModal" tabindex="-1" aria-labelledby="approveAllRecordModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve All Records</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Warning:</strong> Once approved, this record cannot be edited.</p>
                <p>Are you sure you want to approve this record?</p>
                <div class="d-flex gap-2 justify-content-end">
                    <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <form id="approveAllForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_posted" value="1">
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--delete modal-->
<div class="modal fade" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Record?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    </form>
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
                    <h5 class="modal-title" id="addRecordModalLabel">Add Result</h5>
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

                        <!-- Added Team Reuse Toggle -->
                        <div class="custom-toggle">
                            <div class="form-check form-switch">
                                <input name="allowTeamReuse" class="form-check-input" type="checkbox" id="allowTeamReuse" onchange="updateTeamSelections()">
                                <label class="form-check-label" for="allowTeamReuse">Allow team to win multiple medals</label>
                                <p style="padding-left: 22px;"><small>Use this for sports that allow the same team to place in multiple positions</small></p>
                            </div>
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
                                    <label for="medalWeight" class="form-label">Number of Participating Teams</label>
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
                    selectElement.remove(i);

                    // Option 2: Disable the option and indicate why
                    // selectElement.options[i].disabled = true;
                    // selectElement.options[i].text = selectElement.options[i].text + ' (Results Exist)';
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

        function updateTeamSelections(isEditModal = false) {
            const prefix = isEditModal ? 'edit_' : '';
            const selects = [
                document.getElementById(prefix + 'championTeam'),
                document.getElementById(prefix + 'firstRunnerUp'),
                document.getElementById(prefix + 'secondRunnerUp'),
                document.getElementById(prefix + 'secondRunnerUp2')
            ];

            const allowTeamReuse = document.getElementById(prefix + 'allowTeamReuse').checked;
            console.log(allowTeamReuse)
            document.getElementById(prefix + 'allowTeamReuse').value = allowTeamReuse ? '1' : '0';

            if (allowTeamReuse) {
                // Enable all options when reuse is allowed
                selects.forEach(select => {
                    Array.from(select.options).forEach(option => option.disabled = false);
                });
                return;
            }

            const selectedValues = new Set(
                selects.map(select => select.value).filter(value => value !== '')
            );

            selects.forEach(currentSelect => {
                Array.from(currentSelect.options).forEach(option => {
                    if (option.value === '') return; // Skip empty option

                    // Disable if the option is selected in another dropdown
                    const isSelectedElsewhere = selectedValues.has(option.value) && option.value !== currentSelect.value;
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

<div class="modal fade" id="editRecordModal" tabindex="-1" aria-labelledby="editRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRecordModalLabel">Edit Medal Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="editRecordForm" onsubmit="disableSubmitButton(this)">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="event_category_id" id="edit_event_category_id">
                    <div class="mb-4">
                        <label for="edit_event" class="form-label">Event Category</label>
                        <select name="event_category_id" class="form-select" id="edit_event" disabled>
                            <option value="">Select Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id }}"
                                    data-double-bronze="{{ $event->is_double_bronze }}"
                                    data-medal-count="{{ $event->medal_count }}">
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Event cannot be changed once records exist</small>
                    </div>

                    <!-- Added Team Reuse Toggle -->
                    <div class="custom-toggle">
                        <div class="form-check form-switch">
                            <input name="allowTeamReuse" class="form-check-input" type="checkbox" id="edit_allowTeamReuse" onchange="updateTeamSelections(true)">
                            <label class="form-check-label" for="allowTeamReuse">Allow team to win multiple medals</label>
                            <p style="padding-left: 22px;"><small>Use this for sports that allow the same team to place in multiple positions</small></p>
                        </div>
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
                                    <select name="champion_team_id" class="form-select" id="edit_championTeam" required onchange="updateEditTeamSelections()">
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
                                    <select name="first_runner_up_team_id" class="form-select" id="edit_firstRunnerUp" required onchange="updateEditTeamSelections()">
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
                                    <select name="second_runner_up_team_id" class="form-select" id="edit_secondRunnerUp" required onchange="updateEditTeamSelections()">
                                        <option value="">Select Team</option>
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->id }}">{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- 2nd Bronze Medal Row (Initially Hidden) -->
                        <div class="medal-row edit-second-bronze-div" style="display: none;">
                            <div class="row w-100 align-items-center">
                                <div class="col-4 text-end">
                                    <span class="medal-badge medal-bronze">2nd Runner Up (2)</span>
                                </div>
                                <div class="col-8">
                                    <select name="second_runner_up_team_id_2" class="form-select" id="edit_secondRunnerUp2" onchange="updateEditTeamSelections()">
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
                                <label for="edit_medalCount" class="form-label">Medal Count</label>
                                <input type="number" name="medal_count" class="form-control" id="edit_medalCount" readonly>
                                <div class="text-hint">Determined by event category</div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_participants" class="form-label">Number of Participating Teams</label>
                                <input type="number" name="number_of_participant" class="form-control" id="edit_participants" min="1" required>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="is_double_bronze" id="edit_isDoubleBronze" value="0">

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Add this button to the header section near the top of your page, right after the d-flex align-items-center gap-3 div -->
<button id="exportPDF" class="export-btn">
    <i class="mdi mdi-file-pdf-box me-1"></i> <span class="d-none d-md-block">Export PDF</span>
</button>

<!-- Progress Overlay and Dialog (Keep these) -->
<div id="exportOverlay" class="export-overlay"></div>
<div id="exportProgress">
    <h4>Generating PDF Report...</h4>
    <div class="progress-bar">
        <div class="progress-fill" style="width: 0%"></div>
    </div>
    <p id="progressText">Preparing document...</p>
</div>


<!-- Add progress overlay and bar -->
<div id="exportOverlay" class="export-overlay"></div>
<div id="exportProgress">
    <h4>Generating PDF Report...</h4>
    <div class="progress-bar">
        <div class="progress-fill" style="width: 0%"></div>
    </div>
    <p id="progressText">Preparing document...</p>
</div>

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])
@endsection

@section('script-bottom')
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

@vite(['resources/js/pages/crm-dashboard.init.js'])
<!-- Add this JavaScript function to prepare the edit form -->

<script>

    // Add Excel Export Button
document.addEventListener('DOMContentLoaded', function() {
    // Create Excel export button
    const exportBtnContainer = document.createElement('div');
    exportBtnContainer.style.position = 'fixed';
    exportBtnContainer.style.bottom = '55px'; // Position much higher to avoid any existing buttons
    exportBtnContainer.style.left = '0px';
    exportBtnContainer.style.zIndex = '2';

    const exportExcelBtn = document.createElement('button');
    exportExcelBtn.id = 'exportExcel';
    exportExcelBtn.style.position = 'relative';
    exportExcelBtn.className = 'export-btn';
    exportExcelBtn.innerHTML = '<i class="mdi mdi-microsoft-excel me-1"></i> <span class="d-none d-md-block ">Export Excel</span>';
    exportExcelBtn.style.backgroundColor = '#217346'; // Excel green color

    // Don't modify other buttons, just position this one higher
    exportBtnContainer.appendChild(exportExcelBtn);
    document.body.appendChild(exportBtnContainer);

    // Add event listener to Excel button
    exportExcelBtn.addEventListener('click', function() {
        // Show overlay and progress bar
        const exportOverlay = document.getElementById('exportOverlay');
        const progressBar = document.getElementById('exportProgress');
        const progressFill = document.querySelector('.progress-fill');
        const progressText = document.getElementById('progressText');

        exportOverlay.style.display = 'block';
        progressBar.style.display = 'block';
        exportExcelBtn.disabled = true;
        progressFill.style.width = '30%';
        progressText.textContent = 'Collecting medal data...';

        setTimeout(() => {
            try {
                // Check if SheetJS library is available
                if (typeof XLSX === 'undefined') {
                    throw new Error('Excel library not loaded. Please refresh the page and try again.');
                }

                // Process medal data from the page - check for visible elements only
                // Group data by sport
                const sportGroups = {};
                const sportHeaders = document.querySelectorAll('.sport-header:not([style*="display: none"])');

                // Check if we have any visible sports
                if (sportHeaders.length === 0) {
                    throw new Error('No visible data to export. Please adjust your filters to show some data first.');
                }

                // First collect sport and event data
                sportHeaders.forEach(header => {
                    // Safely extract sport name
                    const sportNameEl = header.querySelector('h4');
                    if (!sportNameEl) return;

                    const sportName = sportNameEl.textContent.trim();
                    sportGroups[sportName] = [];

                    const cardContainer = header.nextElementSibling;
                    if (!cardContainer) return;

                    const medalCards = cardContainer.querySelectorAll('.medal-card');

                    medalCards.forEach(card => {
                        // Skip card if parent col is hidden
                        const cardCol = card.closest('.col');
                        if (cardCol && cardCol.style.display === 'none') return;

                        // Safely extract data with optional chaining
                        const cardTitle = card.querySelector('.card-title');
                        if (!cardTitle) return;

                        const eventName = cardTitle.textContent.trim();
                        const statusBadge = card.querySelector('.status-badge');
                        const status = statusBadge ? statusBadge.textContent.trim() : 'Unknown';

                        // Get gold winner
                        let goldTeam = '-', goldCount = '0', goldWeight = '0';
                        const goldBadge = card.querySelector('.medal-badge.medal-gold');
                        if (goldBadge) {
                            const goldPosition = goldBadge.closest('.medal-position');
                            if (goldPosition) {
                                const teamEl = goldPosition.querySelector('.team-name');
                                if (teamEl) goldTeam = teamEl.textContent.trim();

                                const countEl = goldPosition.querySelector('#gold-count-value');
                                if (countEl) goldCount = countEl.textContent.trim();

                                const weightEl = goldPosition.querySelector('#gold-weight-value');
                                if (weightEl) goldWeight = weightEl.textContent.trim();
                            }
                        }

                        // Get silver winner
                        let silverTeam = '-', silverCount = '0', silverWeight = '0';
                        const silverBadge = card.querySelector('.medal-badge.medal-silver');
                        if (silverBadge) {
                            const silverPosition = silverBadge.closest('.medal-position');
                            if (silverPosition) {
                                const teamEl = silverPosition.querySelector('.team-name');
                                if (teamEl) silverTeam = teamEl.textContent.trim();

                                const countEl = silverPosition.querySelector('#silver-count-value');
                                if (countEl) silverCount = countEl.textContent.trim();

                                const weightEl = silverPosition.querySelector('#silver-weight-value');
                                if (weightEl) silverWeight = weightEl.textContent.trim();
                            }
                        }

                        // Get bronze winner(s)
                        let bronzeTeams = [], bronzeCount = '0', bronzeWeight = '0';
                        const bronzeBadge = card.querySelector('.medal-badge.medal-bronze');
                        if (bronzeBadge) {
                            const bronzePosition = bronzeBadge.closest('.medal-position');
                            if (bronzePosition) {
                                const bronzeTeamElements = bronzePosition.querySelectorAll('.team-name');
                                if (bronzeTeamElements && bronzeTeamElements.length > 0) {
                                    bronzeTeams = Array.from(bronzeTeamElements)
                                        .map(el => el.textContent.trim())
                                        .filter(text => text !== '-');
                                }

                                const countEl = bronzePosition.querySelector('.bronze-count-value');
                                    if (countEl) bronzeCount = countEl.textContent.trim();

                                const weightEl = bronzePosition.querySelector('.bronze-weight-value');
                                if (weightEl) bronzeWeight = weightEl.textContent.trim();
                            }
                        }

                        // Combine bronze teams into a single string
                        const bronzeTeamsStr = bronzeTeams.length > 0 ? bronzeTeams.join(', ') : '-';

                        // Get participants count
                        let participants = '0';
                        const participantsEl = card.querySelector('.card-footer .text-muted');
                        if (participantsEl) {
                            const participantsText = participantsEl.textContent.trim();
                            const participantsMatch = participantsText.match(/(\d+)/);
                            if (participantsMatch) participants = participantsMatch[1];
                        }

                        // Store event data in sport group
                        sportGroups[sportName].push({
                            eventName,
                            status,
                            goldTeam,
                            goldCount,
                            goldWeight,
                            silverTeam,
                            silverCount,
                            silverWeight,
                            bronzeTeamsStr,
                            bronzeCount,
                            bronzeWeight,
                            participants
                        });
                    });
                });

                // Ensure we have data to export
                let hasData = false;
                for (const sport in sportGroups) {
                    if (sportGroups[sport].length > 0) {
                        hasData = true;
                        break;
                    }
                }

                if (!hasData) {
                    throw new Error('No medal data found. Please make sure some data is visible.');
                }

                progressFill.style.width = '60%';
                progressText.textContent = 'Creating Excel workbook...';

                // Create a new workbook
                const wb = XLSX.utils.book_new();

                // Create a summary sheet with all data in one organized table
                const summaryData = [];

                // Add header row
                summaryData.push([
                    'Results Tally Summary Report',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
                ]);

                summaryData.push([
                    'Generated on:',
                    new Date().toLocaleString(),
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    ''
                ]);

                summaryData.push([]);  // Empty row for spacing

                // Add table headers
                summaryData.push([
                    'Sport',
                    'Event',
                    'Status',
                    'Champion',
                    'Count',
                    'Weight',
                    '1st Runner Up',
                    'Count',
                    'Weight',
                    '2nd Runner Up',
                    'Count',
                    'Weight',
                    'Teams'
                ]);

                // Add data rows
                for (const sportName in sportGroups) {
                    if (sportGroups[sportName].length === 0) continue;

                    // Add sport name as a section header
                    summaryData.push([
                        sportName,
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
                        ''
                    ]);

                    // Add event data rows
                    sportGroups[sportName].forEach(event => {
                        summaryData.push([
                            '',  // Indent under sport
                            event.eventName,
                            event.status,
                            event.goldTeam,
                            event.goldCount,
                            event.goldWeight,
                            event.silverTeam,
                            event.silverCount,
                            event.silverWeight,
                            event.bronzeTeamsStr,
                            event.bronzeCount,
                            event.bronzeWeight,
                            event.participants
                        ]);
                    });

                    // Add empty row between sports
                    summaryData.push([]);
                }

                // Create worksheet from aoa (array of arrays)
                const ws = XLSX.utils.aoa_to_sheet(summaryData);

                // Add the worksheet to the workbook
                XLSX.utils.book_append_sheet(wb, ws, 'Results Tally');

                // Style the worksheet - set column widths
                ws['!cols'] = [
                    { wch: 20 },  // Sport
                    { wch: 25 },  // Event
                    { wch: 10 },  // Status
                    { wch: 20 },  // Gold Team
                    { wch: 8 },   // Gold Count
                    { wch: 8 },   // Gold Weight
                    { wch: 20 },  // Silver Team
                    { wch: 8 },   // Silver Count
                    { wch: 8 },   // Silver Weight
                    { wch: 25 },  // Bronze Teams
                    { wch: 8 },   // Bronze Count
                    { wch: 8 },   // Bronze Weight
                    { wch: 10 }   // Teams
                ];

                // Format current date for filename
                 // Format the current date
                 const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const year = now.getFullYear();
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const monthName = monthNames[now.getMonth()];
                const hours12 = now.getHours() % 12 || 12;
                const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                const formattedHours = String(hours12).padStart(2, '0');
                const formattedMinutes = String(now.getMinutes()).padStart(2, '0');
                const formattedDateTime = `${day}-${monthName}-${year} ${formattedHours}:${formattedMinutes} ${ampm}`;


                progressFill.style.width = '90%';
                progressText.textContent = 'Finalizing Excel file...';

                // Apply cell styling for better readability
                // Get the range of cells
                const range = XLSX.utils.decode_range(ws['!ref']);

                // Create cell style objects
                const headerStyle = { font: { bold: true }, fill: { fgColor: { rgb: "00217346" }, patternType: "solid" }, alignment: { horizontal: "center" } };
                const sportHeaderStyle = { font: { bold: true }, fill: { fgColor: { rgb: "00E2EFDA" }, patternType: "solid" } };
                const dataRowStyle = { alignment: { horizontal: "left" } };

                // Define merged cell ranges
                ws['!merges'] = [
                    // Merge title cell across all columns
                    { s: { r: 0, c: 0 }, e: { r: 0, c: 12 } },
                    // Merge date cell
                    { s: { r: 1, c: 0 }, e: { r: 1, c: 1 } },
                    { s: { r: 1, c: 2 }, e: { r: 1, c: 12 } }
                ];

                // Apply some basic styling (SheetJS doesn't have full styling support in the free version)
                // But we can at least add some cell properties for Excel to interpret
                // Format the title row - although limited in the community version
                ws.A1 = { t: 's', v: 'Results Tally Summary Report' };


                // Generate filename with date
                const filename = `Medal_Tally_Report_${formattedDateTime}.xlsx`;

                // Create download
                XLSX.writeFile(wb, filename);

                progressFill.style.width = '100%';
                progressText.textContent = 'Download complete!';

            } catch (error) {
                console.error('Error generating Excel:', error);
                alert(error.message || 'Error generating Excel file. Please try again.');
            } finally {
                // Hide overlay and progress after a short delay
                setTimeout(() => {
                    exportOverlay.style.display = 'none';
                    progressBar.style.display = 'none';
                    exportExcelBtn.disabled = false;
                }, 1000);
            }
        }, 500);
    });
});
</script>

<script>
function setEventCategory(eventCategoryId, actionType) {
    if (actionType === 'approve') {
        document.getElementById('approveForm').action = `/admin/medal-records/${eventCategoryId}/approve`;
    } else if (actionType === 'delete') {
        document.getElementById('deleteForm').action = `/admin/medal-records/${eventCategoryId}`;
    } else if ( actionType === 'approvedAll') {
        document.getElementById('approveAllForm').action = `/admin/medal-records/${eventCategoryId}/approve/all`;
    }
}



// Initialize tooltips
document.addEventListener('DOMContentLoaded', () => {
    // Load filters from localStorage
    loadFiltersFromStorage();

    // Apply initial filters
    applyAllFilters();
});


document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

    // Store event data globally for easy access
let eventCategoriesData = {};

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Prepare event data for quick lookup
    @foreach($events as $event)
        eventCategoriesData[{{ $event->id }}] = {
            name: "{{ $event->name }}",
            isDoubleBronze: {{ $event->is_double_bronze ? 'true' : 'false' }},
            medalCount: {{ $event->medal_count }},
            medalWeightCategory: {
                goldWeight: {{ $event->medalWeightCategory->gold_weight }},
                silverWeight: {{ $event->medalWeightCategory->silver_weight }},
                bronzeWeight: {{ $event->medalWeightCategory->bronze_weight }}
            }
        };
    @endforeach
});

async function prepareEditForm(eventCategoryId) {
    // Reset the form
    document.getElementById('editRecordForm').reset();

    // Set the form action URL
    document.getElementById('editRecordForm').action = `/admin/medal-records/${eventCategoryId}`;

    // Set the event category ID in the hidden input
    document.getElementById('edit_event_category_id').value = eventCategoryId;

    // Find and set the corresponding option in the event select dropdown
    const eventSelect = document.querySelector('#editRecordModal select[name="event_category_id"]');
    for (let i = 0; i < eventSelect.options.length; i++) {
        if (eventSelect.options[i].value == eventCategoryId) {
            eventSelect.selectedIndex = i;
            break;
        }
    }

    // Get event data
    const eventData = Array.from(eventSelect.options)
        .find(option => option.value == eventCategoryId);

    if (eventData) {
        // Set medal count
        document.getElementById('edit_medalCount').value = eventData.getAttribute('data-medal-count') || 1;

        // Set double bronze flag
        const isDoubleBronze = eventData.getAttribute('data-double-bronze') === '1';
        document.getElementById('edit_isDoubleBronze').value = isDoubleBronze ? '1' : '0';

        // Show/hide second bronze input based on double bronze status
        const secondBronzeDiv = document.querySelector('.edit-second-bronze-div');
        if (isDoubleBronze) {
            secondBronzeDiv.style.display = 'block';
            document.getElementById('edit_secondRunnerUp2').setAttribute('required', 'required');
        } else {
            secondBronzeDiv.style.display = 'none';
            document.getElementById('edit_secondRunnerUp2').removeAttribute('required');
        }
    }

    // Fetch existing medal records for this event
    await fetchMedalRecords(eventCategoryId);

    // Check if there are duplicate team IDs
    const selects = [
        document.getElementById('edit_championTeam'),
        document.getElementById('edit_firstRunnerUp'),
        document.getElementById('edit_secondRunnerUp'),
        document.getElementById('edit_secondRunnerUp2')
    ];

    const selectedTeams = selects.map(select => select.value).filter(value => value !== '');
    const hasDuplicates = new Set(selectedTeams).size < selectedTeams.length;

    // If duplicates are found, check the "allow team reuse" checkbox
    const allowTeamReuseCheckbox = document.getElementById('edit_allowTeamReuse');
    if (hasDuplicates) {
        allowTeamReuseCheckbox.checked = true;
        // Set the value to match the checked state
        allowTeamReuseCheckbox.value = '1';
    } else {
        allowTeamReuseCheckbox.checked = false;
        allowTeamReuseCheckbox.value = '0';
    }

    updateEditTeamSelections(true);
}



function fetchMedalRecords(eventCategoryId) {
    let goldTeamId = null;
    let silverTeamId = null;
    let bronzeTeamIds = [];
    let participants = 0;

    // Extract data from the displayed cards or table rows
    @foreach($medalRecords as $record)
        if ({{ $record->event_category_id }} == eventCategoryId) {
            if ({{ $record->gold_tally }} > 0) {
                goldTeamId = {{ $record->team_id }};
            }
            if ({{ $record->silver_tally }} > 0) {
                silverTeamId = {{ $record->team_id }};
            }
            if ({{ $record->bronze_tally }} > 0) {
                bronzeTeamIds.push({{ $record->team_id }});
            }
            participants = {{ $record->number_of_participant }};
        }
    @endforeach


    // Set form values
    document.getElementById('edit_championTeam').value = goldTeamId || '';
    document.getElementById('edit_firstRunnerUp').value = silverTeamId || '';

    if (bronzeTeamIds.length > 0) {
        document.getElementById('edit_secondRunnerUp').value = bronzeTeamIds[0] || '';

        if (bronzeTeamIds.length > 1 && document.getElementById('edit_isDoubleBronze').value === '1') {
            document.getElementById('edit_secondRunnerUp2').value = bronzeTeamIds[1] || '';
        }
    }

    document.getElementById('edit_participants').value = participants;

    updateEditTeamSelections();
}

function updateEditTeamSelections() {
    const selects = [
        document.getElementById('edit_championTeam'),
        document.getElementById('edit_firstRunnerUp'),
        document.getElementById('edit_secondRunnerUp'),
        document.getElementById('edit_secondRunnerUp2')
    ];

    const selectedValues = selects.map(select => select.value).filter(value => value !== '');

    // For each select box
    selects.forEach(currentSelect => {
        // Only process if the select exists
        if (!currentSelect) return;

        // For each option in current select box
        Array.from(currentSelect.options).forEach(option => {
            if (option.value === '') return; // Skip empty option

            const isSelectedElsewhere = selectedValues.includes(option.value) &&
                                       option.value !== currentSelect.value;

            option.disabled = isSelectedElsewhere;
        });
    });
}
</script>
<script>



// Add this script to the script section at the bottom of the page
// Run this function on page load to hide all sports if 'All' is selected by default
document.addEventListener('DOMContentLoaded', () => {
    filterSport();
});

function filterSport() {
    const checkboxes = document.querySelectorAll('.sport-checkbox');
    const selectedSports = [];

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            selectedSports.push(checkbox.value);
        }
    });

    const isAllSelected = selectedSports.includes('all') || selectedSports.length === 0;

    // Update the dropdown button text
    const dropdownButton = document.getElementById('sportFilterDropdown');
    dropdownButton.innerHTML = `<i class="mdi mdi-filter-outline me-1"></i> ${isAllSelected ? 'All Sports' : selectedSports.length + ' Selected'}`;

    // Show/hide reset button
    document.getElementById('resetFilterBtn').style.display = isAllSelected ? 'none' : 'block';

    // Card View Filtering
    const cardView = document.getElementById('cardView');
    const sportSections = cardView.querySelectorAll('.sport-header');
    let anyVisibleSport = false;

    sportSections.forEach(header => {
        // Find the nearest parent with sport-id attribute or data attribute
        const sportId = header.getAttribute('data-sport-id');

        // Check if we should show this sport based on ID, not name
        const shouldShow = isAllSelected || selectedSports.includes(sportId);

        const cardRow = header.nextElementSibling;
        if (shouldShow) {
            header.style.display = 'flex'; // Changed to flex since it's using d-flex
            cardRow.style.display = 'flex';
            anyVisibleSport = true;
            header.classList.remove('d-none');
        } else {
            header.classList.add('d-none');
            header.style.display = 'none';
            cardRow.style.display = 'none';
        }
    });


    // Handle "no data" message in card view
    const noDataCard = cardView.querySelector('.card.shadow-sm.no-data-filtered');
    if (!anyVisibleSport && !noDataCard) {
        const noDataElement = document.createElement('div');
        noDataElement.className = 'card shadow-sm no-data-filtered';
        noDataElement.innerHTML = `
            <div class="card-body text-center p-5">
                <img src="/assets/images/no-data.svg" alt="No Data" class="img-fluid mb-3" style="max-width: 200px;">
                <h4>No Medal Records Found for this Filter</h4>
                <p class="text-muted">Try a different sport filter or reset filters to view all records.</p>
            </div>
        `;
        if (!cardView.querySelector('.no-data-filtered')) {
            cardView.appendChild(noDataElement);
        }

    } else if (anyVisibleSport) {
        const noDataFiltered = cardView.querySelector('.no-data-filtered');
        if (noDataFiltered) {
            noDataFiltered.remove();
        }
    }



}

function resetFilter() {
    document.querySelectorAll('.sport-checkbox').forEach(checkbox => checkbox.checked = false);
    filterSport();
}



document.addEventListener('DOMContentLoaded', () => {
    filterCatRecords() ;
});

// Variables to store filter states
let currentSportFilters = [];
let currentPostedStatus = [];
let currentSearchTerm = '';

// Run on page load to load stored filters

// Save filters to local storage
function saveFiltersToStorage() {
    localStorage.setItem('sportFilters', JSON.stringify(currentSportFilters));
    localStorage.setItem('postedStatus', JSON.stringify(currentPostedStatus));
    localStorage.setItem('searchTerm', currentSearchTerm);
}

// Load filters from local storage
function loadFiltersFromStorage() {
    // Load sport filters
    const storedSportFilters = localStorage.getItem('sportFilters');
    if (storedSportFilters) {
        currentSportFilters = JSON.parse(storedSportFilters);

        // Set checkboxes based on stored values
        document.querySelectorAll('.sport-checkbox').forEach(checkbox => {
            checkbox.checked = currentSportFilters.includes(checkbox.value);
        });

        // Update sport filter dropdown text
        updateSportFilterText();
    }

    // Load posted status filters
    const storedPostedStatus = localStorage.getItem('postedStatus');
    if (storedPostedStatus) {
        currentPostedStatus = JSON.parse(storedPostedStatus);

        // Set checkboxes based on stored values
        document.querySelectorAll('.posted-checkbox').forEach(checkbox => {
            checkbox.checked = currentPostedStatus.includes(checkbox.value);
        });

        // Update posted filter dropdown text
        updatePostedFilterText();
    }

    // Load search term
    const storedSearchTerm = localStorage.getItem('searchTerm');
    if (storedSearchTerm) {
        currentSearchTerm = storedSearchTerm;
        document.getElementById('searchInput').value = currentSearchTerm;
    }
}

// Apply all filters at once (sport, posted status, and search)
function applyAllFilters() {
    const cardView = document.getElementById('cardView');
    const sportSections = cardView.querySelectorAll('.sport-header');
    let anyVisibleSport = false;

    // Process each sport section
    sportSections.forEach(header => {
        const sportId = header.getAttribute('data-sport-id');
        const cardRow = header.nextElementSibling;

        // Check if sport should be shown based on sport filter
        const isAllSportsSelected = currentSportFilters.includes('all') || currentSportFilters.length === 0;
        const shouldShowSport = isAllSportsSelected || currentSportFilters.includes(sportId);

        // Initially mark the sport as hidden
        header.style.display = 'none';
        header.classList.add('d-none');
        if (cardRow) cardRow.style.display = 'none';

        // If sport is filtered out, skip further processing
        if (!shouldShowSport) {
            return;
        }

        // Process each card in this sport to see if any should be visible
        let visibleCardsInSport = 0;

        // Process each card in this sport
        const cards = cardRow.querySelectorAll('.col');
        cards.forEach(cardCol => {
            const cardTitle = cardCol.querySelector('.card-title');
            const statusBadge = cardCol.querySelector('.status-badge');

            if (!cardTitle || !statusBadge) return;

            const eventTitle = cardTitle.textContent.trim().toLowerCase();
            const isPosted = statusBadge.classList.contains('status-posted');

            // Check if card matches all filters
            const matchesSearchTerm = currentSearchTerm === '' ||
                                      eventTitle.includes(currentSearchTerm.toLowerCase());

            const isAllStatusSelected = currentPostedStatus.includes('all') || currentPostedStatus.length === 0;
            const matchesPostedStatus = isAllStatusSelected ||
                                       (isPosted && currentPostedStatus.includes('posted')) ||
                                       (!isPosted && currentPostedStatus.includes('pending'));

            // Show/hide card based on all filters
            if (matchesSearchTerm && matchesPostedStatus) {
                cardCol.style.display = 'block';
                visibleCardsInSport++;
            } else {
                cardCol.style.display = 'none';
            }
        });

        // Show sport section only if it has visible cards
        if (visibleCardsInSport > 0) {
            header.style.display = 'flex';
            header.classList.remove('d-none');
            cardRow.style.display = 'flex';
            anyVisibleSport = true;
        }
    });

    // Handle "no data" message
    handleNoDataMessage(anyVisibleSport);
}

// Filter by sport function
function filterSport() {
    const checkboxes = document.querySelectorAll('.sport-checkbox');
    currentSportFilters = [];

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            currentSportFilters.push(checkbox.value);
        }
    });

    // Update the dropdown button text
    updateSportFilterText();

    // Show/hide reset button
    document.getElementById('resetFilterBtn').style.display =
        (currentSportFilters.includes('all') || currentSportFilters.length === 0) ? 'none' : 'block';

    // Save and apply all filters
    saveFiltersToStorage();
    applyAllFilters();
}

// Update sport filter dropdown text
function updateSportFilterText() {
    const dropdownButton = document.getElementById('sportFilterDropdown');
    const isAllSelected = currentSportFilters.includes('all') || currentSportFilters.length === 0;
    dropdownButton.innerHTML = `<i class="mdi mdi-filter-outline me-1"></i> ${isAllSelected ? 'All Sports' : currentSportFilters.length + ' Selected'}`;
}

// Filter by posted status
function filterByPosted() {
    const checkboxes = document.querySelectorAll('.posted-checkbox');
    currentPostedStatus = [];

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            currentPostedStatus.push(checkbox.value);
        }
    });
    const isAllSelected = currentPostedStatus.includes('all') || currentPostedStatus.length === 0;

    document.getElementById('resetFilterBtn').style.display = isAllSelected ? 'none' : 'block';

    // Update the dropdown button text
    updatePostedFilterText();

    // Save and apply all filters
    saveFiltersToStorage();
    applyAllFilters();
}

// Update posted filter dropdown text
function updatePostedFilterText() {
    const dropdownButton = document.getElementById('postedFilterDropdown');
    const isAllSelected = currentPostedStatus.includes('all') || currentPostedStatus.length === 0;
    dropdownButton.innerHTML = `<i class="mdi mdi-check-circle-outline me-1"></i> ${isAllSelected ? 'All Status' : currentPostedStatus.length + ' Selected'}`;
}

// Search events function
function searchEvents() {
    currentSearchTerm = document.getElementById('searchInput').value.trim();
    const isInput = currentSearchTerm == '';

    document.getElementById('resetFilterBtn').style.display = isInput ? 'none' : 'block';
    saveFiltersToStorage();
    applyAllFilters();
}

// Clear search function
function clearSearch() {
    document.getElementById('searchInput').value = '';
    currentSearchTerm = '';
    saveFiltersToStorage();
    applyAllFilters();
}

// Reset all filters
function resetFilter() {
    // Reset sport filters
    document.querySelectorAll('.sport-checkbox').forEach(checkbox => checkbox.checked = false);
    currentSportFilters = [];
    updateSportFilterText();

    // Reset posted status filters
    document.querySelectorAll('.posted-checkbox').forEach(checkbox => checkbox.checked = false);
    currentPostedStatus = [];
    updatePostedFilterText();

    // Reset search
    document.getElementById('searchInput').value = '';
    currentSearchTerm = '';

    // Save and apply all filters
    saveFiltersToStorage();
    applyAllFilters();

    // Hide reset button
    document.getElementById('resetFilterBtn').style.display = 'none';
}

// Handle no data message
function handleNoDataMessage(anyVisibleSport) {
    const cardView = document.getElementById('cardView');
    const noDataCard = cardView.querySelector('.card.shadow-sm.no-data-filtered');

    if (!anyVisibleSport) {
        if (!noDataCard) {
            const noDataElement = document.createElement('div');
            noDataElement.className = 'card shadow-sm no-data-filtered';
            noDataElement.innerHTML = `
                <div class="card-body text-center p-5">
                    <img src="/assets/images/no-data.svg" alt="No Data" class="img-fluid mb-3" style="max-width: 200px;">
                    <h4>No Medal Records Found for this Filter</h4>
                    <p class="text-muted">Try different filter settings or reset filters to view all records.</p>
                </div>
            `;
            cardView.appendChild(noDataElement);
        }
    } else if (noDataCard) {
        noDataCard.remove();
    }
}

function filterCatRecords() {
    // Get all checked event category checkboxes
    const eventCategoryCheckboxes = document.querySelectorAll('.event-category-checkbox');
    const selectedEventCategories = [];

    eventCategoryCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            selectedEventCategories.push(checkbox.value);
        }
    });

    // Check if "All Categories" is selected or nothing is selected
    const isAllCategoriesSelected = selectedEventCategories.includes('all') || selectedEventCategories.length === 0;

    // Update the dropdown button text
    const dropdownButton = document.getElementById('eventCategoryFilterDropdown');
    dropdownButton.innerHTML = `<i class="mdi mdi-filter-outline me-1"></i> ${isAllCategoriesSelected ? 'All Categories' : selectedEventCategories.length + ' Selected'}`;

    // Get all medal cards
    const medalCards = document.querySelectorAll('.medal-card');
    let anyVisibleCard = false;

    // Loop through all medal cards
    medalCards.forEach(card => {
        // Find the event category ID from the card
        const cardTitle = card.closest('.col').querySelector('.card-title');

        if (!cardTitle) return; // Skip if no card title exists

        const eventCategoryId = cardTitle.getAttribute('data-event-category-id');

        // Ensure eventCategoryId is valid
        if (!eventCategoryId) return;

        // Check if we should show this card
        const shouldShowByCategory = isAllCategoriesSelected || selectedEventCategories.includes(eventCategoryId);

        // Show/hide the card based on category filter
        const cardCol = card.closest('.col');

        if (shouldShowByCategory) {
            cardCol.style.display = 'block';
            anyVisibleCard = true;
        } else {
            cardCol.style.display = 'none';
        }
    });

    // Handle "no data" message when no cards are visible
    const cardView = document.getElementById('cardView');
    const noDataFilteredCard = cardView.querySelector('.no-data-filtered');

    if (!anyVisibleCard) {
        if (!noDataFilteredCard) {
            const noDataElement = document.createElement('div');
            noDataElement.className = 'card shadow-sm no-data-filtered';
            noDataElement.innerHTML = `
                <div class="card-body text-center p-5">
                    <img src="/assets/images/no-data.svg" alt="No Data" class="img-fluid mb-3" style="max-width: 200px;">
                    <h4>No Medal Records Found for this Filter</h4>
                    <p class="text-muted">Try different filter settings or reset filters to view all records.</p>
                </div>
            `;
            cardView.appendChild(noDataElement);
        }
    } else if (noDataFilteredCard) {
        noDataFilteredCard.remove();
    }

    // Show/hide sport headers based on whether they have any visible cards
    const sportSections = cardView.querySelectorAll('.sport-header');
    sportSections.forEach(header => {
        const cardRow = header.nextElementSibling;
        const visibleCards = cardRow.querySelectorAll('.col:not([style*="display: none"])'); // Fix for hidden elements

        if (visibleCards.length === 0) {
            header.style.display = 'none';
            cardRow.style.display = 'none';
        } else {
            header.style.display = 'flex';
            cardRow.style.display = 'flex';
        }
    });
}




</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>

<script>
// PDF Export functionality
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportPDF');
    if (!exportBtn) return; // Exit if button doesn't exist

    const progressBar = document.getElementById('exportProgress');
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.getElementById('progressText');
    const exportOverlay = document.getElementById('exportOverlay');

    // Only add this event listener once
    if (!exportBtn.hasAttribute('data-listener-attached')) {
        exportBtn.setAttribute('data-listener-attached', 'true');

        exportBtn.addEventListener('click', function() {
            // Show overlay to block user interaction
            exportOverlay.style.display = 'block';
            progressBar.style.display = 'block';
            exportBtn.disabled = true;
            progressFill.style.width = '70%';
            progressText.textContent = 'Preparing document...';

            setTimeout(() => {
                try {
                    // Check if jsPDF is available
                    if (!window.jspdf || !window.jspdf.jsPDF) {
                        throw new Error('PDF library not loaded. Please refresh the page and try again.');
                    }

                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('l', 'mm', 'a4');

                    // Document styling constants
                    const colors = {
                        primary: [0, 73, 30],
                        secondary: [2, 104, 30],
                        accent: [255, 255, 255],
                        gold: [255, 184, 0],
                        silver: [183, 183, 183],
                        bronze: [205, 127, 50],
                        light: [241, 245, 249],
                        white: [255, 255, 255],
                        lightText: [148, 163, 184],
                        darkText: [30, 41, 59]
                    };

                    // Create cover page
                    doc.setFillColor(...colors.primary);
                    doc.rect(0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height, 'F');

                    // Add design element - white section at bottom
                    doc.setFillColor(...colors.white);
                    doc.roundedRect(0, doc.internal.pageSize.height - 110, doc.internal.pageSize.width, 110, 80, 0, 'F');

                    // Add header text
                    doc.setTextColor(...colors.white);
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(38);
                    doc.text('Results Tally Report', 30, 50);

                    doc.setFontSize(26);
                    doc.text('Event Results Summary', 30, 65);

                    // Add medal icons
                    doc.setFillColor(...colors.gold);
                    doc.circle(35, 100, 10, 'F');
                    doc.setFillColor(...colors.silver);
                    doc.circle(65, 100, 10, 'F');
                    doc.setFillColor(...colors.bronze);
                    doc.circle(95, 100, 10, 'F');

                    // Add date
                    doc.setTextColor(...colors.darkText);
                    doc.setFontSize(12);

                    // Format the current date
                    const now = new Date();
                    const day = String(now.getDate()).padStart(2, '0');
                    const year = now.getFullYear();
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    const monthName = monthNames[now.getMonth()];
                    const hours12 = now.getHours() % 12 || 12;
                    const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                    const formattedHours = String(hours12).padStart(2, '0');
                    const formattedMinutes = String(now.getMinutes()).padStart(2, '0');
                    const formattedDateTime = `${day}-${monthName}-${year} ${formattedHours}:${formattedMinutes} ${ampm}`;

                    doc.text(`Generated: ${formattedDateTime}`, 30, 140);

                    progressFill.style.width = '40%';
                    progressText.textContent = 'Extracting medal data...';

                    // Process medal data from the page - check for visible elements only
                    const medalData = [];
                    const sportHeaders = document.querySelectorAll('.sport-header:not([style*="display: none"])');

                    // Check if we have any visible sports
                    if (sportHeaders.length === 0) {
                        throw new Error('No visible data to export. Please adjust your filters to show some data first.');
                    }

                    sportHeaders.forEach(header => {
                        // Safely extract sport name
                        const sportNameEl = header.querySelector('h4');
                        if (!sportNameEl) return;

                        const sportName = sportNameEl.textContent.trim();
                        const cardContainer = header.nextElementSibling;
                        if (!cardContainer) return;

                        const medalCards = cardContainer.querySelectorAll('.medal-card');

                        medalCards.forEach(card => {
                            // Skip card if parent col is hidden
                            const cardCol = card.closest('.col');
                            if (cardCol && cardCol.style.display === 'none') return;

                            // Safely extract data with optional chaining
                            const cardTitle = card.querySelector('.card-title');
                            if (!cardTitle) return;

                            const eventName = cardTitle.textContent.trim();
                            const statusBadge = card.querySelector('.status-badge');
                            const status = statusBadge ? statusBadge.textContent.trim() : 'Unknown';

                            // Get gold winner - with safe access
                            let goldTeam = '-', goldCount = '0', goldWeight = '0';
                            const goldBadge = card.querySelector('.medal-badge.medal-gold');
                            if (goldBadge) {
                                const goldPosition = goldBadge.closest('.medal-position');
                                if (goldPosition) {
                                    const teamEl = goldPosition.querySelector('.team-name');
                                    if (teamEl) goldTeam = teamEl.textContent.trim();

                                    const countEl = goldPosition.querySelector('#gold-count-value');
                                    if (countEl) goldCount = countEl.textContent.trim();

                                    const weightEl = goldPosition.querySelector('#gold-weight-value');
                                    if (weightEl) goldWeight = weightEl.textContent.trim();
                                }
                            }

                            // Get silver winner - with safe access
                            let silverTeam = '-', silverCount = '0', silverWeight = '0';
                            const silverBadge = card.querySelector('.medal-badge.medal-silver');
                            if (silverBadge) {
                                const silverPosition = silverBadge.closest('.medal-position');
                                if (silverPosition) {
                                    const teamEl = silverPosition.querySelector('.team-name');
                                    if (teamEl) silverTeam = teamEl.textContent.trim();

                                    const countEl =  silverPosition.querySelector('#silver-count-value');
                                    if (countEl) silverCount = countEl.textContent.trim();

                                    const weightEl =  silverPosition.querySelector('#silver-weight-value');
                                    console.log(weightEl)
                                    if (weightEl) silverWeight = weightEl.textContent.trim();
                                }
                            }

                            // Get bronze winner(s) - with safe access
                            let bronzeTeams = '-', bronzeCount = '0', bronzeWeight = '0';
                            const bronzeBadge = card.querySelector('.medal-badge.medal-bronze');
                            if (bronzeBadge) {
                                const bronzePosition = bronzeBadge.closest('.medal-position');
                                if (bronzePosition) {
                                    const bronzeTeamElements = bronzePosition.querySelectorAll('.team-name');
                                    if (bronzeTeamElements && bronzeTeamElements.length > 0) {
                                        const teams = Array.from(bronzeTeamElements).map(el => el.textContent.trim());
                                        bronzeTeams = teams.join(', ');
                                    }

                                    const countEl = bronzePosition.querySelector('.bronze-count-value');
                                    if (countEl) bronzeCount = countEl.textContent.trim();

                                    const weightEl = bronzePosition.querySelector('.bronze-weight-value');
                                    if (weightEl) bronzeWeight = weightEl.textContent.trim();
                                }
                            }

                            // Get participants count - with safe access
                            let participants = '0';
                            const participantsEl = card.querySelector('.card-footer .text-muted');
                            if (participantsEl) {
                                const participantsText = participantsEl.textContent.trim();
                                const participantsMatch = participantsText.match(/(\d+)/);
                                if (participantsMatch) participants = participantsMatch[1];
                            }

                            medalData.push({
                                sportName,
                                eventName,
                                status,
                                goldTeam,
                                goldCount,
                                goldWeight,
                                silverTeam,
                                silverCount,
                                silverWeight,
                                bronzeTeams,
                                bronzeCount,
                                bronzeWeight,
                                participants
                            });
                        });
                    });

                    // Ensure we have data to export
                    if (medalData.length === 0) {
                        throw new Error('No medal data found. Please make sure some data is visible.');
                    }

                    // Add medal data summary page
                    doc.addPage();

                    // Create header
                    doc.setFillColor(...colors.primary);
                    doc.rect(0, 0, doc.internal.pageSize.width, 20, 'F');

                    doc.setTextColor(...colors.white);
                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Results Tally Report', doc.internal.pageSize.width / 2, 13, { align: 'center' });

                    progressFill.style.width = '60%';
                    progressText.textContent = 'Creating medal summary table...';

                    // Group by sport
                    const sportGroups = {};
                    medalData.forEach(item => {
                        if (!sportGroups[item.sportName]) {
                            sportGroups[item.sportName] = [];
                        }
                        sportGroups[item.sportName].push(item);
                    });

                    let yPos = 30;
                    let currentPage = 2;

                    // Create tables for each sport
                    for (const sportName in sportGroups) {
                        // Check if we need a new page
                        if (yPos > doc.internal.pageSize.height - 60) {
                            doc.addPage();
                            currentPage++;

                            // Add header to new page
                            doc.setFillColor(...colors.primary);
                            doc.rect(0, 0, doc.internal.pageSize.width, 20, 'F');

                            doc.setTextColor(...colors.white);
                            doc.setFontSize(12);
                            doc.setFont('helvetica', 'bold');
                            doc.text('Results Tally Report', doc.internal.pageSize.width / 2, 13, { align: 'center' });

                            yPos = 30;
                        }

                        // Add sport title
                        doc.setTextColor(...colors.darkText);
                        doc.setFontSize(14);
                        doc.setFont('helvetica', 'bold');
                        doc.text(sportName, 20, yPos);

                        yPos += 10;

                        // Create table data for this sport
                        const tableData = sportGroups[sportName].map(item => [
                            item.eventName,
                            item.status,
                            `${item.goldTeam} Count: (${item.goldCount}) Weight: (${item.goldWeight})`,
                            `${item.silverTeam} Count: (${item.silverCount}) Weight: (${item.silverWeight})`,
                            `${item.bronzeTeams} Count: (${item.bronzeCount}) Weight: (${item.bronzeWeight})`,
                            item.participants
                        ]);

                        // Add table for this sport
                        doc.autoTable({
                            startY: yPos,
                            head: [[
                                'Event',
                                'Status',
                                'Gold',
                                'Silver',
                                'Bronze',
                                'Teams'
                            ]],
                            body: tableData,
                            theme: 'grid',
                            styles: {
                                fontSize: 9,
                                cellPadding: 4,
                                lineColor: [240, 240, 240],
                                lineWidth: 0.1,
                                font: 'helvetica',
                            },
                            headStyles: {
                                fillColor: colors.secondary,
                                textColor: colors.white,
                                fontSize: 10,
                                fontStyle: 'bold',
                                halign: 'center',
                                cellPadding: 4,
                                lineWidth: 0
                            },
                            columnStyles: {
                                0: { halign: 'left', cellWidth: 'auto' },
                                1: { halign: 'center', cellWidth: 20 },
                                2: { halign: 'left', cellWidth: 'auto' },
                                3: { halign: 'left', cellWidth: 'auto' },
                                4: { halign: 'left', cellWidth: 'auto' },
                                5: { halign: 'center', cellWidth: 20 }
                            },
                            alternateRowStyles: {
                                fillColor: colors.light
                            },
                            didDrawPage: (data) => {
                                // Update yPos for next table
                                yPos = data.cursor.y + 15;
                            }
                        });
                    }

                    progressFill.style.width = '80%';
                    progressText.textContent = 'Finalizing document...';

                    // Add footers to all pages
                    const pageCount = doc.internal.getNumberOfPages();
                    for (let i = 1; i <= pageCount; i++) {
                        doc.setPage(i);

                        // Skip footer on cover page
                        if (i === 1) continue;

                        // Add footer line
                        doc.setDrawColor(...colors.light);
                        doc.setLineWidth(0.5);
                        doc.line(10, doc.internal.pageSize.height - 15, doc.internal.pageSize.width - 10, doc.internal.pageSize.height - 15);

                        // Add page number
                        doc.setFillColor(...colors.light);
                        doc.roundedRect(doc.internal.pageSize.width / 2 - 15, doc.internal.pageSize.height - 12, 30, 8, 4, 4, 'F');

                        doc.setTextColor(...colors.darkText);
                        doc.setFontSize(8);
                        doc.text(
                            `Page ${i} of ${pageCount}`,
                            doc.internal.pageSize.width / 2,
                            doc.internal.pageSize.height - 7,
                            { align: 'center' }
                        );

                        // Add footer text
                        doc.setTextColor(...colors.secondary);
                        doc.text(
                            'Results Tally System',
                            20,
                            doc.internal.pageSize.height - 7
                        );
                    }
                    progressFill.style.width = '100%';
                    progressText.textContent = 'Download starting...';

                    // Save the PDF with formatted filename after a 2-second delay

                        const filename = `Medal_Tally_Report_${day}-${monthName}-${year}_${formattedHours}-${formattedMinutes}-${ampm}.pdf`;
                        doc.save(filename);

                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert(error.message || 'Error generating PDF. Please try again.');
                } finally {
                    // Hide overlay and progress bar
                    exportOverlay.style.display = 'none';
                    progressBar.style.display = 'none';
                    exportBtn.disabled = false;
                }
            }, 2000);
        });
    }
});
</script>
@endsection

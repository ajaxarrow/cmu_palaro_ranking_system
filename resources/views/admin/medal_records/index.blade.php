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
        @if(!$sportId)
            <div class="d-flex align-items-center gap-3">
                <!-- Reset Filter Button (Initially Hidden) -->
                <button id="resetFilterBtn" class="btn btn-light" onclick="resetFilter()" style="display: none;">
                    <i class="mdi mdi-refresh me-1"></i> Reset Filter
                </button>
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
                <div class="sport-header mb-4 d-flex justify-content-between align-items-center" data-sport-id="{{ $sport->id }}">
                    <h4 class="fs-16 m-0">{{ $sport->name }}</h4>
                    @if($user->role == 'admin')
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
                                        $bronzeWeight = $record->bronze_tally * $eventCategory->medalWeightCategory->bronze_weight;
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
                                        <i class="mdi mdi-account-group me-1"></i> {{ $participants }} Participating Teams
                                    </div>
                                    @if(!$isPosted)
                                    <div class="d-flex align-items-center gap-1">
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

                                        @if($user->role == 'admin' || $user->role == 'tournament_director')
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

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])
@endsection

@section('script-bottom')
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
@vite(['resources/js/pages/crm-dashboard.init.js'])
<!-- Add this JavaScript function to prepare the edit form -->
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
    updateEditTeamSelections();
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
@endsection

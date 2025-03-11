@extends('layouts.admin', ['title' => 'Tally'])

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
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Medal Tally</h4>
        </div>
        @if($user->role != 'user')
            <div class="text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">Add</button>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="bg-light">
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
                                    $hasRecords = false;
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
                                        @php $hasRecords = true; @endphp

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
                                                $medalCount = $participants = 0;
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
                                                        <h5 class="m-0">{{ $sport->name }}</h5>
                                                    </td>
                                                @endif

                                                <td rowspan="{{ $eventRowspan }}" class="align-middle">{{ $eventCategory->name }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning text-dark">Champion</span>
                                                </td>
                                                <td>{{ $goldWinner ? $goldWinner->name : '-' }}</td>
                                                <td>{{ $goldTally }}</td>
                                                <td>{{ $goldWeight }}</td>
                                                <td rowspan="{{ $eventRowspan }}" class="align-middle text-center">{{ $participants }}</td>
                                                <td rowspan="{{ $eventRowspan }}" class="align-middle text-center">
                                                    @if($isPosted)
                                                        <div class="badge bg-info-subtle text-info">Posted</div>
                                                    @else
                                                        <div class="badge bg-dark-subtle text-dark">Pending</div>
                                                    @endif
                                                </td>
                                                @if($user->role != 'user')
                                                    <td rowspan="{{ $eventRowspan }}" class="align-middle text-center">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
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
                                                    <span class="badge bg-light text-dark">1st Runner Up</span>
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
                                                            <span class="badge bg-danger-subtle text-danger">2nd Runner Up</span>
                                                        </td>
                                                        <td>{{ $bronzeWinner->name }}</td>
                                                        <td>{{ $bronzeTally }}</td>
                                                        <td>{{ $bronzeWeight }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-center">
                                                        <span class="badge bg-danger-subtle text-danger">2nd Runner Up</span>
                                                    </td>
                                                    <td>-</td>
                                                    <td>{{ $bronzeTally }}</td>
                                                        <td>{{ $bronzeWeight }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                @if(!$hasRecords)
                                    <tr>
                                        <td colspan="{{ $user->role != 'user' ? 8 : 7 }}" class="text-center">No medal records found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($user->role != 'user')
    <!-- Add Record Modal -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRecordModalLabel">Add Medal Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medal_records.store') }}" method="POST" onsubmit="disableSubmitButton(this)">
                        @csrf
                        <div class="mb-3">
                            <label for="event" class="form-label">Event Category</label>
                            <select name="event_category_id" class="form-control" id="event" required onchange="handleEventChange(this)">
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
                        <div class="mb-3">
                            <label for="championTeam" class="form-label">Champion (Gold)</label>
                            <select name="champion_team_id" class="form-control" id="championTeam" required onchange="updateTeamSelections()">
                                <option value="">Select Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="firstRunnerUp" class="form-label">1st Runner Up (Silver)</label>
                            <select name="first_runner_up_team_id" class="form-control" id="firstRunnerUp" required onchange="updateTeamSelections()">
                                <option value="">Select Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="secondRunnerUp" class="form-label">2nd Runner Up (Bronze)</label>
                            <select name="second_runner_up_team_id" class="form-control" id="secondRunnerUp" required onchange="updateTeamSelections()">
                                <option value="">Select Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 second-bronze-div" style="display: none;">
                            <label for="secondRunnerUp2" class="form-label">2nd Runner Up (Bronze) - Second Team</label>
                            <select name="second_runner_up_team_id_2" class="form-control" id="secondRunnerUp2" onchange="updateTeamSelections()">
                                <option value="">Select Team</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="medalCount" class="form-label">Medal Count</label>
                            <input type="number" name="medal_count" class="form-control" id="medalCount" readonly>
                            <small class="text-muted">Medal count is determined by the event category.</small>
                        </div>
                        <div class="mb-3">
                            <label for="medalWeight" class="form-label">Number of Participants</label>
                            <input type="number" name="number_of_participant" class="form-control" id="medalWeight" value="1" min="1" required>
                        </div>
                        <input type="hidden" name="is_double_bronze" id="isDoubleBronze" value="0">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Record</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for handling team selections and double bronze -->
    <script>
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
</script>
@endsection

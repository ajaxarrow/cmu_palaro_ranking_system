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
                                <th>No. of Participating Teams</th>
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
                                                $participants = $record->number_of_participant;
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
                                                    <div class="d-flex align-items-center gap-1">
                                                        @if($user->role != 'user')
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-primary btn-floating"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editRecordModal"
                                                                    onclick="prepareEditForm('{{ $eventCategory->id }}')"
                                                                    {{ $isPosted ? 'disabled' : '' }}
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="{{ $isPosted ? 'Editing disabled: Record is posted' : 'Edit record' }}"
                                                                    style="{{ $isPosted ? 'cursor: not-allowed;' : '' }}">
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

<script>
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
</script>
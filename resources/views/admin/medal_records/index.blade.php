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
            <h4 class="fs-18 fw-semibold m-0">Tally</h4>
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
                    <table
                        id="state-saving-datatable"
                        class="table activate-select dt-responsive nowrap w-100" >
                            <thead>
                                <tr>
                                    <th class="ps-0">Team</th>
                                    <th>Event</th>
                                    <th>Gold Tally</th> 
                                    <th>Silver Tally</th>
                                    <th>Bronze Tally</th>
                                    <th>Medal Weight</th>
                                    <th>Status</th>
                                    
                                    @if($user->role != 'user')
                                        <th class="text-end" >Actions</th>
                                    @endif
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medalRecords as $record)
                                    <tr>
                                        <td class="ps-0">
                                            <p class="d-inline-block align-middle mb-0">
                                                <span class="font-13 fw-medium">{{ $record->team->name }}</span>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $record->event->name }} </p>
                                        </td>
                                       
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $record->gold_tally }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $record->silver_tally }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $record->bronze_tally }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $record->number_of_participant }} </p>
                                        </td>
                                        <td>   
                                         @if($record->is_posted == 1)
                                            <div class="btn btn-sm bg-info-subtle me-1">Posted  </div>
                                        @else
                                            <div class="btn btn-sm bg-dark-subtle me-1">Pending  </div>
                                       
                                        @endif
                                        </td>
                                        @if($user->role != 'user')
                                            <td class="text-end">

                                                @if($record->is_posted == 0)

                                                        @if($user->role == 'admin' || $user->role == 'tournament_director' )     

                                                            <a aria-label="anchor" class="btn btn-sm bg-success-subtle me-1" data-bs-toggle="modal" data-bs-target="#confirmPostRecordModal-{{$record->id}}" >
                                                                <i class="ph ph-trophy s-14 text-primary"></i>
                                                            </a>
                                                        @else
                                                            <button  aria-label="anchor"  class="btn btn-sm bg-dark-subtle me-1"  >
                                                                <i class="ph ph-trophy fs-14 text-white"></i>
                                                            </button> 
                                                        @endif
                                                    
                                                        @if($user->role == 'admin' || $user->role == 'tournament_director' || $user->id == optional($record->postedBy)->id)

                                                            <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" data-bs-toggle="modal" data-bs-target="#editRecordModal-{{$record->id}}" onclick="setEditFormAction('{{ route('medal_records.update', $record->id) }}', '{{ $record->team_id }}', '{{ $record->event->name }}', '{{ $record->gold_tally }}', '{{ $record->silver_tally }}', '{{ $record->bronze_tally }}', '{{ $record->number_of_participant }}')">
                                                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                                            </a>
                                                        @else
                                                            
                                                            <a aria-label="anchor"  class="btn btn-sm bg-dark-subtle me-1" >
                                                                <i class="mdi mdi-pencil-outline fs-14 text-white"></i>
                                                            </a>

                                                        @endif

                                                        @if($user->role == 'admin')

                                                            <a aria-label="anchor" class="btn btn-sm bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#delete-event-modal-{{$record->id}}">
                                                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                                                            </a>
                                                        @else
                                                            <button aria-label="anchor" class=" btn btn-sm bg-dark-subtle" >
                                                                <i class="mdi mdi-delete fs-14 text-white"></i>
                                                            </button>

                                                        @endif
                                                @else

                                                    <button  aria-label="anchor"  class="btn btn-sm bg-dark-subtle me-1"  >
                                                        <i class="ph ph-trophy fs-14 text-white"></i>
                                                    </button>   
                                                    
                                                    <button aria-label="anchor"  class="btn btn-sm bg-dark-subtle me-1" >
                                                        <i class="mdi mdi-pencil-outline fs-14 text-white"></i>
                                                    </button>

                                                    <button aria-label="anchor" class=" btn btn-sm bg-dark-subtle" >
                                                        <i class="mdi mdi-delete fs-14 text-white"></i>
                                                    </button>

                                                @endif

                                            </td>
                                        @endif
                                    </tr>
                                    @if($user->role == 'admin')     
                                    {{-- Post Medal Record  Modal --}}
                                    <div class="modal fade" id="confirmPostRecordModal-{{$record->id}}" tabindex="-1" aria-labelledby="ConfirmPostRecordModal-{{$record->id}}Label" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                        <h5 class="modal-title" id="editRecordModalLabel">Post Medal Record</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                       
                                                    <p class="card-text">
                                                        <strong>Warning:</strong> You are about to finalize this medal record. Once finalized, it will be officially recorded and can no longer be edited.  
                                                        <br><br>Are you sure you want to proceed?
                                                    </p>
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <button class="btn  bg-primary-subtle" data-bs-dismiss="modal" >Cancel</button>
                                                       
                                                        <form id="editForm-{{ $record->id }}" action="{{ route('medal_records.approve', $record->id) }}" method="POST" onsubmit="disableSubmitButton(this)">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="is_posted" value="1">
                                                            <button  form="editForm-{{ $record->id }}" class="btn  bg-danger-subtle" >Confirm</button>
                                                        </form>
                                                    </div>
                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editRecordModal-{{$record->id}}" tabindex="-1" aria-labelledby="editRecordModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editRecordModalLabel">Confirmation Required</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="editForm" action="{{ route('medal_records.update', $record->id) }}" method="POST" onsubmit="disableSubmitButton(this)">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="editTeamId" class="form-label">Team</label>
                                                            <select name="team_id" class="form-control" id="editTeamId" required>
                                                                <option value="">Select Team</option>
                                                                @foreach ($teams as $team)
                                                                    <option value="{{ $team->id }}" {{ $team->id == $record->team_id ? 'selected' : '' }}>{{ $team->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="editTeamId" class="form-label">Event Category</label>
                                                            <select name="team_id" class="form-control" id="editTeamId" required>
                                                                <option value="">Select Event</option>
                                                                @foreach ($events as $event)
                                                                    <option value="{{ $event->id }}" {{ $event->id == $record->event_category_id ? 'selected' : '' }}>{{  $event->name  }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                     
                                                        <div class="mb-3">
                                                            <label for="editGoldTally" class="form-label">Gold Tally</label>
                                                            <input type="number" name="gold_tally" class="form-control" id="editGoldTally" value="{{ $record->gold_tally }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editSilverTally" class="form-label">Silver Tally</label>
                                                            <input type="number" name="silver_tally" class="form-control" id="editSilverTally" value="{{ $record->silver_tally }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editBronzeTally" class="form-label">Bronze Tally</label>
                                                            <input type="number" name="bronze_tally" class="form-control" id="editBronzeTally" value="{{ $record->bronze_tally }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editMedalWeight" class="form-label">Number of Participant</label>
                                                            <input type="number" name="number_of_participant" class="form-control" id="editMedalWeight" value="{{ $record->number_of_participant }}" required>
                                                        </div>
                                                        <input type="hidden" name="year" value="{{ date('Y') }}">
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Delete Modal Confirmation --}}
                                    <div class="modal fade" id="delete-event-modal-{{$record->id}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete Record?</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Confirm deletion of the record ?</p>
                                                    <form action="{{ route('medal_records.destroy', $record->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
                                <label for="teamId" class="form-label">Team</label>
                                <select name="team_id" class="form-control" id="teamId" required>
                                    <option value="">Select Team</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="event" class="form-label">Event</label>
                                <select name="event_category_id" class="form-control" id="event" required>
                                    <option value="">Select Event</option>
                                    @foreach ($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="goldTally" class="form-label">Gold Tally</label>
                                <input type="number" name="gold_tally" class="form-control" id="goldTally" required>
                            </div>
                            <div class="mb-3">
                                <label for="silverTally" class="form-label">Silver Tally</label>
                                <input type="number" name="silver_tally" class="form-control" id="silverTally" required>
                            </div>
                            <div class="mb-3">
                                <label for="bronzeTally" class="form-label">Bronze Tally</label>
                                <input type="number" name="bronze_tally" class="form-control" id="bronzeTally" required>
                            </div>
                            <div class="mb-3">
                                <label for="medalWeight" class="form-label">Number of Participant</label>
                                <input type="number" name="number_of_participant" class="form-control" id="medalWeight" required>
                            </div>
                            <input type="number" class="form-control d-none" name="year" value="{{ date('Y') }}">
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Record</button>
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
<script>
    function setEditFormAction(action, teamId, event, goldTally, silverTally, bronzeTally, medalWeight) {
        document.getElementById('editForm').action = action;
        document.getElementById('editTeamId').value = teamId;
        document.getElementById('editEvent').value = event;
        document.getElementById('editGoldTally').value = goldTally;
        document.getElementById('editSilverTally').value = silverTally;
        document.getElementById('editBronzeTally').value = bronzeTally;
        document.getElementById('editMedalWeight').value = medalWeight;
    }

    function disableSubmitButton(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true; // Disable the button
        submitButton.innerText = 'Submitting...'; // Change button text
    }
</script>
@endsection

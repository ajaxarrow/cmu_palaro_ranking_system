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
            <h4 class="fs-18 fw-semibold m-0">Users</h4>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">Add User</button>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 checkbox-all" id="datatable_1">
                            <thead>
                                <tr>
                                    <th class="ps-0">Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Record Modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRecordModalLabel">Add Medal Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" onsubmit="disableSubmitButton(this)">
                    @csrf
                    <div class="mb-3">
                        <label for="goldTally" class="form-label">Gold Tally</label>
                        <input type="number" name="gold_tally" class="form-control" id="goldTally" placeholder="Enter Gold Tally" required>
                    </div>
                    <div class="mb-3">
                        <label for="silverTally" class="form-label">Silver Tally</label>
                        <input type="number" name="silver_tally" class="form-control" id="silverTally" placeholder="Enter Silver Tally" required>
                    </div>
                    <div class="mb-3">
                        <label for="bronzeTally" class="form-label">Bronze Tally</label>
                        <input type="number" name="bronze_tally" class="form-control" id="bronzeTally" placeholder="Enter Bronze Tally" required>
                    </div>
                    <div class="mb-3">
                        <label for="medalWeight" class="form-label">Number of Participant</label>
                        <input type="number" name="number_of_participant" class="form-control" id="medalWeight" placeholder="Enter No. of Participant" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script-bottom')
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

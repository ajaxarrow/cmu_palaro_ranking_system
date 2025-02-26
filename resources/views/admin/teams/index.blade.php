@extends('layouts.admin', ['title' => 'Colleges'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
@endsection
@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- Display Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Display Error Message -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Teams</h4>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                Add Team
            </button>
        </div>

        <!-- Add Team Modal -->
        <div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addTeamModalLabel">Add New Team</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('teams.store')}}" method="POST" onsubmit="disableSubmitButton(this)" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="teamImage" class="form-label">Image</label>
                                <input required type="file" accept="image/*"  name="image" class="form-control" id="teamImage"  required>
                            </div>

                            <div class="mb-3">
                                <label for="teamName" class="form-label">Name</label>
                                <input required type="text" name="name" class="form-control" id="teamName" placeholder="Enter Team Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="teamDescription" class="form-label">Description</label>
                                <input required type="text" name="desc" class="form-control" id="teamDescription" placeholder="Enter Team Description" required>
                            </div>
                            <div class="mb-3">
                                <label for="collegeSelectAdd" class="form-label">Select Colleges</label>
                                <select required name="college_ids[]" id="collegeSelectAdd" class="form-select" multiple required>
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple colleges.</small>
                            </div>
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
                                    <th>Description</th>
                                    <th>Colleges</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teams as $team)
                                    <tr>
                                        <td class="ps-0 d-flex align-items-center gap-1">
                                            @if($team->image)
                                                <img src="{{ asset('storage/' . $team->image) }}" width="40" class="rounded-circle">
                                            @else
                                                <i class="ph-duotone fs-24 ph-ranking mb-0"></i>
                                            @endif
                                            <p class="d-inline-block align-middle mb-0 py-2">
                                        
                                                <span class="font-16 fw-medium ml-3">{{$team->name}}</span>
                                               
                                            </p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $team->desc }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">
                                                @if ($team->colleges)
                                                    @foreach ($team->colleges as $college)
                                                        {{ $college->name }}@if (!$loop->last), @endif
                                                    @endforeach
                                                @endif
                                            </p>
                                        </td>
                                        <td class="text-end">
                                            <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" href="{{ route('teams.show', $team->id) }}">
                                                <i class="mdi mdi-eye-outline fs-14 text-primary"></i>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $team->id }}">
                                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                            </a>
                                            <a aria-label="anchor" class="btn btn-sm bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $team->id }}">
                                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- EDIT MODAL for each team -->
                                    <div class="modal fade" id="editModal{{ $team->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $team->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $team->id }}">Edit Team</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('teams.update', $team->id) }}" method="POST" onsubmit="disableSubmitButton(this)" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-3">
                                                        @if($team->image)
                                                            <div class="mb-2 d-flex justify-content-center align-items-center">
                                                                <img id="imagePreview{{ $team->id }}" 
                                                                    src="{{ asset('storage/' . $team->image) }}" 
                                                                    width="100" height="100" 
                                                                    class="rounded-circle object-fit-cover">
                                                            </div>
                                                        @else
                                                            <div class="mb-2 d-flex justify-content-center align-items-center">
                                                                <img id="imagePreview{{ $team->id }}" 
                                                                    src="{{ asset('storage/default.jpg') }}" 
                                                                    width="100" height="100"  
                                                                    class="rounded-circle object-fit-cover">
                                                            </div>
                                                        @endif
                                                                                                                    
                                                            <label for="teamImage{{ $team->id }}" class="form-label">Image</label>
                                                            {{-- File input for uploading a new image --}}
                                                            <input type="file" accept="image/*" name="image" class="form-control" id="teamImage{{ $team->id }}" onchange="previewImage(event, '{{ $team->id }}')">
                                                        </div>


                                                        <div class="mb-3">
                                                            <label for="editTeamName{{ $team->id }}" class="form-label">Name</label>
                                                            <input required type="text" name="name" class="form-control" placeholder="Enter Team Name" id="editTeamName{{ $team->id }}" value="{{ $team->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="editTeamDescription{{ $team->id }}" class="form-label">Description</label>
                                                            <input required type="text" placeholder="Enter Team Description" name="desc" class="form-control" id="editTeamDescription{{ $team->id }}" value="{{ $team->desc }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="collegeSelectEdit{{ $team->id }}" class="form-label">Select Colleges</label>
                                                            @php
                                                                $selectedColleges = $team->colleges->pluck('id')->toArray();
                                                            @endphp

                                                            <select required name="college_ids[]" id="collegeSelectEdit{{ $team->id }}" class="form-select" multiple required>
                                                                @foreach ($colleges as $college)
                                                                    <option value="{{ $college->id }}" {{ in_array($college->id, $selectedColleges) ? 'selected' : '' }}>
                                                                        {{ $college->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple colleges.</small>
                                                        </div>
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DELETE MODAL for each team -->
                                    <div class="modal fade" id="deleteModal{{ $team->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $team->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $team->id }}">Delete Team</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this team?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <form id="deleteForm{{ $team->id }}" action="{{ route('teams.destroy', $team->id) }}" method="POST" style="display:inline;" onsubmit="disableSubmitButton(this)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
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

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])
<script>
    function disableSubmitButton(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true; // Disable the button
        submitButton.innerText = 'Submitting...'; // Optional: Change button text
    }
</script>
@endsection

@section('script-bottom')
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
<script>
    function previewImage(event, teamId) {
        const input = event.target;
        const preview = document.getElementById(`imagePreview${teamId}`);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]); // Read the image file
        }
    }
</script>


@vite(['resources/js/pages/crm-dashboard.init.js'])
@endsection

@extends('layouts.admin', ['title' => 'Colleges'])

@section('css')
@endsection
@section('content')

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Colleges</h4>
        </div>

        @if($user->role == 'admin')
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                Add College
            </button>
        </div>
        @endif

        <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalgridLabel">Add New College</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('colleges.store')}}"  method="POST" onsubmit="disableSubmitButton(this)" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div>

                                    <div class="mb-2 d-flex justify-content-center align-items-center">
                                        <img id="ImagePreview"
                                         onclick="document.getElementById('CollegeImage').click();"
                                            src="{{ asset('storage/default.jpg') }}"
                                            width="100" height="100"
                                            class="rounded-circle object-fit-cover">
                                    </div>


                                    <div class="mb-3">

                                        <label for="CollegeImage" class="form-label">Image</label>
                                        <input required type="file" accept="image/*"  name="image" class="form-control" id="CollegeImage" onchange="previewImage(event, 'ImagePreview')">

                                    </div>

                                    <div class="mb-3">
                                        <label for="firstName" class="form-label">Name</label>
                                        <input type="text" name="name" class="form-control" id="firstName" placeholder="Enter College Name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="lastName" class="form-label">Acronym</label>
                                        <input type="text"  name="acronym" class="form-control" id="lastName" placeholder="Enter College Acronym">
                                    </div>

                                </div>

                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </form> <!-- end form -->
                    </div> <!-- end modal body -->
                </div> <!-- end modal content -->
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                    <table
                        id="state-saving-datatable"
                        class="table activate-select dt-responsive nowrap w-100"  >
                            <thead>
                                <tr>
                                    <th class="ps-0">Name</th>
                                    @if($user->role == 'admin')
                                        <th class="text-end">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($colleges as $college)
                                    <tr>
                                        <td class="ps-0  gap-2">
                                            <div class="d-flex align-items-center gap-1">
                                                @if($college->image)
                                                <img src="{{ asset('storage/' . $college->image) }}" width="32" height="32" class="rounded-circle">
                                            @else
                                                <i class="ph-duotone fs-24 ph-ranking"></i>
                                            @endif
                                            <div class="d-flex flex-column justify-content-center">
                                                <span class="font-13 fw-medium">{{$college->name}} ({{ $college->acronym }})</span>
                                            </div>
                                            </div>
                                        </td>
                                        @if($user->role == 'admin')
                                        <td class="text-end">
                                            <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" data-bs-toggle="modal" data-bs-target="#editModal"
                                               onclick="setEditFormAction('{{ route('colleges.update', $college->id) }}', '{{ $college->name }}', '{{ $college->acronym }}', '{{ $college->image }}')">
                                                <i class="ph ph-pencil fs-14 text-primary"></i>
                                            </a>
                                            <a aria-label="anchor" class="btn btn-sm bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                               onclick="setDeleteFormAction('{{ route('colleges.destroy', $college->id) }}')">
                                                <i class="ph-bold ph-trash fs-14 text-danger"></i>
                                            </a>
                                        </td>
                                        @endif
                                    </tr>



                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($user->role == 'admin')
                    <!-- DELETE MODAL -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Delete College</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete this college?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <form id="deleteForm" action="" method="POST" style="display:inline;" onsubmit="disableSubmitButton(this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- EDIT MODAL -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit College</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="editForm" action="" method="POST" onsubmit="disableSubmitButton(this)" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-2 d-flex justify-content-center align-items-center">
                                            <img id="ImagePreview{{ $college->id }}"
                                             onclick="document.getElementById('editImage').click();"
                                                src="{{ $college->image ? asset('storage/' . $college->image) : asset('storage/default.jpg') }}"
                                                width="100" height="100"
                                                class="rounded-circle object-fit-cover">
                                        </div>

                                        <div class="mb-2 ">
                                            <label for="editImage" class="form-label">Image</label>
                                            <input type="file" accept="image/*" name="image" class="form-control" id="editImage" onchange="previewImage(event, 'ImagePreview{{ $college->id }}')">
                                        </div>

                                        <div class="mb-3">
                                            <label for="editName" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" id="editName" placeholder="Enter College Name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editAcronym" class="form-label">Acronym</label>
                                            <input type="text" name="acronym" class="form-control" id="editAcronym" placeholder="Enter College Acronym" required>
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
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])
@endsection

@section('script-bottom')

<script>
    function setDeleteFormAction(action) {
        document.getElementById('deleteForm').action = action;
    }

    function setEditFormAction(action, name, acronym, image) {
        document.getElementById('editForm').action = action;
        document.getElementById('editName').value = name;

        document.getElementById('editAcronym').value = acronym;

        const preview = document.getElementById('EditImagePreview');
        if (image) {
            preview.src = "/storage/" + image; // Update with the correct path
            preview.style.display = "block"; // Show the image
        } else {
            preview.style.display = "none"; // Hide if no image
        }

    }

    function disableSubmitButton(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true; // Disable the button
        submitButton.innerText = 'Submitting...'; // Optional: Change button text
    }
</script>

<script>
    function previewImage(event, teamId) {
        const input = event.target;
        const preview = document.getElementById(teamId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]); // Read the image file
        }
    }
</script>


@endsection

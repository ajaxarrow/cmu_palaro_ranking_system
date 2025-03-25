@extends('layouts.admin', ['title' => 'Sports'])


@section('content')


<!-- Start Content-->
<div class="container-fluid">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Sports</h4>
        </div>

        @if($user->role == 'admin')
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
                    Add Sport
                </button>
            </div>


            <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalgridLabel">Add New Sport</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('sports.store')}}" method="POST" onsubmit="disableSubmitButton(this)">
                                @csrf
                                <div class="row g-3">
                                    <div class="mb-3">
                                        <div>
                                            <label for="firstName" class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" id="firstName" placeholder="Enter Sports Name" required>
                                        </div>
                                    </div><!--end col-->


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
                                    <th class="ps-0">Name</th>
                                    @if($user->role == 'admin')
                                        <th class="text-end">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sports as $sport)
                                    <tr>
                                        <td class="ps-0">
                                            <p class="d-inline-block align-middle mb-0">
                                                <span class="font-13 fw-medium">{{$sport->name}}</span>
                                            </p>
                                        </td>
                                            @if($user->role == 'admin')
                                            <td class="text-end">

                                                <div class="d-flex justify-content-end">
                                                    <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" href="{{ route('medal_records.by_sport', $sport->id) }}">
                                                        <i class="ph ph-eye fs-14 text-primary"></i>
                                                    </a>

                                                    <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" data-bs-toggle="modal" data-bs-target="#editModal-{{$sport->id}}">
                                                        <i class="ph ph-pencil fs-14 text-primary"></i>
                                                    </a>
                                                    <a aria-label="anchor" class="btn btn-sm bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$sport->id}}"
                                                    onclick="setDeleteFormAction('{{ route('sports.destroy', $sport->id) }}')">
                                                        <i class="ph-bold ph-trash fs-14 text-danger"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    @if($user->role == 'admin')
                                        <!-- EDIT MODAL -->
                                        <div class="modal fade" id="editModal-{{$sport->id}}" tabindex="-1" aria-labelledby="editModalLabel">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel">Edit Sport</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('sports.update', $sport->id)}}"  method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="row g-3">
                                                                <div class="mb-3">
                                                                    <div>
                                                                        <label for="firstName" class="form-label">Name</label>
                                                                        <input type="text" name="name" class="form-control" id="firstName" placeholder="Enter Sports Name" value="{{ $sport->name }}">
                                                                    </div>
                                                                </div><!--end col-->


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
                                        <!-- DELETE MODAL -->
                                        <div class="modal fade" id="deleteModal-{{$sport->id}}" tabindex="-1" aria-labelledby="deleteModalLabel">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel">Delete Sport</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this sport?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <form id="deleteForm" action="{{ route('sports.destroy', $sport->id)}}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
@endsection

@section('script-bottom')
<script>
    function setDeleteFormAction(action) {
        document.getElementById('deleteForm').action = action;
    }

    function disableSubmitButton(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true; // Disable the button
        submitButton.innerText = 'Submitting...'; // Optional: Change button text
    }
</script>
@endsection

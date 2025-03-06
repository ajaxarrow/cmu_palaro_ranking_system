@extends('layouts.admin', ['title' => 'Event Categories'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Event Categories</h4>
        </div>

        @if($user->role == 'admin')
            <div class="text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventCategoryModal">
                    Add Event Category
                </button>
            </div>
        @endif
    </div>
    @if($user->role == 'admin')
    <!-- Add Event Category Modal -->
    <div class="modal fade" id="addEventCategoryModal" tabindex="-1" aria-labelledby="addEventCategoryModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventCategoryModalLabel">Add Event Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('event_categories.store') }}" method="POST" onsubmit="disableSubmitButton(this)">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter Event Category Name" required>
                        </div>
                        <div class="mb-3">

                        <input type="hidden" name="is_double_bronze" value="0">
                        <input type="checkbox" name="is_double_bronze" value="1" id="isDoubleBronze" >
                            <label for="isDoubleBronze">Double Bronze</label>
                        </div>
                        <div class="mb-3">
                            <label for="sport_id" class="form-label">Sport</label>
                            <select name="sport_id" class="form-select" id="sport_id" required>
                                @foreach ($sports as $sport)
                                    <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="medal_weight_category_id" class="form-label">Medal Weight Category</label>
                            <select name="medal_weight_category_id" class="form-select" id="medal_weight_category_id" required>
                                @foreach ($medalWeightCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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
    @endif

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
                                    <th>Double Bronze</th>
                                    <th>Sport</th>
                                    <th>Medal Weight Category</th>
                                    @if($user->role == 'admin')
                                        <th class="text-end">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eventCategories as $eventCategory)
                                    <tr>
                                        <td class="ps-0">
                                            <p class="d-inline-block align-middle mb-0">
                                                <span class="font-13 fw-medium">{{ $eventCategory->name }}</span>
                                            </p>
                                        </td>
                                        <td class="">
                                            <i class="fe  fs-14"></i>
                                            <span class=" {{ $eventCategory->is_double_bronze ? 'mdi mdi-check-bold text-primary' : 'mdi mdi-close-thick text-danger' }}"> </span>


                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $eventCategory->sport->name }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $eventCategory->medalWeightCategory->name }}</p>
                                        </td>
                                        @if($user->role == 'admin')
                                            <td class="text-end">


                                                <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $eventCategory->id }}">
                                                    <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                                </a>
                                                <a aria-label="anchor" class="btn btn-sm bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $eventCategory->id }}">
                                                    <i class="mdi mdi-delete fs-14 text-danger"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>

                                    @if($user->role == 'admin')
                                        <!-- Edit Modal for each event category -->
                                        <div class="modal fade" id="editModal{{ $eventCategory->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $eventCategory->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editModalLabel{{ $eventCategory->id }}">Edit Event Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('event_categories.update', $eventCategory->id) }}" method="POST" onsubmit="disableSubmitButton(this)">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Name</label>
                                                                <input type="text" name="name" class="form-control" placeholder="Enter Event Category Name"  id="name" value="{{ $eventCategory->name }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <input type="hidden" name="is_double_bronze" value="0">
                                                                <input type="checkbox" name="is_double_bronze" value="1" id="isDoubleBronze{{ $eventCategory->id }}" {{ $eventCategory->is_double_bronze ? 'checked' : '' }}>
                                                                <label for="isDoubleBronze{{ $eventCategory->id }}">Double Bronze</label>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="sport_id" class="form-label">Sport</label>
                                                                <select name="sport_id" class="form-select" id="sport_id" required>
                                                                    @foreach ($sports as $sport)
                                                                        <option value="{{ $sport->id }}" {{ $eventCategory->sport_id == $sport->id ? 'selected' : '' }}>{{ $sport->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="medal_weight_category_id" class="form-label">Medal Weight Category</label>
                                                                <select name="medal_weight_category_id" class="form-select" id="medal_weight_category_id" required>
                                                                    @foreach ($medalWeightCategories as $category)
                                                                        <option value="{{ $category->id }}" {{ $eventCategory->medal_weight_category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                    @endforeach
                                                                </select>
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

                                        <!-- Delete Modal for each event category -->
                                        <div class="modal fade" id="deleteModal{{ $eventCategory->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $eventCategory->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $eventCategory->id }}">Delete Event Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete {{ $eventCategory->name }}?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <form action="{{ route('event_categories.destroy', $eventCategory->id) }}" method="POST" style="display:inline;" onsubmit="disableSubmitButton(this)">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
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
<script>
    function disableSubmitButton(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true; // Disable the button
        submitButton.innerText = 'Submitting...'; // Optional: Change button text
    }
</script>
@endsection


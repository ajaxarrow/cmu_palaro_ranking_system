@extends('layouts.admin', ['title' => 'Medal Weight Categories'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
@endsection

@section('content')

<!-- Start Content-->

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
<div class="container-fluid">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Medal Weight Categories</h4>
        </div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                Add Category
            </button>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Medal Weight Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('medal_weight_categories.store') }}" method="POST" onsubmit="disableSubmitButton(this)">
                        @csrf
                        <div class="mb-3">
                            <label for="category_no" class="form-label">Category No.</label>
                            <input type="number" name="category_no" class="form-control" id="category_no" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="gold_weight" class="form-label">Gold Weight</label>
                            <input type="number" step="0.01" name="gold_weight" class="form-control" id="gold_weight" required>
                        </div>
                        <div class="mb-3">
                            <label for="silver_weight" class="form-label">Silver Weight</label>
                            <input type="number" step="0.01" name="silver_weight" class="form-control" id="silver_weight" required>
                        </div>
                        <div class="mb-3">
                            <label for="bronze_weight" class="form-label">Bronze Weight</label>
                            <input type="number" step="0.01" name="bronze_weight" class="form-control" id="bronze_weight" required>
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

    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 checkbox-all" id="datatable_1">
                            <thead>
                                <tr>
                                    <th class="ps-0">Category No.</th>
                                    <th>Name</th>
                                    <th>Gold Weight</th>
                                    <th>Silver Weight</th>
                                    <th>Bronze Weight</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td class="ps-0">
                                            <p class="d-inline-block align-middle mb-0">
                                                <span class="font-13 fw-medium">{{$category->category_no}}</span>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $category->name }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $category->gold_weight }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $category->silver_weight }}</p>
                                        </td>
                                        <td>
                                            <p class="d-inline-block align-middle mb-0 text-body">{{ $category->bronze_weight }}</p>
                                        </td>
                                        <td class="text-end">
                                        
                                            <a aria-label="anchor" class="btn btn-sm bg-primary-subtle me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                                <i class="mdi mdi-pencil-outline fs-14 text-primary"></i>
                                            </a>
                                            <a aria-label="anchor" class="btn btn-sm bg-danger-subtle" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Edit Modal for each category -->
                                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $category->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $category->id }}">Edit Medal Weight Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('medal_weight_categories.update', $category->id) }}" method="POST" onsubmit="disableSubmitButton(this)">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="category_no" class="form-label">Category No.</label>
                                                            <input type="number" name="category_no" class="form-control" id="category_no" value="{{ $category->category_no }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Name</label>
                                                            <input type="text" name="name" class="form-control" id="name" value="{{ $category->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="gold_weight" class="form-label">Gold Weight</label>
                                                            <input type="number" step="0.01" name="gold_weight" class="form-control" id="gold_weight" value="{{ $category->gold_weight }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="silver_weight" class="form-label">Silver Weight</label>
                                                            <input type="number" step="0.01" name="silver_weight" class="form-control" id="silver_weight" value="{{ $category->silver_weight }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="bronze_weight" class="form-label">Bronze Weight</label>
                                                            <input type="number" step="0.01" name="bronze_weight" class="form-control" id="bronze_weight" value="{{ $category->bronze_weight }}" required>
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

                                    <!-- Delete Modal for each category -->
                                    <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $category->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $category->id }}">Delete Medal Weight Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete {{ $category->name }}?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <form action="{{ route('medal_weight_categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="disableSubmitButton(this)">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Confirm Delete</button>
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
@vite(['resources/js/pages/crm-dashboard.init.js'])
@endsection

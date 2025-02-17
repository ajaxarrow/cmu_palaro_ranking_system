@extends('layouts.admin', ['title' => 'Colleges'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
@endsection
@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Medal Weight Categories</h4>
        </div>

        {{-- <div class="text-end">
            <button type="button" class="btn btn-primary"
                onclick="location.href='{{ route('activity.create', ['event_id' => $event->id])}}'">Add</button>

        </div> --}}
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
                                            <button type="button" class="btn btn-primary">Edit</button>
                                            <a aria-label="anchor" class="btn btn-sm bg-danger-subtle"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delete-event-modal-{{$category->id}}">
                                                <i class="mdi mdi-delete fs-14 text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    {{-- Delete Modal Confirmation --}}
                                    <div class="modal fade"  tabindex="-1"
                                        aria-labelledby="standard-modalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="standard-modalLabel">Delete Medal Weight Category?
                                                    </h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Confirm deletion of {{$category->name}} ?</p>
                                                    <form

                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Confirm
                                                                Delete</button>
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

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])
@endsection

@section('script-bottom')
<script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
@vite(['resources/js/pages/crm-dashboard.init.js'])
@endsection

@extends('layouts.admin', ['title' => $team->name])

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
            <a class="fs-16 m-0" href="{{route('teams.index')}}">Back to Teams List</a>
        </div>
    </div>
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">{{$team->name}} Medal Tally</h4>
        </div>
    </div>

    <div class="row">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 checkbox-all" id="datatable_1">
                                <thead>
                                    <tr>
                                        <th class="ps-0">Sports</th>
                                        <th>Event Categories</th>
                                        <th colspan="2" class="text-center">Gold</th>
                                        <th colspan="2" class="text-center">Silver</th>
                                        <th colspan="2" class="text-center">Bronze</th>
                                        <th colspan="2" class="text-center">All Medal Tally</th>
                                    </tr>
                                    <tr>
                                        <th class="ps-0"></th>
                                        <th></th>
                                        <th class="text-center">Medal Count</th>
                                        <th class="text-center">Medal Weight</th>
                                        <th class="text-center">Medal Count</th>
                                        <th class="text-center">Medal Weight</th>
                                        <th class="text-center">Medal Count</th>
                                        <th class="text-center">Medal Weight</th>
                                        <th class="text-center">Total Medals</th>
                                        <th class="text-center">Total Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Basketball -->
                                    <tr>
                                        <td rowspan="3">Basketball</td>
                                        <td>Basketball Men 3x3</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">0.53</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">0.43</td>
                                        <td class="text-center">2</td> <!-- Total Medals -->
                                        <td class="text-center">0.96</td> <!-- Total Weight -->
                                    </tr>
                                    <tr>
                                        <td>Basketball Women 3x3</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">4.5</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1.25</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">2.3</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">8.05</td>
                                    </tr>
                                    <!-- Basketball Total -->
                                    <tr class="fw-bold table-secondary">
                                        <td>Total Basketball</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">5.03</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1.25</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">2.73</td>
                                        <td class="text-center">5</td>
                                        <td class="text-center">9.01</td>
                                    </tr>

                                    <!-- Volleyball -->
                                    <tr>
                                        <td rowspan="3">Volleyball</td>
                                        <td>Men</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1.24</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1.2</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">2.44</td>
                                    </tr>
                                    <tr>
                                        <td>Women</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">0</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">0.75</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">0.33</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">1.08</td>
                                    </tr>
                                    <!-- Volleyball Total -->
                                    <tr class="fw-bold table-secondary">
                                        <td>Total Volleyball</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">1.24</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">1.95</td>
                                        <td class="text-center">1</td>
                                        <td class="text-center">0.33</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">3.52</td>
                                    </tr>

                                    <!-- Grand Total for All Sports -->
                                    <tr class="fw-bold table-dark text-white">
                                        <td colspan="2">Overall Total</td>
                                        <td class="text-center">4</td>
                                        <td class="text-center">6.27</td>
                                        <td class="text-center">3</td>
                                        <td class="text-center">3.2</td>
                                        <td class="text-center">2</td>
                                        <td class="text-center">3.06</td>
                                        <td class="text-center">9</td>
                                        <td class="text-center">12.53</td>
                                    </tr>
                                </tbody>


                            </table>
                        </div>
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

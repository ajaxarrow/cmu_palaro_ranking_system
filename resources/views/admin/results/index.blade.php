@extends('layouts.admin', ['title' => 'Resuls'])

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
            <h4 class="fs-18 fw-semibold m-0">Results</h4>
        </div>
    </div>

    <div class="p-6">
  

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0" id="datatable_1">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Team</th>
                                    <th class="text-center">Gold Count</th>
                                    <th class="text-center">Gold Weight</th>
                                    <th class="text-center">Silver Count</th>
                                    <th class="text-center">Silver Weight</th>
                                    <th class="text-center">Bronze Count</th>
                                    <th class="text-center">Bronze Weight</th>
                                    <th class="text-center">Total Medals</th>
                                    <th class="text-center">Total Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                         

                      
                            @foreach ($medalTally as $sport => $data)
                                <tr>
                                    <td><strong>{{ $loop->iteration }} Place</strong></td>
                                    <td><strong>{{ $data['team'] }}</strong></td>
                                    <td class="text-center">{{ $data['total']['gold_tally'] }}</td>
                                    <td class="text-center">{{ number_format($data['total']['gold_weight'], 2) }}</td>
                                    <td class="text-center">{{ $data['total']['silver_tally'] }}</td>
                                    <td class="text-center">{{ number_format($data['total']['silver_weight'], 2) }}</td>
                                    <td class="text-center">{{ $data['total']['bronze_tally'] }}</td>
                                    <td class="text-center">{{ number_format($data['total']['bronze_weight'], 2) }}</td>
                                    <td class="text-center"><strong>{{ $data['total']['total_medals'] }}</strong></td>
                                    <td class="text-center"><strong>{{ number_format($data['total']['total_weight'], 2) }}</strong></td>
                                </tr>
                            @endforeach
       
                                <tr class="table-primary">
                                    <td><strong>Overall Total</strong></td>
                                    <td></td>
                                    <td class="text-center"><strong>29</strong></td>
                                    <td class="text-center"><strong>71.8</strong></td>
                                    <td class="text-center"><strong>23</strong></td>
                                    <td class="text-center"><strong>57.7</strong></td>
                                    <td class="text-center"><strong>18</strong></td>
                                    <td class="text-center"><strong>45.7</strong></td>
                                    <td class="text-center"><strong>70</strong></td>
                                    <td class="text-center"><strong>175.2</strong></td>
                                </tr>
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


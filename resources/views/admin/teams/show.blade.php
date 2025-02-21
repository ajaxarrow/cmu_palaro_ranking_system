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
                        <table class="table mb-0" id="datatable_1">
                            <thead>
                                <tr>
                                    <th>Sports</th>
                                    <th>Event Categories</th>
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
                                @foreach ($medalTally as $sport => $events)
                                    @foreach ($events as $index => $event)
                                        <tr>
                                            @if ($index == 0)
                                                <td rowspan="{{ count($events) }}">{{ $sport }}</td>
                                            @endif
                                            <td>{{ $event['event_category'] }}</td>
                                            <td class="text-center">{{ $event['gold_tally'] }}</td>
                                            <td class="text-center">{{ number_format($event['gold_weight'], 2) }}</td>
                                            <td class="text-center">{{ $event['silver_tally'] }}</td>
                                            <td class="text-center">{{ number_format($event['silver_weight'], 2) }}</td>
                                            <td class="text-center">{{ $event['bronze_tally'] }}</td>
                                            <td class="text-center">{{ number_format($event['bronze_weight'], 2) }}</td>
                                            <td class="text-center">{{ $event['total_medals'] }}</td>
                                            <td class="text-center">{{ number_format($event['total_weight'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
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

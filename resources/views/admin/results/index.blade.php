@extends('layouts.admin', ['title' => 'Results'])

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
        <!-- Add your content here -->
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Charts -->
    <div class="row">
        <!-- Basic Bar Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Team Ranks</h5>
                </div>
                <div class="card-body">
                    <div id="sport-result-bar" class="apex-charts"></div> 
                </div>
            </div>  
        </div>
    </div>

</div>

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])

<!-- Include ApexCharts from CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Prepare data for the chart
    var seriesData = [];
    var categoriesData = [];

    @foreach ($medalTally as $data)
        seriesData.push({{ $data['total']['total_weight'] }});
        categoriesData.push("{{ $data['team'] }}");
    @endforeach

    // Check if data is populated
    console.log('Series Data:', seriesData);
    console.log('Categories Data:', categoriesData);

    // Count the seriesData and create random colors
    var colors = [];
    for (var i = 0; i < seriesData.length; i++) {
        var color = "#" + Math.floor(Math.random()*16777215).toString(16);
        colors.push(color);
    }

    // ApexCharts initialization
    var options = {
        series: [{
            data: seriesData // Use the series data from the medal tally
        }],
        chart: {
            type: 'bar',
            height: 500,
            parentHeightOffset: 0,
        },
        plotOptions: {
            bar: {
                borderRadius: 100,
                borderRadiusApplication: 'end',
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: true
        },
        colors: colors, 
        xaxis: {
            categories: categoriesData // Use the categories from the medal tally
        }
    };

    // Render the chart
    var chart = new ApexCharts(document.querySelector("#sport-result-bar"), options);
    chart.render().catch(function(error) {
        console.error('Chart rendering error:', error);
    });
</script>

@endsection


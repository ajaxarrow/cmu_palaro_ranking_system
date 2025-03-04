@extends('layouts.admin', ['title' => $team->name])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])
<style>
 
</style>
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

    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                          <a class="btn btn-outline-primary mb-2" href="{{route('teams.index')}}">
                                <i class="mdi mdi-arrow-left me-1"></i> Back to Teams List
                            </a>
                        <div>
                          
                            <h3 class="fw-bold mb-0">🏆 {{$team->name}} Medal Dashboard</h3>
                        </div>

                      
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center me-4">
                                <div class="medal-icon medal-gold me-2">G</div>
                                <span>Gold</span>
                            </div>
                            <div class="d-flex align-items-center me-4">
                                <div class="medal-icon medal-silver me-2">S</div>
                                <span>Silver</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="medal-icon medal-bronze me-2">B</div>
                                <span>Bronze</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medal Summary Cards -->
    <div class="row mb-4">
        <!-- Gold Medal Summary -->
        <div class="col-md-3">
            <div class="card medal-summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="medal-icon medal-gold" style="width: 48px; height: 48px; font-size: 20px;">G</div>
                        </div>
                        <div>
                            <h5 class="mb-1">Gold Medals</h5>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-3 fw-bold me-2">{{ $overallTotal['gold_tally'] }}</span>
                                <span class="text-muted">medals</span>
                            </div>
                            <div class="text-muted">Weight: {{ number_format($overallTotal['gold_weight'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Silver Medal Summary -->
        <div class="col-md-3">
            <div class="card medal-summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="medal-icon medal-silver" style="width: 48px; height: 48px; font-size: 20px;">S</div>
                        </div>
                        <div>
                            <h5 class="mb-1">Silver Medals</h5>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-3 fw-bold me-2">{{ $overallTotal['silver_tally'] }}</span>
                                <span class="text-muted">medals</span>
                            </div>
                            <div class="text-muted">Weight: {{ number_format($overallTotal['silver_weight'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bronze Medal Summary -->
        <div class="col-md-3">
            <div class="card medal-summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="medal-icon medal-bronze" style="width: 48px; height: 48px; font-size: 20px;">B</div>
                        </div>
                        <div>
                            <h5 class="mb-1">Bronze Medals</h5>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-3 fw-bold me-2">{{ $overallTotal['bronze_tally'] }}</span>
                                <span class="text-muted">medals</span>
                            </div>
                            <div class="text-muted">Weight: {{ number_format($overallTotal['bronze_weight'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Medal Summary -->
        <div class="col-md-3">
            <div class="card medal-summary-card custom-primary-background text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="medal-icon" style="width: 48px; height: 48px; font-size: 20px; background-color: rgba(255,255,255,0.2); color: white;">T</div>
                        </div>
                        <div>
                            <h5 class="mb-1">Total Medals</h5>
                            <div class="d-flex align-items-baseline">
                                <span class="fs-3 fw-bold me-2">{{ $overallTotal['total_medals'] }}</span>
                                <span>medals</span>
                            </div>
                            <div>Weight: {{ number_format($overallTotal['total_weight'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Charts Row -->
    <div class="row mb-4">
        
        <div class="col-12">
            <!-- Bar Chart Container -->
            
            <h3 class="p-2">Medal Distribution Charts</h3>
            <div class="chart-toggle">
                        <button class="chart-tab active me-2" data-target="sports-table">Sports Table</button>
                        <button class="chart-tab" data-target="bar-chart">Medal Distribution</button>
                        <button class="chart-tab" data-target="donut-chart">Medal Breakdown</button>
                        <button class="chart-tab" data-target="sports-chart">Sports Analysis</button>
             
            </div>
            <div id="bar-chart" class="chart-container active">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Medal Distribution by Type</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="toggleWeight">
                            <label class="form-check-label" for="toggleWeight">Show Weight</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="medal-distribution-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <!-- Donut Chart Container -->
            <div id="donut-chart" class="chart-container">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Medal Type Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="medal-count-chart" style="height: 350px;"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="medal-weight-chart" style="height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sports Analysis Chart -->
            <div id="sports-chart" class="chart-container">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Sports Performance Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div id="sports-analysis-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>



                <!-- Detailed Medal Table -->

        <div id="sports-table" class="chart-container">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detailed Medal Breakdown by Sport & Event</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table
                        id="saving-datatable-shesh"
                        class="table activate-select dt-responsive nowrap w-100" >
                            <thead>
                                <tr>
                                    <th>Sports</th>
                                    <th>Event Categories</th>
                                    <th class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="medal-icon medal-gold me-2" style="width: 22px; height: 22px; font-size: 12px;">G</div>
                                            <span>Count</span>
                                        </div>
                                    </th>
                                    <th class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="medal-icon medal-gold me-2" style="width: 22px; height: 22px; font-size: 12px;">G</div>
                                            <span>Weight</span>
                                        </div>
                                    </th>
                                    <th class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="medal-icon medal-silver me-2" style="width: 22px; height: 22px; font-size: 12px;">S</div>
                                            <span>Count</span>
                                        </div>
                                    </th>
                                    <th class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="medal-icon medal-silver me-2" style="width: 22px; height: 22px; font-size: 12px;">S</div>
                                            <span>Weight</span>
                                        </div>
                                    </th>
                                    <th class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="medal-icon medal-bronze me-2" style="width: 22px; height: 22px; font-size: 12px;">B</div>
                                            <span>Count</span>
                                        </div>
                                    </th>
                                    <th class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="medal-icon medal-bronze me-2" style="width: 22px; height: 22px; font-size: 12px;">B</div>
                                            <span>Weight</span>
                                        </div>
                                    </th>
                                    <th class="text-center">Total Medals</th>
                                    <th class="text-center">Total Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medalTally as $sport => $data)
                                    @foreach ($data['events'] as $index => $event)
                                        <tr>
                                            @if ($index == 0)
                                                <td rowspan="{{ count($data['events']) + 1 }}"><strong>{{ $sport }}</strong></td>
                                            @endif
                                            <td>{{ $event['event_category'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">{{ $event['gold_tally'] }}</span>
                                            </td>
                                            <td class="text-center">{{ number_format($event['gold_weight'], 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $event['silver_tally'] }}</span>
                                            </td>
                                            <td class="text-center">{{ number_format($event['silver_weight'], 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-danger-subtle text-dark">{{ $event['bronze_tally'] }}</span>
                                            </td>
                                            <td class="text-center">{{ number_format($event['bronze_weight'], 2) }}</td>
                                            <td class="text-center">
                                                <strong>{{ $event['total_medals'] }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ number_format($event['total_weight'], 2) }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <!-- Sport Total Row -->
                                    <tr class="table-secondary">
                                        <td><strong>Total for {{ $sport }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">{{ $data['total']['gold_tally'] }}</span>
                                        </td>
                                        <td class="text-center"><strong>{{ number_format($data['total']['gold_weight'], 2) }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $data['total']['silver_tally'] }}</span>
                                        </td>
                                        <td class="text-center"><strong>{{ number_format($data['total']['silver_weight'], 2) }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-dark">{{ $data['total']['bronze_tally'] }}</span>
                                        </td>
                                        <td class="text-center"><strong>{{ number_format($data['total']['bronze_weight'], 2) }}</strong></td>
                                        <td class="text-center"><strong>{{ $data['total']['total_medals'] }}</strong></td>
                                        <td class="text-center"><strong>{{ number_format($data['total']['total_weight'], 2) }}</strong></td>
                                    </tr>
                                @endforeach
                                <!-- Overall Total Row -->
                                <tr class="table-primary">
                                    <td colspan="2"><strong>Overall Total</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">{{ $overallTotal['gold_tally'] }}</span>
                                    </td>
                                    <td class="text-center"><strong>{{ number_format($overallTotal['gold_weight'], 2) }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $overallTotal['silver_tally'] }}</span>
                                    </td>
                                    <td class="text-center"><strong>{{ number_format($overallTotal['silver_weight'], 2) }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-dark">{{ $overallTotal['bronze_tally'] }}</span>
                                    </td>
                                    <td class="text-center"><strong>{{ number_format($overallTotal['bronze_weight'], 2) }}</strong></td>
                                    <td class="text-center"><strong>{{ $overallTotal['total_medals'] }}</strong></td>
                                    <td class="text-center"><strong>{{ number_format($overallTotal['total_weight'], 2) }}</strong></td>
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

<!-- Include ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Prepare data for charts using PHP data
    const medalData = {
        goldCount: {{ $overallTotal['gold_tally'] }},
        goldWeight: {{ $overallTotal['gold_weight'] }},
        silverCount: {{ $overallTotal['silver_tally'] }},
        silverWeight: {{ $overallTotal['silver_weight'] }},
        bronzeCount: {{ $overallTotal['bronze_tally'] }},
        bronzeWeight: {{ $overallTotal['bronze_weight'] }},
        totalCount: {{ $overallTotal['total_medals'] }},
        totalWeight: {{ $overallTotal['total_weight'] }}
    };

    // Prepare sports data
    const sportsData = [];
    @foreach ($medalTally as $sport => $data)
        sportsData.push({
            name: "{{ $sport }}",
            gold: {{ $data['total']['gold_tally'] }},
            goldWeight: {{ $data['total']['gold_weight'] }},
            silver: {{ $data['total']['silver_tally'] }},
            silverWeight: {{ $data['total']['silver_weight'] }},
            bronze: {{ $data['total']['bronze_tally'] }},
            bronzeWeight: {{ $data['total']['bronze_weight'] }},
            total: {{ $data['total']['total_medals'] }},
            totalWeight: {{ $data['total']['total_weight'] }}
        });
    @endforeach

    // Track chart instances to destroy them before recreating
    let medalDistributionChart = null;
    let medalCountChart = null;
    let medalWeightChart = null;
    let sportsAnalysisChart = null;

    // Chart toggle functionality
    function setupChartTabs() {
        document.querySelectorAll('.chart-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.chart-tab').forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all chart containers
                document.querySelectorAll('.chart-container').forEach(container => {
                    container.classList.remove('active');
                });
                
                // Show target chart container
                const targetId = this.getAttribute('data-target');
                document.getElementById(targetId).classList.add('active');
                
                // Manually trigger resize for the visible chart
                window.dispatchEvent(new Event('resize'));
            });
        });
    }

    // 1. Medal Distribution Bar Chart
    function renderMedalDistributionChart(showWeight = false) {
        const chartElement = document.querySelector("#medal-distribution-chart");
        if (!chartElement) return;
        
        // Clear previous chart if it exists
        if (medalDistributionChart) {
            medalDistributionChart.destroy();
        }
        
        const data = showWeight ? 
            [medalData.goldWeight, medalData.silverWeight, medalData.bronzeWeight] : 
            [medalData.goldCount, medalData.silverCount, medalData.bronzeCount];
        
        const labels = ['Gold', 'Silver', 'Bronze'];
        const colors = ['#FFB800', '#B7B7B7', '#CD7F32'];
        
        const options = {
            series: [{
                name: showWeight ? 'Medal Weight' : 'Medal Count',
                data: data
            }],
            chart: {
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    columnWidth: '60%',
                    borderRadius: 8,
                    distributed: true,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return showWeight ? val.toFixed(2) : val;
                },
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    colors: ['#333']
                }
            },
            legend: {
                show: false
            },
            colors: colors,
            xaxis: {
                categories: labels,
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: '600',
                        colors: colors
                    }
                }
            },
            yaxis: {
                title: {
                    text: showWeight ? 'Weight' : 'Count',
                    style: {
                        fontSize: '14px',
                        fontWeight: '600'
                    }
                }
            },
            title: {
                text: showWeight ? 'Medal Distribution by Weight' : 'Medal Distribution by Count',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: '600'
                }
            }
        };
        
        // Render chart
        medalDistributionChart = new ApexCharts(chartElement, options);
        medalDistributionChart.render();
    }

    // 2. Donut Charts for Medal Breakdown
    function renderDonutCharts() {
        const countElement = document.querySelector("#medal-count-chart");
        const weightElement = document.querySelector("#medal-weight-chart");
        
        if (!countElement || !weightElement) return;
        
        // Clear previous charts if they exist
        if (medalCountChart) {
            medalCountChart.destroy();
        }
        if (medalWeightChart) {
            medalWeightChart.destroy();
        }
        
        // Medal count donut chart
        const countOptions = {
            series: [medalData.goldCount, medalData.silverCount, medalData.bronzeCount],
            chart: {
                type: 'donut',
                height: 350,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            labels: ['Gold', 'Silver', 'Bronze'],
            colors: ['#FFB800', '#B7B7B7', '#CD7F32'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '55%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Medals',
                                formatter: function() {
                                    return medalData.totalCount;
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opt) {
                    return opt.w.globals.labels[opt.seriesIndex] + ": " + opt.w.globals.series[opt.seriesIndex];
                }
            },
            legend: {
                position: 'bottom'
            },
            title: {
                text: 'Medal Count Distribution',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: '600'
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    }
                }
            }]
        };

        // Medal weight donut chart
        const weightOptions = {
            series: [
                parseFloat(medalData.goldWeight.toFixed(2)), 
                parseFloat(medalData.silverWeight.toFixed(2)), 
                parseFloat(medalData.bronzeWeight.toFixed(2))
            ],
            chart: {
                type: 'donut',
                height: 350,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            labels: ['Gold', 'Silver', 'Bronze'],
            colors: ['#FFB800', '#B7B7B7', '#CD7F32'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '55%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Weight',
                                formatter: function() {
                                    return medalData.totalWeight.toFixed(2);
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val, opt) {
                    return opt.w.globals.labels[opt.seriesIndex] + ": " + opt.w.globals.series[opt.seriesIndex].toFixed(2);
                }
            },
            legend: {
                position: 'bottom'
            },
            title: {
                text: 'Medal Weight Distribution',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: '600'
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 300
                    }
                }
            }]
        };
        
        // Render charts
        medalCountChart = new ApexCharts(countElement, countOptions);
        medalWeightChart = new ApexCharts(weightElement, weightOptions);
        
        medalCountChart.render();
        medalWeightChart.render();
    }

    // 3. Sports Analysis Chart
    function renderSportsAnalysisChart() {
        const chartElement = document.querySelector("#sports-analysis-chart");
        if (!chartElement) return;
        
        // Clear previous chart if it exists
        if (sportsAnalysisChart) {
            sportsAnalysisChart.destroy();
        }
        
        // Sort sports by total medals
        const sortedSports = [...sportsData].sort((a, b) => b.total - a.total);
        
        const options = {
            series: [{
                name: 'Gold',
                data: sortedSports.map(sport => sport.gold),
                color: '#FFB800'
            }, {
                name: 'Silver',
                data: sortedSports.map(sport => sport.silver),
                color: '#B7B7B7'
            }, {
                name: 'Bronze',
                data: sortedSports.map(sport => sport.bronze),
                color: '#CD7F32'
            }],
            chart: {
                type: 'bar',
                height: 350,
                stacked: true,
                toolbar: {
                    show: false
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: sortedSports.map(sport => sport.name),
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Medal Count'
                }
            },
            fill: {
                opacity: 1
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center'
            },
            title: {
                text: 'Medal Distribution by Sport',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: '600'
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " medals";
                    }
                }
            }
        };
        
        // Render chart
        sportsAnalysisChart = new ApexCharts(chartElement, options);
        sportsAnalysisChart.render();
    }

    // Initialize everything when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Show sports table by default
        const sportsTableTab = document.querySelector('[data-target="sports-table"]');
        if (sportsTableTab) {
            // Remove active class from all tabs and containers
            document.querySelectorAll('.chart-tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.chart-container').forEach(container => container.classList.remove('active'));
            
            // Add active class to sports table tab and container
            sportsTableTab.classList.add('active');
            document.getElementById('sports-table').classList.add('active');
        }

        // Initialize charts after setting default view
        if (typeof ApexCharts !== 'undefined') {
            initializeCharts();
        } else {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/apexcharts';
            script.onload = initializeCharts;
            document.head.appendChild(script);
        }
    });

    // Function to initialize all charts
    function initializeCharts() {
        // Set up the tab switching
        setupChartTabs();
        
        // Render initial charts
        setTimeout(() => {
            renderMedalDistributionChart(false);
            renderDonutCharts();
            renderSportsAnalysisChart();
        }, 100);
        
        // Set up weight toggle for bar chart
        const toggleWeight = document.getElementById('toggleWeight');
        if (toggleWeight) {
            toggleWeight.addEventListener('change', function() {
                renderMedalDistributionChart(this.checked);
            });
        }
        
        // Add window resize handler to ensure charts are properly sized
        window.addEventListener('resize', function() {
            if (medalDistributionChart) medalDistributionChart.render();
            if (medalCountChart) medalCountChart.render();
            if (medalWeightChart) medalWeightChart.render();
            if (sportsAnalysisChart) sportsAnalysisChart.render();
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Initialize DataTable with sport-based grouping
            const dataTable = new simpleDatatables.DataTable("#saving-datatable-shesh", {
                searchable: true,
                fixedHeight: false,
                perPage: false, // Disable default pagination
                columns: [
                    { 
                        select: 0, 
                        sortable: false, // Disable sorting for sports column
                        render: function(data, cell, row) {
                            return data; // Preserve rowspan
                        }
                    },
                    { select: 1, sortable: true },
                    { select: [2,3,4,5,6,7,8,9], type: "number", sortable: true }
                ],
                labels: {
                    placeholder: "Search medal records...",
                    noRows: "No medal records found",
                    info: "Showing {start} to {end} of {rows} records"
                }
            });

            // Add custom sport-based pagination
            addSportBasedPagination(dataTable);

        } catch (error) {
            console.error("Error initializing DataTable:", error);
            fallbackToBasicTable();
        }
    });

    // Function to add sport-based pagination
    function addSportBasedPagination(table) {
        const tableElement = document.querySelector('#saving-datatable-shesh');
        const tbody = tableElement.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        // Group rows by sports
        let currentSport = '';
        let sportsGroups = {};
        let visibleSports = [];
        
        rows.forEach(row => {
            const firstCell = row.querySelector('td:first-child');
            if (firstCell) {
                const sportName = firstCell.textContent.trim();
                if (!sportName.includes('Total for') && sportName !== 'Overall Total') {
                    if (sportName !== '') {
                        currentSport = sportName;
                        if (!sportsGroups[currentSport]) {
                            sportsGroups[currentSport] = [];
                            visibleSports.push(currentSport);
                        }
                    }
                    sportsGroups[currentSport].push(row);
                }
            }
        });

        // Create pagination controls
        const paginationContainer = document.createElement('div');
        paginationContainer.className = 'sport-pagination mb-3';
        tableElement.parentNode.insertBefore(paginationContainer, tableElement);

        // Add "Show All" button
        const showAllBtn = document.createElement('button');
        showAllBtn.className = 'btn btn-sm btn-outline-primary me-2';
        showAllBtn.textContent = 'Show All Sports';
        showAllBtn.onclick = () => showAllSports();
        paginationContainer.appendChild(showAllBtn);

        // Add sport buttons
        visibleSports.forEach(sport => {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm btn-outline-secondary me-2';
            btn.textContent = sport;
            btn.onclick = () => showSport(sport);
            paginationContainer.appendChild(btn);
        });

        // Function to show specific sport
        function showSport(sportName) {
            // Hide all rows first
            rows.forEach(row => row.style.display = 'none');

            // Show selected sport rows
            if (sportsGroups[sportName]) {
                sportsGroups[sportName].forEach(row => row.style.display = '');
            }

            // Show total row for this sport
            const totalRow = Array.from(tbody.querySelectorAll('tr')).find(row => 
                row.querySelector('td:first-child')?.textContent.includes(`Total for ${sportName}`)
            );
            if (totalRow) totalRow.style.display = '';

            // Update active button state
            updateActiveButton(sportName);
        }

        // Function to show all sports
        function showAllSports() {
            rows.forEach(row => row.style.display = '');
            updateActiveButton('all');
        }

        // Function to update active button state
        function updateActiveButton(activeSport) {
            const buttons = paginationContainer.querySelectorAll('button');
            buttons.forEach(btn => {
                btn.classList.remove('btn-primary', 'btn-outline-primary', 'btn-secondary', 'btn-outline-secondary');
                if ((activeSport === 'all' && btn.textContent === 'Show All Sports') ||
                    (btn.textContent === activeSport)) {
                    btn.classList.add('btn-primary');
                } else {
                    btn.classList.add('btn-outline-secondary');
                }
            });
        }

        // Show all sports initially
        showAllSports();

        // Add custom styling
        const style = document.createElement('style');
        style.textContent = `
            .sport-pagination {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                padding: 1rem;
                background: #f8f9fa;
                border-radius: 0.5rem;
            }
            .sport-pagination button {
                transition: all 0.3s ease;
            }
            .sport-pagination button:hover {
                transform: translateY(-1px);
            }
            .table tbody tr {
                transition: all 0.3s ease;
            }
            .table tbody tr:hover {
                background-color: rgba(0, 123, 255, 0.05);
                }
            `;
            document.head.appendChild(style);
    }

    // Basic fallback function if needed
    function fallbackToBasicTable() {
        const table = document.getElementById("saving-datatable-shesh");
        if (!table) return;

        // Add basic search functionality
        const searchInput = document.createElement("input");
        searchInput.type = "text";
        searchInput.placeholder = "Search...";
        searchInput.className = "form-control mb-3";
        table.parentNode.insertBefore(searchInput, table);

        searchInput.addEventListener("keyup", function() {
            const searchTerm = this.value.toLowerCase();
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? "" : "none";
            }
        });
    }
</script>

<script>
// Initialize the datatable with custom configuration
document.addEventListener('DOMContentLoaded', function() {
    const medalTable = new simpleDatatables.DataTable("#saving-datatable-shesh", {
        searchable: true,
        fixedHeight: false,
        perPage: 10,
        perPageSelect: [5, 10, 15, 20, 25, 50],
        columns: [
            // Define column configurations
            { select: 0, sort: "asc" },  // Sports column
            { select: 1, sortable: true }, // Event Categories
            { select: 2, type: "number", sortable: true }, // Gold Count
            { select: 3, type: "number", sortable: true }, // Gold Weight
            { select: 4, type: "number", sortable: true }, // Silver Count
            { select: 5, type: "number", sortable: true }, // Silver Weight
            { select: 6, type: "number", sortable: true }, // Bronze Count
            { select: 7, type: "number", sortable: true }, // Bronze Weight
            { select: 8, type: "number", sortable: true }, // Total Medals
            { select: 9, type: "number", sortable: true }  // Total Weight
        ],
        labels: {
            placeholder: "Search medal records...",
            perPage: "Records per page: {select}",
            noRows: "No medal records found",
            info: "Showing {start} to {end} of {rows} records"
        },
        layout: {
            top: "{search}{select}",
            bottom: "{info}{pager}"
        }
    });

    // Create custom filter elements
    createCustomFilters(medalTable);
});

// Function to create custom filter elements
function createCustomFilters(table) {
    // Create filter container
    const filterContainer = document.createElement('div');
    filterContainer.className = 'mb-3 p-2 border rounded bg-light d-flex flex-wrap align-items-center';
    
    // Add filter title
    const filterTitle = document.createElement('div');
    filterTitle.className = 'me-3 fw-bold';
    filterTitle.textContent = 'Filters:';
    filterContainer.appendChild(filterTitle);
    
    // Create sport filter
    const sportFilter = createFilterSelect('sportFilter', 'Sport:', getUniqueValues(table, 0));
    filterContainer.appendChild(sportFilter);
    
    // Create medal type filters
    const medalTypes = ['All Medals', 'Gold', 'Silver', 'Bronze'];
    const medalFilter = createFilterSelect('medalFilter', 'Medal Type:', medalTypes);
    filterContainer.appendChild(medalFilter);
    
    // Create toggle for showing only totals
    const totalsContainer = document.createElement('div');
    totalsContainer.className = 'form-check ms-3';
    
    const totalsCheckbox = document.createElement('input');
    totalsCheckbox.type = 'checkbox';
    totalsCheckbox.className = 'form-check-input';
    totalsCheckbox.id = 'showTotalsOnly';
    
    const totalsLabel = document.createElement('label');
    totalsLabel.className = 'form-check-label';
    totalsLabel.htmlFor = 'showTotalsOnly';
    totalsLabel.textContent = 'Show Totals Only';
    
    totalsContainer.appendChild(totalsCheckbox);
    totalsContainer.appendChild(totalsLabel);
    filterContainer.appendChild(totalsContainer);
    
    // Create reset filters button
    const resetButton = document.createElement('button');
    resetButton.className = 'btn btn-sm btn-outline-secondary ms-auto';
    resetButton.textContent = 'Reset Filters';
    filterContainer.appendChild(resetButton);
    
    // Insert the filter container before the table
    const tableWrapper = document.querySelector('#saving-datatable-shesh').closest('.table-responsive');
    tableWrapper.parentNode.insertBefore(filterContainer, tableWrapper);
    
    // Add event listeners for filters
    document.getElementById('sportFilter').addEventListener('change', function() {
        applyFilters(table);
    });
    
    document.getElementById('medalFilter').addEventListener('change', function() {
        applyFilters(table);
    });
    
    document.getElementById('showTotalsOnly').addEventListener('change', function() {
        applyFilters(table);
    });
    
    resetButton.addEventListener('click', function() {
        document.getElementById('sportFilter').value = '';
        document.getElementById('medalFilter').value = '';
        document.getElementById('showTotalsOnly').checked = false;
        applyFilters(table);
    });
}

// Function to create a filter select dropdown
function createFilterSelect(id, label, options) {
    const container = document.createElement('div');
    container.className = 'me-3';
    
    const labelElement = document.createElement('label');
    labelElement.className = 'me-1';
    labelElement.htmlFor = id;
    labelElement.textContent = label;
    container.appendChild(labelElement);
    
    const select = document.createElement('select');
    select.className = 'form-select form-select-sm d-inline-block w-auto';
    select.id = id;
    
    // Add default "All" option
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'All';
    select.appendChild(defaultOption);
    
    // Add options from provided array
    options.forEach(option => {
        if (option) {  // Check if option is not empty/null
            const optionElement = document.createElement('option');
            optionElement.value = option;
            optionElement.textContent = option;
            select.appendChild(optionElement);
        }
    });
    
    container.appendChild(select);
    return container;
}

// Function to get unique values from a specific column
function getUniqueValues(table, columnIndex) {
    const data = table.data;
    const uniqueValues = new Set();
    
    // For each row in the data
    for (let i = 0; i < data.data.length; i++) {
        const rowData = data.data[i];
        const cellValue = rowData.cells[columnIndex].data;
        
        // Extract the sport name (remove "Total for " prefix if present)
        let value = cellValue;
        if (value.includes('Total for ')) {
            value = value.replace('Total for ', '');
        } else if (value === 'Overall Total') {
            continue; // Skip the overall total row
        }
        
        uniqueValues.add(value.trim());
    }
    
    return Array.from(uniqueValues).sort();
}

// Function to apply all filters
function applyFilters(table) {
    const sportFilter = document.getElementById('sportFilter').value;
    const medalFilter = document.getElementById('medalFilter').value;
    const showTotalsOnly = document.getElementById('showTotalsOnly').checked;
    
    // Apply custom filter to the table
    table.search(''); // Clear the search first
    
    // Use the DataTable API to filter
    table.data.data.forEach((row, index) => {
        let show = true;
        
        // Get the content of the first cell (Sport)
        const sportCell = row.cells[0].data;
        
        // Check if it's a sport row, total row, or event row
        const isTotalRow = sportCell.includes('Total for ') || sportCell === 'Overall Total';
        const isEventRow = !isTotalRow && sportCell !== '';
        
        // Filter by show totals only
        if (showTotalsOnly && !isTotalRow) {
            show = false;
        }
        
        // Filter by sport
        if (sportFilter && !sportCell.includes(sportFilter) && !sportCell.includes('Total for ' + sportFilter)) {
            show = false;
        }
        
        // Filter by medal type
        if (medalFilter && medalFilter !== 'All Medals') {
            // Determine which columns to check based on medal type
            let columnToCheck;
            if (medalFilter === 'Gold') columnToCheck = 2;
            else if (medalFilter === 'Silver') columnToCheck = 4;
            else if (medalFilter === 'Bronze') columnToCheck = 6;
            
            // Check if medals of this type exist
            const medalCount = parseInt(row.cells[columnToCheck].data.replace(/[^\d]/g, ''), 10);
            if (isNaN(medalCount) || medalCount === 0) {
                show = false;
            }
        }
        
        // Update row visibility
        if (show) {
            row.hidden = false;
        } else {
            row.hidden = true;
        }
    });
    
    // Refresh the table
    table.update();
    
    // Fix the rowspan issues with dynamic rows
    handleRowspans(table);
}

// Function to handle rowspans when filtering
function handleRowspans(table) {
    // Get all the table rows
    const tableElement = document.querySelector('#saving-datatable-shesh');
    const rows = tableElement.querySelectorAll('tbody tr');
    
    // Reset all cells that might have rowspan
    rows.forEach(row => {
        const firstCell = row.querySelector('td:first-child');
        if (firstCell && firstCell.hasAttribute('rowspan')) {
            firstCell.removeAttribute('rowspan');
        }
    });
    
    // Recalculate rowspans for visible rows
    let currentSport = '';
    let sportStartIndex = -1;
    let visibleCount = 0;
    
    rows.forEach((row, index) => {
        // Skip hidden rows
        if (row.style.display === 'none') return;
        
        const firstCell = row.querySelector('td:first-child');
        if (!firstCell) return;
        
        // If this is a sport cell (not empty and not a total row)
        if (firstCell.textContent && !firstCell.textContent.includes('Total for ') && firstCell.textContent !== 'Overall Total') {
            if (firstCell.textContent !== currentSport) {
                // New sport found
                currentSport = firstCell.textContent;
                sportStartIndex = index;
                visibleCount = 1;
            } else {
                // Increment visible count for current sport
                visibleCount++;
                
                // Hide this cell as it's redundant
                firstCell.style.display = 'none';
            }
        } else if (firstCell.textContent.includes('Total for ' + currentSport)) {
            // This is the total row for the current sport
            // Apply rowspan to the first instance of this sport
            const firstInstance = rows[sportStartIndex].querySelector('td:first-child');
            if (firstInstance) {
                firstInstance.setAttribute('rowspan', visibleCount + 1);
            }
            
            // Reset for next sport
            currentSport = '';
            sportStartIndex = -1;
            visibleCount = 0;
        }
    });
}

// Function to enhance the medal display with better badges
function enhanceMedalDisplay() {
    // Get all medal badge elements
    const goldBadges = document.querySelectorAll('.badge.bg-warning.text-dark');
    const silverBadges = document.querySelectorAll('.badge.bg-light.text-dark');
    const bronzeBadges = document.querySelectorAll('.badge.bg-danger-subtle.text-dark');
    
    // Enhance gold badges
    goldBadges.forEach(badge => {
        if (parseInt(badge.textContent) > 0) {
            badge.classList.add('medal-badge', 'gold-badge');
            badge.innerHTML = `<i class="mdi mdi-medal me-1"></i>${badge.textContent}`;
        }
    });
    
    // Enhance silver badges
    silverBadges.forEach(badge => {
        if (parseInt(badge.textContent) > 0) {
            badge.classList.add('medal-badge', 'silver-badge');
            badge.innerHTML = `<i class="mdi mdi-medal me-1"></i>${badge.textContent}`;
        }
    });
    
    // Enhance bronze badges
    bronzeBadges.forEach(badge => {
        if (parseInt(badge.textContent) > 0) {
            badge.classList.add('medal-badge', 'bronze-badge');
            badge.innerHTML = `<i class="mdi mdi-medal me-1"></i>${badge.textContent}`;
        }
    });
}

// Call medal display enhancement after table initialization
document.addEventListener('DOMContentLoaded', function() {
    // Add a slight delay to ensure the table is fully rendered
    setTimeout(enhanceMedalDisplay, 500);
});

// Add custom CSS for better styling
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .medal-badge {
            transition: all 0.3s ease;
        }
        .medal-badge:hover {
            transform: scale(1.1);
        }
        .gold-badge {
            background-color: #FFD700 !important;
            color: #000 !important;
        }
        .silver-badge {
            background-color: #C0C0C0 !important;
            color: #000 !important;
        }
        .bronze-badge {
            background-color: #CD7F32 !important;
            color: #fff !important;
        }
        
        /* Datatable enhancements */
        .dataTable-wrapper .dataTable-top {
            padding: 12px 0;
        }
        .dataTable-input {
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        .dataTable-selector {
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #ced4da;
            margin-left: 10px;
        }
        .dataTable-pagination {
            margin-top: 15px;
        }
        .dataTable-pagination a {
            border-radius: 4px;
            margin: 0 2px;
        }
        
        /* Highlight sorting columns */
        th.dataTable-sorter.dataTable-sorter-active {
            background-color: rgba(0, 123, 255, 0.05);
        }
        
        /* Row highlighting */
        tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.03);
        }
        .table-secondary:hover {
            background-color: rgba(0, 123, 255, 0.08) !important;
        }
        .table-primary:hover {
            background-color: rgba(0, 123, 255, 0.15) !important;
        }
    `;
    document.head.appendChild(style);
});
</script>

<script>
    
</script>
@endsection
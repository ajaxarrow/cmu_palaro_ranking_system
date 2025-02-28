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
                        <button class="chart-tab active me-2" data-target="bar-chart">Medal Distribution</button>
                        <button class="chart-tab me-2" data-target="donut-chart">Medal Breakdown</button>
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
        </div>
    </div>

    <!-- Detailed Medal Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detailed Medal Breakdown by Sport & Event</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="datatable_1">
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
        // Make sure ApexCharts is loaded
        if (typeof ApexCharts === 'undefined') {
            // Load ApexCharts dynamically if it's not available
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/apexcharts';
            script.onload = initializeCharts;
            document.head.appendChild(script);
        } else {
            initializeCharts();
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
@endsection
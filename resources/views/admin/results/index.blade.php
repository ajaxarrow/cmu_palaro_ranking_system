@extends('layouts.admin', ['title' => 'Results Dashboard'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])

@endsection

@section('content')

<!-- Start Content-->
<div class="container-fluid">

    <!-- Dashboard Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold mb-0">🏆 Medal Results Dashboard</h3>
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

    <!-- Chart Toggle Section -->

    <!-- Charts Row -->
    <div class="row">
        <div class="col-12">


                    <div class="chart-toggle d-flex justify-content-center">
                        <button class="chart-tab active me-2" data-target="chart-datatable">Distribution Table</button>
                        <button class="chart-tab me-2" data-target="bar-chart">Medal Standings</button>
                        <button class="chart-tab me-2" data-target="donut-chart">Medal Distribution</button>
                        <button class="chart-tab me-2" data-target="radar-chart">Team Balance</button>
                        <button class="chart-tab" data-target="heatmap-chart">Performance Heatmap</button>
                    </div>



            <div id="chart-datatable" class="chart-container active">
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detailed Results</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table
                        id="state-saving-datatable"
                        class="table activate-select dt-responsive nowrap w-100" >
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Team</th>
                                    <th class="text-center">Gold</th>
                                    <th class="text-center">Silver</th>
                                    <th class="text-center">Bronze</th>
                                    <th class="text-center">Total Medals</th>
                                    <th class="text-center">Total Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medalTally as $sport => $data)

                                    <tr>
                                        <td><strong>{{ $data['rank'] }}</strong></td>
                                        <td>
                                            <a   href="{{ route('teams.show', ['id' => $data['team_id']]) }}" title="View Team Details">
                                            <div class="team-badge">
                                                <img src="{{ $data['team_image'] ? asset('storage/'.$data['team_image'])  : Vite::asset('resources/images/bg-pattern.svg') }}" alt="{{ $data['team'] }}" class="team-img">
                                                <span>{{ $data['team'] }}</span>
                                            </div>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">{{ $data['total']['gold_tally'] }}</span>
                                            <small class="d-block text-muted mt-1">{{ number_format($data['total']['gold_weight'], 2) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $data['total']['silver_tally'] }}</span>
                                            <small class="d-block text-muted mt-1">{{ number_format($data['total']['silver_weight'], 2) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-dark">{{ $data['total']['bronze_tally'] }}</span>
                                            <small class="d-block text-muted mt-1">{{ number_format($data['total']['bronze_weight'], 2) }}</small>
                                        </td>
                                        <td class="text-center"><strong>{{ $data['total']['total_medals'] }}</strong></td>
                                        <td class="text-center"><strong>{{ number_format($data['total']['total_weight'], 2) }}</strong></td>
                                    </tr>
                                    </a>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
             </div>

            <!-- Bar Chart Container -->

            <div id="bar-chart" class="chart-container">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Medal Standings by Weight</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="sortByCount">
                            <label class="form-check-label" for="sortByCount">Sort by count</label>
                        </div>
                    </div>
                    <div class="card-body position-relative">
                        <div id="medal-standings-chart" style="height: 600px;"></div>
                    </div>
                </div>
            </div>

            <!-- Donut Chart Container -->
            <div id="donut-chart" class="chart-container">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Medal Type Distribution</h5>
                        <select id="team-selector" class="form-select" style="width: auto;">
                            <option value="all">All Teams</option>
                            @foreach ($medalTally as $data)
                                <option value="{{ $data['team'] }}">{{ $data['team'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="medal-count-chart" style="height: 400px;"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="medal-weight-chart" style="height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Radar Chart Container -->
            <div id="radar-chart" class="chart-container">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Team Performance Balance</h5>
                    </div>
                    <div class="card-body">
                        <div id="team-radar-chart" style="height: 500px;"></div>
                    </div>
                </div>
            </div>

            <!-- Heatmap Chart Container -->
            <div id="heatmap-chart" class="chart-container">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Medal Performance Heatmap</h5>
                    </div>
                    <div class="card-body">
                        <div id="performance-heatmap" style="height: 500px;"></div>
                    </div>
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
// Prepare data for charts
var seriesData = [];
var categoriesData = [];

@foreach ($medalTally as $data)
    categoriesData.push("{{ $data['team'] }}");

    // Create data for charts
    seriesData.push({
        teamImage : "{{ $data['team_image']}}",
        team: "{{ $data['team'] }}",
        gold: {{ $data['total']['gold_weight'] }},
        goldCount: {{ $data['total']['gold_tally'] }},
        silver: {{ $data['total']['silver_weight'] }},
        silverCount: {{ $data['total']['silver_tally'] }},
        bronze: {{ $data['total']['bronze_weight'] }},
        bronzeCount: {{ $data['total']['bronze_tally'] }},
        total: {{ $data['total']['total_weight'] }},
        totalMedals: {{ $data['total']['total_medals'] }}
    });
@endforeach

// Sort data by total weight for better visualization
seriesData.sort((a, b) => b.total - a.total);
categoriesData = seriesData.map(item => item.team);

// Function to get team flag/logo URL
function getTeamImageUrl(teamImage) {
    return "{{ asset('storage/') }}" + "/" + teamImage;
}

// Chart toggle functionality
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

        // Trigger window resize to fix any chart sizing issues
        window.dispatchEvent(new Event('resize'));
    });
});

// 1. BAR CHART - Medal Standings
function renderBarChart(sortByCount = false) {
    // Sort data based on selected option
    let sortedData = [...seriesData];
    if (sortByCount) {
        sortedData.sort((a, b) => b.totalMedals - a.totalMedals);
    } else {
        sortedData.sort((a, b) => b.total - a.total);
    }

    const sortedCategories = sortedData.map(item => item.team);

    // Create series for stacked bars
    const goldSeries = sortedData.map(item => sortByCount ? item.goldCount : item.gold);
    const silverSeries = sortedData.map(item => sortByCount ? item.silverCount : item.silver);
    const bronzeSeries = sortedData.map(item => sortByCount ? item.bronzeCount : item.bronze);

    // Get max value for better visualization scaling
    const maxTotal = Math.max(...sortedData.map(item => sortByCount ? item.totalMedals : item.total));

    const options = {
        series: [
            {
                name: 'Gold',
                data: goldSeries,
                color: '#FFB800'
            },
            {
                name: 'Silver',
                data: silverSeries,
                color: '#B7B7B7'
            },
            {
                name: 'Bronze',
                data: bronzeSeries,
                color: '#CD7F32'
            }
        ],
        chart: {
            type: 'bar',
            height: 600,
            fontFamily: "'Poppins', sans-serif",
            background: '#ffffff',
            foreColor: '#333333',
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
                horizontal: true,
                borderRadius: 6,
                columnWidth: '80%',
                barHeight: '70%',
                distributed: false,
                dataLabels: {
                    position: 'middle'
                }
            }
        },
        stroke: {
            width: 1,
            colors: ['#ffffff']
        },
        dataLabels: {
            enabled: true,
            formatter: function(val, opt) {
                const index = opt.dataPointIndex;
                const seriesIndex = opt.seriesIndex;

                if (val > 0) {
                    if (seriesIndex === 0) return sortByCount ? `${sortedData[index].goldCount} 🥇` : `${val.toFixed(1)}`;
                    if (seriesIndex === 1) return sortByCount ? `${sortedData[index].silverCount} 🥈` : `${val.toFixed(1)}`;
                    if (seriesIndex === 2) return sortByCount ? `${sortedData[index].bronzeCount} 🥉` : `${val.toFixed(1)}`;
                }
                return '';
            },
            style: {
                fontSize: '13px',
                fontWeight: 'bold',
                colors: ['#000'],
                textShadow: '0px 0px 2px rgba(255,255,255,0.7)'
            }
        },
        tooltip: {
            theme: 'light',
            shared: true,
            intersect: false,
            style: {
                fontSize: '14px'
            },
            y: {
                formatter: function(val, { seriesIndex, dataPointIndex, w }) {
                    const teamData = sortedData[dataPointIndex];

                    if (sortByCount) {
                        if (seriesIndex === 0) {
                            return `${teamData.goldCount} Gold Medals`;
                        } else if (seriesIndex === 1) {
                            return `${teamData.silverCount} Silver Medals`;
                        } else {
                            return `${teamData.bronzeCount} Bronze Medals`;
                        }
                    } else {
                        if (seriesIndex === 0) {
                            return `${teamData.goldCount} Gold Medals (${teamData.gold.toFixed(2)} weight)`;
                        } else if (seriesIndex === 1) {
                            return `${teamData.silverCount} Silver Medals (${teamData.silver.toFixed(2)} weight)`;
                        } else {
                            return `${teamData.bronzeCount} Bronze Medals (${teamData.bronze.toFixed(2)} weight)`;
                        }
                    }
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            fontSize: '14px',
            markers: {
                radius: 50,
                width: 16,
                height: 16
            },
            itemMargin: {
                horizontal: 15
            }
        },
        xaxis: {
            categories: sortedCategories,
            labels: {
                style: {
                    fontSize: '13px',
                    fontWeight: 500,
                    colors: '#333'
                }
            },
            title: {
                text: sortByCount ? 'Medal Count' : 'Medal Weight',
                style: {
                    fontSize: '14px',
                    fontWeight: 600,
                    color: '#333'
                }
            },
            axisBorder: {
                show: true,
                color: '#e0e0e0'
            },
            axisTicks: {
                show: true,
                color: '#e0e0e0'
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: '14px',
                    fontWeight: 600,
                    colors: '#333'
                }
            }
        },
        grid: {
            borderColor: '#e0e0e0',
            strokeDashArray: 3,
            position: 'back'
        },
        // Custom markers for team rank
        annotations: {
            points: sortedData.map((item, index) => ({
                x: (sortByCount ? item.totalMedals : item.total) + (maxTotal * 0.05),
                y: sortedCategories[index],
                marker: {
                    size: 0
                },
                label: {
                    borderColor: 'transparent',
                    style: {
                        color: '#ffffff',
                        background: '#4361ee',
                        fontSize: '14px',
                        fontWeight: 'bold',
                        padding: {
                            left: 12,
                            right: 12,
                            top: 6,
                            bottom: 6
                        },
                        borderRadius: 20
                    },
                    text: sortByCount ? `Total: ${item.totalMedals}` : `Total: ${item.total.toFixed(1)}`
                }
            })),
            position: 'front'
        },
        states: {
            hover: {
                filter: {
                    type: 'darken',
                    value: 0.08
                }
            }
        },
        title: {
            text: '🏆 MEDAL STANDINGS 🏆',
            align: 'center',
            style: {
                fontSize: '20px',
                fontWeight: 700,
                color: '#333'
            },
            offsetY: 15
        },
        responsive: [
            {
                breakpoint: 768,
                options: {
                    chart: {
                        height: 500
                    },
                    dataLabels: {
                        style: {
                            fontSize: '11px'
                        }
                    }
                }
            }
        ]
    };

    // Clear previous chart
    document.querySelector("#medal-standings-chart").innerHTML = '';

    // Render the chart
    const chart = new ApexCharts(document.querySelector("#medal-standings-chart"), options);
    chart.render().then(function() {
        // Add team images to labels
        setTimeout(function() {
            const yAxisLabels = document.querySelectorAll('#medal-standings-chart .apexcharts-yaxis-label');

            yAxisLabels.forEach((label, index) => {
                if (index < sortedCategories.length) {
                    const teamData = sortedData.find(data => data.team === sortedCategories[index]);
                    if (teamData) {
                        const img = document.createElement('img');
                        img.src = getTeamImageUrl(teamData.teamImage);
                        img.alt = "";
                        img.style.width = '24px';
                        img.style.height = '24px';
                        img.style.borderRadius = '50%';
                        img.style.marginRight = '8px';
                        img.style.verticalAlign = 'middle';
                        img.style.border = '1px solid #ddd';

                        label.parentNode.insertBefore(img, label);

                        label.style.display = 'flex';
                        label.style.alignItems = 'center';
                        label.style.fontWeight = 'bold';
                    }
                }
            });
        }, 1000);
    });
}

// 2. DONUT CHARTS - Medal Distribution
function renderDonutCharts(team = 'all') {
    // Prepare data for selected team or all teams
    let countData, weightData;

    if (team === 'all') {
        // Aggregate all medals by type
        const totalGoldCount = seriesData.reduce((sum, item) => sum + item.goldCount, 0);
        const totalSilverCount = seriesData.reduce((sum, item) => sum + item.silverCount, 0);
        const totalBronzeCount = seriesData.reduce((sum, item) => sum + item.bronzeCount, 0);

        const totalGoldWeight = seriesData.reduce((sum, item) => sum + item.gold, 0);
        const totalSilverWeight = seriesData.reduce((sum, item) => sum + item.silver, 0);
        const totalBronzeWeight = seriesData.reduce((sum, item) => sum + item.bronze, 0);

        countData = [totalGoldCount, totalSilverCount, totalBronzeCount];
        weightData = [totalGoldWeight, totalSilverWeight, totalBronzeWeight];
    } else {
        // Get data for specific team
        const teamData = seriesData.find(item => item.team === team);
        if (teamData) {
            countData = [teamData.goldCount, teamData.silverCount, teamData.bronzeCount];
            weightData = [teamData.gold, teamData.silver, teamData.bronze];
        } else {
            countData = [0, 0, 0];
            weightData = [0, 0, 0];
        }
    }

    // Medal count donut chart
    const countOptions = {
        series: countData,
        chart: {
            type: 'donut',
            height: 400,
            fontFamily: "'Poppins', sans-serif",
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        colors: ['#FFB800', '#B7B7B7', '#CD7F32'],
        labels: ['Gold', 'Silver', 'Bronze'],
        plotOptions: {
            pie: {
                donut: {
                    size: '50%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Medals',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '14px',
            markers: {
                width: 12,
                height: 12,
                radius: 12
            },
            itemMargin: {
                horizontal: 15,
                vertical: 5
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        title: {
            text: 'Medal Count Distribution',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 600
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + " medals";
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.globals.series[opts.seriesIndex] + ' (' + Math.round(val) + '%)';
            },
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                colors: ["#fff"],
                textShadow: '0px 0px 2px rgba(0,0,0,0.5)'
            },
            dropShadow: {
                enabled: true,
                blur: 3,
                opacity: 0.4
            }
        },
        stroke: {
            width: 2,
            colors: ['#fff']
        }
    };

    // Medal weight donut chart
    const weightOptions = {
        series: weightData.map(val => parseFloat(val.toFixed(2))),
        chart: {
            type: 'donut',
            height: 400,
            fontFamily: "'Poppins', sans-serif",
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#FFB800', '#B7B7B7', '#CD7F32'],
        labels: ['Gold', 'Silver', 'Bronze'],
        plotOptions: {
            pie: {
                donut: {
                    size: '50%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Weight',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(2);
                            }
                        }
                    }
                }
            }
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '14px',
            markers: {
                width: 12,
                height: 12,
                radius: 12
            },
            itemMargin: {
                horizontal: 15,
                vertical: 5
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    height: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        title: {
            text: 'Medal Weight Distribution',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 600
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val.toFixed(2) + " weight";
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.globals.series[opts.seriesIndex].toFixed(1) + ' (' + Math.round(val) + '%)';
            },
            style: {
                fontSize: '14px',
                fontWeight: 'bold',
                colors: ["#fff"],
                textShadow: '0px 0px 2px rgba(0,0,0,0.5)'
            },
            dropShadow: {
                enabled: true,
                blur: 3,
                opacity: 0.4
            }
        },
        stroke: {
            width: 2,
            colors: ['#fff']
        }
    };

    // Clear previous charts
    document.querySelector("#medal-count-chart").innerHTML = '';
    document.querySelector("#medal-weight-chart").innerHTML = '';

    // Render charts
    const countChart = new ApexCharts(document.querySelector("#medal-count-chart"), countOptions);
    const weightChart = new ApexCharts(document.querySelector("#medal-weight-chart"), weightOptions);

    countChart.render();
    weightChart.render();
}

// 3. RADAR CHART - Team Performance Balance
function renderRadarChart() {
    // Prepare data for radar chart - normalize values
    const radarData = seriesData.map(team => {
        // Calculate normalized scores (0-100) for each medal type
        const maxGold = Math.max(...seriesData.map(t => t.goldCount));
        const maxSilver = Math.max(...seriesData.map(t => t.silverCount));
        const maxBronze = Math.max(...seriesData.map(t => t.bronzeCount));
        const maxTotal = Math.max(...seriesData.map(t => t.totalMedals));

        const goldScore = Math.round((team.goldCount / maxGold) * 100);
        const silverScore = Math.round((team.silverCount / maxSilver) * 100);
        const bronzeScore = Math.round((team.bronzeCount / maxBronze) * 100);
        const totalScore = Math.round((team.totalMedals / maxTotal) * 100);

        // Calculate a consistency score based on standard deviation (lower std dev = higher consistency)
        const medalCounts = [team.goldCount, team.silverCount, team.bronzeCount];
        const mean = medalCounts.reduce((sum, val) => sum + val, 0) / medalCounts.length;
        const stdDev = Math.sqrt(medalCounts.reduce((sum, val) => sum + Math.pow(val - mean, 2), 0) / medalCounts.length);
        const maxStdDev = Math.max(...seriesData.map(t => {
            const counts = [t.goldCount, t.silverCount, t.bronzeCount];
            const avg = counts.reduce((sum, val) => sum + val, 0) / counts.length;
            return Math.sqrt(counts.reduce((sum, val) => sum + Math.pow(val - avg, 2), 0) / counts.length);
        }));

        // Lower standard deviation means higher consistency
        const consistencyScore = Math.round(((maxStdDev - stdDev) / maxStdDev) * 100);

        return {
            name: team.team,
            data: [goldScore, silverScore, bronzeScore, totalScore, consistencyScore],
            color: getTeamColor(team.team)
        };
    });

    const options = {
        series: radarData,
        chart: {
            height: 500,
            type: 'radar',
            dropShadow: {
                enabled: true,
                blur: 1,
                left: 1,
                top: 1
            },
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        stroke: {
            width: 2
        },
        fill: {
            opacity: 0.2
        },
        markers: {
            size: 4,
            hover: {
                size: 6
            }
        },
        xaxis: {
            categories: ['Gold Medal Performance', 'Silver Medal Performance', 'Bronze Medal Performance', 'Total Medal Count', 'Medal Consistency']
        },
        yaxis: {
            show: false,
            min: 0,
            max: 100
        },
        legend: {
            position: 'bottom',
            fontSize: '14px'
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + "/100";
                }
            }
        }
    };

    // Clear previous chart
    document.querySelector("#team-radar-chart").innerHTML = '';

    // Render chart
    const chart = new ApexCharts(document.querySelector("#team-radar-chart"), options);
    chart.render();
}

// 4. HEATMAP CHART - Performance Heatmap
function renderHeatmapChart() {
    // Prepare data for heatmap
    const categories = ['Gold', 'Silver', 'Bronze', 'Total'];

    // Generate heatmap data
    const sortedTeams = [...seriesData].sort((a, b) => b.total - a.total);

    const heatmapData = categories.map((cat, i) => {
        return {
            name: cat,
            data: sortedTeams.map(team => {
                let value = 0;

                if (cat === 'Gold') value = team.goldCount;
                else if (cat === 'Silver') value = team.silverCount;
                else if (cat === 'Bronze') value = team.bronzeCount;
                else if (cat === 'Total') value = team.totalMedals;

                return {
                    x: team.team,
                    y: value
                }
            })
        }
    });

    const options = {
        series: heatmapData,
        chart: {
            height: 500,
            type: 'heatmap',
            fontFamily: "'Poppins', sans-serif",
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#000']
            }
        },
        colors: ["#4361ee"],
        title: {
            text: 'Medal Performance Comparison',
            align: 'center',
            style: {
                fontSize: '16px',
                fontWeight: 600
            }
        },
        plotOptions: {
            heatmap: {
                shadeIntensity: 0.5,
                radius: 0,
                useFillColorAsStroke: false,
                colorScale: {
                    ranges: [
                        {
                            from: 0,
                            to: 5,
                            name: 'Low',
                            color: '#d1e7ff'
                        },
                        {
                            from: 6,
                            to: 15,
                            name: 'Medium',
                            color: '#7bb3ff'
                        },
                        {
                            from: 16,
                            to: 30,
                            name: 'High',
                            color: '#4361ee'
                        },
                        {
                            from: 31,
                            to: 1000,
                            name: 'Very High',
                            color: '#002c94'
                        }
                    ]
                }
            }
        },
        xaxis: {
            type: 'category'
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + " medals";
                }
            }
        }
    };

    // Clear previous chart
    document.querySelector("#performance-heatmap").innerHTML = '';

    // Render chart
    const chart = new ApexCharts(document.querySelector("#performance-heatmap"), options);
    chart.render();
}

// Function to generate consistent colors for teams
function getTeamColor(teamName) {
    // Simple hash function to create consistent colors for the same team name
    let hash = 0;
    for (let i = 0; i < teamName.length; i++) {
        hash = teamName.charCodeAt(i) + ((hash << 5) - hash);
    }

    // Generate a hue between 0 and 360
    const hue = hash % 360;

    return `hsl(${hue}, 70%, 50%)`;
}

// Initialize all charts
document.addEventListener('DOMContentLoaded', function() {
    // Initial render for all charts
    renderBarChart(false);
    renderDonutCharts('all');
    renderRadarChart();
    renderHeatmapChart();

    // Toggle between weight and count for bar chart
    document.getElementById('sortByCount').addEventListener('change', function() {
        renderBarChart(this.checked);
    });

    // Team selector for donut charts
    document.getElementById('team-selector').addEventListener('change', function() {
        renderDonutCharts(this.value);
    });
});
</script>

@endsection

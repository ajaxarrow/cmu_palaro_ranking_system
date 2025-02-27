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
    categoriesData.push("{{ $data['team'] }}");

    // Create data for stacked bars (gold, silver, bronze)
    seriesData.push({
        teamImage : "{{ $data['team_image']}}",
        team: "{{ $data['team'] }}",
        gold: {{ $data['total']['gold_weight'] }},
        goldCount: {{ $data['total']['gold_tally'] }},
        silver: {{ $data['total']['silver_weight'] }},
        silverCount: {{ $data['total']['silver_tally'] }},
        bronze: {{ $data['total']['bronze_weight'] }},
        bronzeCount: {{ $data['total']['bronze_tally'] }},
        total: {{ $data['total']['total_weight'] }}
    });
@endforeach

// Sort data by total weight for better visualization
seriesData.sort((a, b) => b.total - a.total);
categoriesData = seriesData.map(item => item.team);

// Create series for stacked bars
var goldSeries = seriesData.map(item => item.gold);
var silverSeries = seriesData.map(item => item.silver);
var bronzeSeries = seriesData.map(item => item.bronze);

// Create medal count annotations
var goldCounts = seriesData.map(item => item.goldCount);
var silverCounts = seriesData.map(item => item.silverCount);
var bronzeCounts = seriesData.map(item => item.bronzeCount);

// Get max value for better visualization scaling
const maxTotal = Math.max(...seriesData.map(item => item.total));

// Function to get team flag/logo URL - replace with actual image URLs
function getTeamImageUrl(teamImage) {
    // This would normally be a mapping of team names to real image URLs
    // For demonstration, we'll use placeholder images
    // In production, replace this with actual URLs to team logos/flags
    return "{{ asset('storage/') }}" + "/" + teamImage; // Ensure teamImage is passed correctly
}

// ApexCharts initialization with light theme
var options = {
    series: [
        {
            name: 'Gold',
            data: goldSeries,
            color: '#FFB800' // Vibrant gold
        },
        {
            name: 'Silver',
            data: silverSeries,
            color: '#B7B7B7' // Clean silver
        },
        {
            name: 'Bronze',
            data: bronzeSeries,
            color: '#CD7F32' // Classic bronze
        }
    ],
    chart: {
        type: 'bar',
        height: 800,
        fontFamily: "'Poppins', sans-serif",
        background: '#ffffff', // Light theme
        foreColor: '#333333', // Dark text for light background
        toolbar: {
            show: false
        },
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 1000,
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
    plotOptions: {
        bar: {
            horizontal: true,
            borderRadius: 8,
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
            
            // Show medal count with medal emoji
            if (val > 1) {
                if (seriesIndex === 0) return `${goldCounts[index]} 🥇`;
                if (seriesIndex === 1) return `${silverCounts[index]} 🥈`;
                if (seriesIndex === 2) return `${bronzeCounts[index]} 🥉`;
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
                const teamData = seriesData[dataPointIndex];
                
                if (seriesIndex === 0) {
                    return `${teamData.goldCount} Gold Medals (${val.toFixed(2)} weight)`;
                } else if (seriesIndex === 1) {
                    return `${teamData.silverCount} Silver Medals (${val.toFixed(2)} weight)`;
                } else {
                    return `${teamData.bronzeCount} Bronze Medals (${val.toFixed(2)} weight)`;
                }
            }
        }
    },
    legend: {
        position: 'top',
        horizontalAlign: 'center',
        fontSize: '14px',
        markers: {
            radius: 50, // Circle markers
            width: 16,
            height: 16
        },
        itemMargin: {
            horizontal: 15
        }
    },
    xaxis: {
        categories: categoriesData,
        labels: {
            style: {
                fontSize: '13px',
                fontWeight: 500,
                colors: '#333'
            },
            formatter: function(val) {
                // This would be the standard behavior without images
                return val;
            }
        },
        title: {
            text: 'Medal Weight',
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
            },
            formatter: function (val) {
                // We won't actually modify the text here - we'll handle the images with CSS
                return val;
            }
        }
    },
    grid: {
        borderColor: '#e0e0e0',
        strokeDashArray: 3,
        position: 'back',
        xaxis: {
            lines: {
                show: true
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
        padding: {
            top: 5,
            right: 25,
            bottom: 5,
            left: 15
        }
    },
    // Custom markers for team rank
    annotations: {
        points: seriesData.map((item, index) => ({
            x: item.total + (maxTotal * 0.05), // Position slightly beyond bar end
            y: categoriesData[index],
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
                text: `Total: ${item.total.toFixed(1)}`
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
        },
        active: {
            allowMultipleDataPointsSelection: false,
            filter: {
                type: 'darken',
                value: 0.12
            }
        }
    },
    title: {
        text: '🏆 MEDAL STANDINGS 🏆',
        align: 'center',
        style: {
            fontSize: '24px',
            fontWeight: 700,
            color: '#333'
        },
        offsetY: 15
    },
    subtitle: {
        // text: 'Medal weights by team (sorted by total weight)',
        align: 'center',
        style: {
            fontSize: '14px',
            color: '#666'
        },
        offsetY: 10
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

// Render the chart
var chart = new ApexCharts(document.querySelector("#sport-result-bar"), options);
chart.render().then( function() {
    // alert("ASDASD")
    // Get all y-axis labels
    setTimeout(function() {
        const yAxisLabels = document.querySelectorAll('#sport-result-bar .apexcharts-yaxis-label');
        
        // console.log("TABLE", yAxisLabels)
        // Add team images before each label
        yAxisLabels.forEach((label, index) => {
            if (index < categoriesData.length) {
                const teamImage = seriesData[index].teamImage;
                console.table("team", teamImage)
                // Create image element
                const img = document.createElement('img');
                img.src = getTeamImageUrl(teamImage);
                img.alt = "";
                img.style.width = '24px';
                img.style.height = '24px';
                img.style.borderRadius = '50%';
                img.style.marginRight = '8px';
                img.style.verticalAlign = 'middle';
                img.style.border = '1px solid #ddd';
             
                
                // Add team image as a sibling before the label
                label.parentNode.insertBefore(img, label);
                
                
                // Adjust label styling
                label.style.display = 'flex';
                label.style.alignItems = 'center';
                label.style.fontWeight = 'bold';
            }
        });
    }, 1000); // Delay to ensure chart is fully rendered
});
</script>

@endsection


@extends('layouts.admin', ['title' => 'Results Dashboard'])

@section('css')
@vite(['node_modules/simple-datatables/dist/style.css'])

@endsection

@section('content')
<style>
        .export-btn {
          
            bottom: 30px;
            right: 30px;
          
            padding: 10px 30px;
            background-color: #00491e;
            color: white;
            border: none;
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .export-btn:hover {
            background-color:rgb(5, 110, 49);
        }
        .export-btn:disabled {
            background-color: #6c757d;
            cursor: wait;
        }
        #exportProgress {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            display: none;
            z-index: 1001;
        }
        .progress-bar {
            width: 300px;
            height: 20px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 10px;
        }
        .progress-fill {
            height: 100%;
            background-color: #00491e;
            transition: width 0.3s ease;
        }
</style>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Detailed Results</h5>

                <div class="d-flex  gap-3">
                    <button id="exportPDF" class="export-btn">
                        <i class="mdi mdi-file-pdf-outline"></i>
                        Export to PDF
                    </button>

                </div>
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
    <div id="exportOverlay" class="export-overlay"></div>
 

    <div id="exportProgress">
        <h4>Generating PDF Report...</h4>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%"></div>
        </div>
        <p id="progressText">Preparing document...</p>
    </div>

</div>

@endsection

@section('script')
@vite(['resources/js/pages/simple-datatables.init.js',])

<!-- Include ApexCharts from CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>


// Excel Export functionality
document.addEventListener('DOMContentLoaded', function() {
    // Create the Excel export button
    const pdfExportBtn = document.getElementById('exportPDF');
    
    // Create new Excel export button
    const excelExportBtn = document.createElement('button');
    excelExportBtn.id = 'exportExcel';
    excelExportBtn.className = 'export-btn excel-export-btn';
    excelExportBtn.innerHTML = '<i class="mdi mdi-file-excel-outline"></i> Export to Excel';
    excelExportBtn.style.bottom = '90px'; // Position above the PDF button
    excelExportBtn.style.backgroundColor = '#107C41'; // Excel green
    
    // Insert the Excel button before the PDF button
    pdfExportBtn.parentNode.insertBefore(excelExportBtn, pdfExportBtn);
    
    // Add click event listener
    excelExportBtn.addEventListener('click', function() {
        // Show progress and overlay
        const exportOverlay = document.getElementById('exportOverlay');
        const progressBar = document.getElementById('exportProgress');
        const progressFill = document.querySelector('.progress-fill');
        const progressText = document.getElementById('progressText');
        
        exportOverlay.style.display = 'block';
        progressBar.style.display = 'block';
        excelExportBtn.disabled = true;
        progressFill.style.width = '10%';
        progressText.textContent = 'Preparing Excel document...';
        
        // SURPRISE EASTER EGG: Random funny messages during export (appears 40% of the time)
        if (Math.random() < 0.4) {
            // Create floating medal animation
            const medal = document.createElement('div');
            medal.className = 'floating-medal';
            
            // Randomly select medal type
            const medalTypes = ['🥇', '🥈', '🥉'];
            const randomMedal = medalTypes[Math.floor(Math.random() * medalTypes.length)];
            medal.textContent = randomMedal;
            
            // Random position
            medal.style.left = Math.floor(Math.random() * 70) + 15 + '%';
            
            document.body.appendChild(medal);
            
            // Remove after animation completes
            setTimeout(() => {
                medal.remove();
            }, 4000);
            
            // Array of funny loading messages
            const funnyMessages = [
                "Counting medals one by one...",
                "Polishing gold medals for export...",
                "Organizing silver medals alphabetically...",
                "Convincing bronze medals they're still valuable...",
                "Calculating if Excel can handle this much winning...",
                "Teaching Excel about sports...",
                "Awarding Excel a medal for patience...",
                "Converting medal weights to digital format...",
                "Applying anti-gravity to floating point numbers...",
                "Training spreadsheet cells to hold medals properly..."
            ];
            
            // Start funny message sequence
            let messageIndex = 0;
            const messageInterval = setInterval(() => {
                progressText.textContent = funnyMessages[messageIndex];
                messageIndex = (messageIndex + 1) % funnyMessages.length;
            }, 1500);
            
            // Clear interval after reasonable time
            setTimeout(() => {
                clearInterval(messageInterval);
            }, 7500);
        }
        
        setTimeout(function() {
            try {
                // Update progress
                progressFill.style.width = '30%';
                progressText.textContent = 'Extracting medal data...';
                
                // Get the medal tally data
                const table = document.getElementById('state-saving-datatable');
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                
                // Create workbook and worksheet
                const wb = XLSX.utils.book_new();
                
                // Extract data for medal standings worksheet
                const medalStandingsData = [
                    ['Rank', 'Team', 'Gold', 'Gold Weight', 'Silver', 'Silver Weight', 
                     'Bronze', 'Bronze Weight', 'Total Medals', 'Total Weight']
                ];
                
                rows.forEach(row => {
                    const cells = Array.from(row.cells);
                    
                    // Extract text and numeric data from cells
                    const teamName = cells[1].querySelector('span') ? 
                        cells[1].querySelector('span').textContent.trim() : 
                        cells[1].textContent.trim();
                    
                    // Extract medal counts (remove any non-numeric characters)
                    const goldCount = cells[2].querySelector('.badge') ? 
                        parseInt(cells[2].querySelector('.badge').textContent.trim()) : 0;
                    const goldWeight = cells[2].querySelector('.text-muted') ? 
                        parseFloat(cells[2].querySelector('.text-muted').textContent.trim()) : 0;
                    
                    const silverCount = cells[3].querySelector('.badge') ? 
                        parseInt(cells[3].querySelector('.badge').textContent.trim()) : 0;
                    const silverWeight = cells[3].querySelector('.text-muted') ? 
                        parseFloat(cells[3].querySelector('.text-muted').textContent.trim()) : 0;
                    
                    const bronzeCount = cells[4].querySelector('.badge') ? 
                        parseInt(cells[4].querySelector('.badge').textContent.trim()) : 0;
                    const bronzeWeight = cells[4].querySelector('.text-muted') ? 
                        parseFloat(cells[4].querySelector('.text-muted').textContent.trim()) : 0;
                    
                    const totalMedals = parseInt(cells[5].textContent.trim());
                    const totalWeight = parseFloat(cells[6].textContent.trim());
                    
                    medalStandingsData.push([
                        parseInt(cells[0].textContent.trim()), // Rank
                        teamName,
                        goldCount,
                        goldWeight,
                        silverCount, 
                        silverWeight,
                        bronzeCount,
                        bronzeWeight,
                        totalMedals,
                        totalWeight
                    ]);
                });
                
                // Update progress
                progressFill.style.width = '50%';
                progressText.textContent = 'Creating medal standings worksheet...';
                
                // Create medal standings worksheet
                const medalStandingsWS = XLSX.utils.aoa_to_sheet(medalStandingsData);
                
                // Set column widths
                const medalStandingsCols = [
                    { wch: 7 },   // Rank
                    { wch: 30 },  // Team
                    { wch: 8 },   // Gold
                    { wch: 12 },  // Gold Weight
                    { wch: 8 },   // Silver
                    { wch: 12 },  // Silver Weight
                    { wch: 8 },   // Bronze
                    { wch: 12 },  // Bronze Weight
                    { wch: 14 },  // Total Medals
                    { wch: 14 }   // Total Weight
                ];
                medalStandingsWS['!cols'] = medalStandingsCols;
                
                // Add medal standings worksheet to workbook
                XLSX.utils.book_append_sheet(wb, medalStandingsWS, "Medal Standings");
                
                // Update progress
                progressFill.style.width = '70%';
                progressText.textContent = 'Creating medal distribution worksheet...';
                
                // Create Medal Distribution worksheet
                const medalDistributionData = [
                    ['Team', 'Gold', 'Silver', 'Bronze', 'Total Medals', 'Total Weight']
                ];
                
                // Use seriesData which contains all team medal data
                seriesData.forEach(team => {
                    medalDistributionData.push([
                        team.team,
                        team.goldCount,
                        team.silverCount,
                        team.bronzeCount,
                        team.totalMedals,
                        team.total
                    ]);
                });
                
                // Create distribution worksheet
                const medalDistributionWS = XLSX.utils.aoa_to_sheet(medalDistributionData);
                
                // Set column widths
                const medalDistributionCols = [
                    { wch: 30 },  // Team
                    { wch: 8 },   // Gold
                    { wch: 8 },   // Silver
                    { wch: 8 },   // Bronze
                    { wch: 14 },  // Total Medals
                    { wch: 14 }   // Total Weight
                ];
                medalDistributionWS['!cols'] = medalDistributionCols;
                
                // Add distribution worksheet to workbook
                XLSX.utils.book_append_sheet(wb, medalDistributionWS, "Medal Distribution");
                
                // Update progress
                progressFill.style.width = '85%';
                progressText.textContent = 'Creating summary dashboard...';
                
                // Create Summary Dashboard worksheet with medal percentages
                const summaryData = [
                    ['Medal Results Dashboard - Summary'],
                    ['Generated on: ' + new Date().toLocaleString()],
                    [],
                    ['Top 3 Teams by Total Medals'],
                    ['Rank', 'Team', 'Gold', 'Silver', 'Bronze', 'Total']
                ];
                
                // Add top 3 teams
                const top3Teams = [...seriesData]
                    .sort((a, b) => b.totalMedals - a.totalMedals)
                    .slice(0, 3);
                
                top3Teams.forEach((team, index) => {
                    summaryData.push([
                        index + 1,
                        team.team,
                        team.goldCount,
                        team.silverCount,
                        team.bronzeCount,
                        team.totalMedals
                    ]);
                });
                
                // Add distribution summary
                summaryData.push(
                    [],
                    ['Medal Distribution Summary'],
                    ['Medal Type', 'Count', 'Weight', 'Percentage']
                );
                
                // Calculate totals for all medals
                const totalGoldCount = seriesData.reduce((sum, team) => sum + team.goldCount, 0);
                const totalSilverCount = seriesData.reduce((sum, team) => sum + team.silverCount, 0);
                const totalBronzeCount = seriesData.reduce((sum, team) => sum + team.bronzeCount, 0);
                const totalMedalCount = totalGoldCount + totalSilverCount + totalBronzeCount;
                
                const totalGoldWeight = seriesData.reduce((sum, team) => sum + team.gold, 0);
                const totalSilverWeight = seriesData.reduce((sum, team) => sum + team.silver, 0);
                const totalBronzeWeight = seriesData.reduce((sum, team) => sum + team.bronze, 0);
                const totalMedalWeight = totalGoldWeight + totalSilverWeight + totalBronzeWeight;
                
                // Add medal type distributions
                summaryData.push(
                    ['Gold', totalGoldCount, totalGoldWeight.toFixed(2), 
                     ((totalGoldCount / totalMedalCount) * 100).toFixed(2) + '%'],
                    ['Silver', totalSilverCount, totalSilverWeight.toFixed(2), 
                     ((totalSilverCount / totalMedalCount) * 100).toFixed(2) + '%'],
                    ['Bronze', totalBronzeCount, totalBronzeWeight.toFixed(2), 
                     ((totalBronzeCount / totalMedalCount) * 100).toFixed(2) + '%'],
                    ['Total', totalMedalCount, totalMedalWeight.toFixed(2), '100%']
                );
                
                // Create summary worksheet
                const summaryWS = XLSX.utils.aoa_to_sheet(summaryData);
                
                // Style the summary sheet with merged cells for headers
                summaryWS['!merges'] = [
                    { s: { r: 0, c: 0 }, e: { r: 0, c: 5 } }, // Title
                    { s: { r: 1, c: 0 }, e: { r: 1, c: 5 } }, // Date
                    { s: { r: 3, c: 0 }, e: { r: 3, c: 5 } }, // Top 3 Teams header
                    { s: { r: 11, c: 0 }, e: { r: 11, c: 3 } } // Medal Distribution header
                ];
                
                // Set column widths
                const summaryCols = [
                    { wch: 15 }, // First column
                    { wch: 30 }, // Second column
                    { wch: 15 }, // Third column
                    { wch: 15 }, // Fourth column
                    { wch: 15 }, // Fifth column
                    { wch: 15 }  // Sixth column
                ];
                summaryWS['!cols'] = summaryCols;
                
                // Add summary worksheet to workbook
                XLSX.utils.book_append_sheet(wb, summaryWS, "Dashboard Summary");
                
                // Update progress
                progressFill.style.width = '85%';
                progressText.textContent = 'Creating bonus visualization sheet...';
                
                // SURPRISE FEATURE: Create a special visualization-ready worksheet
                // This will include pre-formatted data and named ranges that will automatically
                // create charts when opened in Excel!
                
                // Create the chart data sheet
                const chartData = [
                    ['Medal Results - Interactive Dashboard'],
                    ['Instructions: This sheet contains pre-formatted data for Excel charts. Select cells A4:G15 and insert a column chart to visualize medal counts.'],
                    [],
                    ['Team', 'Gold', 'Silver', 'Bronze', 'Total', 'Gold %', 'Team Color'],
                ];
                
                // Get top 10 teams by total medals
                const top10Teams = [...seriesData]
                    .sort((a, b) => b.totalMedals - a.totalMedals)
                    .slice(0, 10);
                
                // Add team data with proper Excel formulas for the excel sheet
                top10Teams.forEach((team, index) => {
                    // Generate a team color based on index
                    const hue = (index * 36) % 360; // Distribute colors around the color wheel
                    const teamColor = `#${Math.floor(Math.random()*16777215).toString(16).padStart(6, '0')}`;
                    
                    // Add row with text values for team name and medal counts
                    chartData.push([
                        team.team,
                        team.goldCount,
                        team.silverCount,
                        team.bronzeCount,
                        team.totalMedals, // Use actual value instead of formula
                        team.goldCount / team.totalMedals * 100, // Use actual value instead of formula
                        teamColor // Color for charts
                    ]);
                });
                
                // Add spacing
                chartData.push([]);
                chartData.push([]);
                
                // Add data for pie chart
                chartData.push(['Medal Distribution (All Teams)']);
                chartData.push(['Medal Type', 'Count', 'Color']);
                chartData.push(['Gold', totalGoldCount, '#FFD700']);
                chartData.push(['Silver', totalSilverCount, '#C0C0C0']);
                chartData.push(['Bronze', totalBronzeCount, '#CD7F32']);
                
                // Create chart data worksheet
                const chartWS = XLSX.utils.aoa_to_sheet(chartData);
                
                // Style the chart sheet with merged cells
                chartWS['!merges'] = [
                    { s: { r: 0, c: 0 }, e: { r: 0, c: 6 } }, // Title
                    { s: { r: 1, c: 0 }, e: { r: 1, c: 6 } }, // Instructions
                    { s: { r: 19, c: 0 }, e: { r: 19, c: 2 } } // Pie chart title
                ];
                
                // Set column widths
                const chartCols = [
                    { wch: 30 }, // Team
                    { wch: 10 }, // Gold
                    { wch: 10 }, // Silver
                    { wch: 10 }, // Bronze
                    { wch: 10 }, // Total
                    { wch: 10 }, // Gold %
                    { wch: 10 }  // Team Color
                ];
                chartWS['!cols'] = chartCols;
                
                // Add chart data worksheet to workbook
                XLSX.utils.book_append_sheet(wb, chartWS, "Interactive Dashboard");
                
                // Update progress
                progressFill.style.width = '95%';
                progressText.textContent = 'Finalizing Excel document...';
                
                // Format current date for filename
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const year = now.getFullYear();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                
                // Generate Excel file
                const fileName = `Medal_Results_${day}-${month}-${year}_${hours}-${minutes}.xlsx`;
                XLSX.writeFile(wb, fileName);
                
                // Complete progress
                progressFill.style.width = '100%';
                progressText.textContent = 'Excel file downloaded!';
                
                // Add a fancy animation to the button
                excelExportBtn.classList.add('export-success');
                setTimeout(() => {
                    excelExportBtn.classList.remove('export-success');
                }, 1500);
                
            } catch (error) {
                console.error('Error generating Excel:', error);
                alert('Error generating Excel. Please try again.');
            } finally {
                // Hide progress and overlay after a short delay
                setTimeout(() => {
                    exportOverlay.style.display = 'none';
                    progressBar.style.display = 'none';
                    excelExportBtn.disabled = false;
                }, 1500);
            }
        }, 500);
    });
    
    // Add some CSS for the Excel button and animations
    const style = document.createElement('style');
    style.textContent = `
        .excel-export-btn {
            background-color: #107C41 !important;
        }
        .excel-export-btn:hover {
            background-color: #0B5A30 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }
        .export-success {
            animation: pulse 1.5s ease;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); box-shadow: 0 0 20px rgba(16, 124, 65, 0.5); }
            100% { transform: scale(1); }
        }
        /* Add space for multiple buttons */
        .export-btn {
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        
        /* Floating medal animation */
        .floating-medal {
            position: fixed;
            font-size: 80px;
            z-index: 2000;
            top: -80px;
            animation: floatMedal 4s ease-in-out forwards;
            text-shadow: 0 0 15px rgba(255,255,255,0.8);
            transform-origin: center;
        }
        
        @keyframes floatMedal {
            0% { 
                top: -80px; 
                transform: rotate(0deg) scale(0.7);
            }
            10% {
                transform: rotate(-20deg) scale(0.9);
            }
            25% { 
                transform: rotate(15deg) scale(1); 
            }
            40% {
                transform: rotate(-10deg) scale(1.1);
            }
            50% { 
                top: 40%; 
                transform: rotate(5deg) scale(1.2); 
            }
            60% {
                transform: rotate(-5deg) scale(1.1);
            }
            75% { 
                transform: rotate(0deg) scale(1); 
            }
            100% { 
                top: 120%; 
                transform: rotate(0deg) scale(0.7); 
            }
        }
        
        /* Improve progress bar animations */
        .progress-fill {
            transition: width 0.8s cubic-bezier(0.22, 0.61, 0.36, 1) !important;
            background-image: linear-gradient(90deg, #107C41 0%, #16A75C 100%) !important;
            background-size: 200% 100% !important;
            animation: gradientShift 2s infinite linear !important;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Improve export dialog */
        #exportProgress {
            border-radius: 15px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
            border-top: 5px solid #107C41 !important;
        }
        
        /* Visual feedback when hovering both buttons */
        #exportPDF:hover ~ #exportExcel,
        #exportExcel:hover ~ #exportPDF {
            opacity: 0.7;
        }
    `;
    document.head.appendChild(style);
});
</script>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// PDF Export functionality - add this at the end of your script section
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportPDF');
    const progressBar = document.getElementById('exportProgress');
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.getElementById('progressText');
    const exportOverlay = document.getElementById('exportOverlay');
    
    // Only add this event listener once
    if (exportBtn && !exportBtn.hasAttribute('data-listener-attached')) {
        exportBtn.setAttribute('data-listener-attached', 'true');
        
        exportBtn.addEventListener('click', function() {
            // Show overlay to block user interaction
            exportOverlay.style.display = 'block';
            progressBar.style.display = 'block';
            exportBtn.disabled = true;
            progressFill.style.width = '10%';
            progressText.textContent = 'Preparing document...';
            
            setTimeout(async () => {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF('l', 'mm', 'a4');
                    
                    // Document styling constants
                    const colors = {
                        primary: [0, 73, 30],       // Dark Green #00491e
                        secondary: [2, 104, 30],     // Bright Green #02681e
                        accent: [255, 255, 255],     // White instead of blue
                        gold: [255, 184, 0],         // Gold color
                        silver: [183, 183, 183],     // Silver color
                        bronze: [205, 127, 50],      // Bronze color
                        light: [241, 245, 249],
                        white: [255, 255, 255],
                        lightText: [148, 163, 184],
                        darkText: [30, 41, 59]
                    };
                    
                    // Create cover page
                    doc.setFillColor(...colors.primary);
                    doc.rect(0, 0, doc.internal.pageSize.width, doc.internal.pageSize.height, 'F');
                    
                    // Add design element - white section at bottom
                    doc.setFillColor(...colors.white);
                    doc.roundedRect(0, doc.internal.pageSize.height - 110, doc.internal.pageSize.width, 110, 80, 0, 'F');
                    
                    // Add header text
                    doc.setTextColor(...colors.white);
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(38);
                    doc.text('Medal Results', 30, 50);
                    
                    doc.setFontSize(26);
                    doc.text('Dashboard Report', 30, 65);
                    
                    // Add medal icons
                    doc.setFillColor(...colors.gold);
                    doc.circle(35, 100, 10, 'F');
                    doc.setFillColor(...colors.silver);
                    doc.circle(65, 100, 10, 'F');
                    doc.setFillColor(...colors.bronze);
                    doc.circle(95, 100, 10, 'F');
                    
                    // Add date (dark text in the white section)
                    doc.setTextColor(...colors.darkText);
                    doc.setFontSize(12);
                    
                    // Format the current date
                    const now = new Date();
                    const day = String(now.getDate()).padStart(2, '0');
                    const year = now.getFullYear();
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    const monthName = monthNames[now.getMonth()];
                    const hours12 = now.getHours() % 12 || 12;
                    const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                    const formattedHours = String(hours12).padStart(2, '0');
                    const formattedMinutes = String(now.getMinutes()).padStart(2, '0');
                    const formattedDateTime = `${day}-${monthName}-${year} ${formattedHours}:${formattedMinutes} ${ampm}`;
                    
                    doc.text(`Generated: ${formattedDateTime}`, 30, 140);
                    
                    progressFill.style.width = '40%';
                    progressText.textContent = 'Creating medal standings table...';
                    
                    // Add content page for results table
                    doc.addPage();
                    
                    // Create header
                    doc.setFillColor(...colors.primary);
                    doc.rect(0, 0, doc.internal.pageSize.width, 20, 'F');
                    
                    doc.setTextColor(...colors.white);
                    doc.setFontSize(12);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Medal Results Dashboard', doc.internal.pageSize.width / 2, 13, { align: 'center' });
                    
                    // Add table title
                    doc.setTextColor(...colors.darkText);
                    doc.setFontSize(16);
                    doc.setFont('helvetica', 'bold');
                    doc.text('Medal Standings', doc.internal.pageSize.width / 2, 30, { align: 'center' });
                    
                    // Extract table data from the medal tally
                    const tableData = [];
                    const table = document.getElementById('state-saving-datatable');
                    const rows = Array.from(table.querySelectorAll('tbody tr'));
                    
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = Array.from(row.cells);
                        
                        // Extract data from each cell
                        const rowData = [
                            cells[0].textContent.trim(), // Rank
                            cells[1].querySelector('span') ? cells[1].querySelector('span').textContent.trim() : cells[1].textContent.trim(), // Team
                            cells[2].querySelector('.badge') ? cells[2].querySelector('.badge').textContent.trim() : '0', // Gold
                            cells[3].querySelector('.badge') ? cells[3].querySelector('.badge').textContent.trim() : '0', // Silver
                            cells[4].querySelector('.badge') ? cells[4].querySelector('.badge').textContent.trim() : '0', // Bronze
                            cells[5].textContent.trim(), // Total Medals
                            cells[6].textContent.trim()  // Total Weight
                        ];
                        
                        tableData.push(rowData);
                    }
                    
                    // Create medal standings table
                    doc.autoTable({
                        startY: 40,
                        head: [[
                            'Rank',
                            'Team',
                            'Gold',
                            'Silver',
                            'Bronze',
                            'Total Medals',
                            'Total Weight'
                        ]],
                        body: tableData,
                        theme: 'grid',
                        styles: {
                            fontSize: 10,
                            cellPadding: 4,
                            lineColor: [240, 240, 240],
                            lineWidth: 0.1,
                            font: 'helvetica',
                        },
                        headStyles: {
                            fillColor: colors.primary,
                            textColor: colors.white,
                            fontSize: 11,
                            fontStyle: 'bold',
                            halign: 'center',
                            cellPadding: 5,
                            lineWidth: 0
                        },
                        columnStyles: {
                            0: { halign: 'center', fontStyle: 'bold', cellWidth: 15 },
                            1: { halign: 'left', cellWidth: 'auto' },
                            2: { halign: 'center', fontStyle: 'bold', cellWidth: 20 },
                            3: { halign: 'center', fontStyle: 'bold', cellWidth: 20 },
                            4: { halign: 'center', fontStyle: 'bold', cellWidth: 20 },
                            5: { halign: 'center', fontStyle: 'bold', cellWidth: 30 },
                            6: { halign: 'center', fontStyle: 'bold', cellWidth: 30 }
                        },
                        alternateRowStyles: {
                            fillColor: colors.light
                        },
                        didParseCell: function(data) {
                            // Style medal count cells
                            if (data.column.index === 2 && data.section === 'body') {
                                data.cell.styles.textColor = colors.gold;
                            } else if (data.column.index === 3 && data.section === 'body') {
                                data.cell.styles.textColor = colors.silver;
                            } else if (data.column.index === 4 && data.section === 'body') {
                                data.cell.styles.textColor = colors.bronze;
                            }
                        },
                        margin: { top: 20, left: 10, right: 10 },
                    });
                    
                    progressFill.style.width = '80%';
                    progressText.textContent = 'Finishing document...';
                    
                    // Add footers to all pages
                    const pageCount = doc.internal.getNumberOfPages();
                    for (let i = 1; i <= pageCount; i++) {
                        doc.setPage(i);
                        
                        // Skip footer on cover page
                        if (i === 1) continue;
                        
                        // Add footer line
                        doc.setDrawColor(...colors.light);
                        doc.setLineWidth(0.5);
                        doc.line(10, doc.internal.pageSize.height - 15, doc.internal.pageSize.width - 10, doc.internal.pageSize.height - 15);
                        
                        // Add page number
                        doc.setFillColor(...colors.light);
                        doc.roundedRect(doc.internal.pageSize.width / 2 - 15, doc.internal.pageSize.height - 12, 30, 8, 4, 4, 'F');
                        
                        doc.setTextColor(...colors.darkText);
                        doc.setFontSize(8);
                        doc.text(
                            `Page ${i} of ${pageCount}`, 
                            doc.internal.pageSize.width / 2, 
                            doc.internal.pageSize.height - 7,
                            { align: 'center' }
                        );
                        
                        // Add footer text
                        doc.setTextColor(...colors.secondary);
                        doc.text(
                            'Medal Results Dashboard', 
                            20, 
                            doc.internal.pageSize.height - 7
                        );
                    }
                    
                    progressFill.style.width = '100%';
                    progressText.textContent = 'Download starting...';
                    
                    // Save the PDF with formatted filename
                    const filename = `Medal_Dashboard_${day}-${monthName}-${year}_${formattedHours}-${formattedMinutes}-${ampm}.pdf`;
                    doc.save(filename);
                    
                } catch (error) {
                    console.error('Error generating PDF:', error);
                    alert('Error generating PDF. Please try again.');
                } finally {
                    // Hide overlay and progress bar
                    exportOverlay.style.display = 'none';
                    progressBar.style.display = 'none';
                    exportBtn.disabled = false;
                }
            }, 100);
        });
    }
});
</script>
@endsection

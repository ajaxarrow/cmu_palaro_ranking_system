    @extends('layouts.admin', ['title' => $team->name])

    @section('css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
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
                                    <i class="ph ph-arrow-left me-1"></i> Back to Teams List
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
                                <div class="text-muted">Weight: {{ number_format($overallTotal['gold_weight'], 3) }}</div>
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
                                <div class="text-muted">Weight: {{ number_format($overallTotal['silver_weight'], 3) }}</div>
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
                                <div class="text-muted">Weight: {{ number_format($overallTotal['bronze_weight'], 3) }}</div>
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
                                <div>Weight: {{ number_format($overallTotal['total_weight'], 3) }}</div>
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
                <div class="chart-toggle flex justify-content-between align-items-center">
                    <div class="">
                        <button class="chart-tab active me-2" data-target="sports-table">Sports Table</button>
                        <button class="chart-tab" data-target="bar-chart">Medal Distribution</button>
                        <button class="chart-tab" data-target="donut-chart">Medal Breakdown</button>
                        <button class="chart-tab" data-target="sports-chart">Sports Analysis</button>
                    </div>

                    <div class="d-flex gap-3">
                        <button id="exportPDF" class="export-btn">
                            <i class="ph ph-file-pdf"></i>
                            Export to PDF
                        </button>

                    </div>

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
                                                <td class="text-center">{{ number_format($event['gold_weight'], 3) }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark">{{ $event['silver_tally'] }}</span>
                                                </td>
                                                <td class="text-center">{{ number_format($event['silver_weight'], 3) }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-danger-subtle text-dark">{{ $event['bronze_tally'] }}</span>
                                                </td>
                                                <td class="text-center">{{ number_format($event['bronze_weight'], 3) }}</td>
                                                <td class="text-center">
                                                    <strong>{{ $event['total_medals'] }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <strong>{{ number_format($event['total_weight'], 3) }}</strong>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- Sport Total Row -->
                                        <tr class="table-secondary">
                                            <td><strong>Total for {{ $sport }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark">{{ $data['total']['gold_tally'] }}</span>
                                            </td>
                                            <td class="text-center"><strong>{{ number_format($data['total']['gold_weight'], 3) }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $data['total']['silver_tally'] }}</span>
                                            </td>
                                            <td class="text-center"><strong>{{ number_format($data['total']['silver_weight'], 3) }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge bg-danger-subtle text-dark">{{ $data['total']['bronze_tally'] }}</span>
                                            </td>
                                            <td class="text-center"><strong>{{ number_format($data['total']['bronze_weight'], 3) }}</strong></td>
                                            <td class="text-center"><strong>{{ $data['total']['total_medals'] }}</strong></td>
                                            <td class="text-center"><strong>{{ number_format($data['total']['total_weight'], 3) }}</strong></td>
                                        </tr>
                                    @endforeach
                                    <!-- Overall Total Row -->
                                    <tr class="table-primary">
                                        <td colspan="2"><strong>Overall Total</strong></td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark">{{ $overallTotal['gold_tally'] }}</span>
                                        </td>
                                        <td class="text-center"><strong>{{ number_format($overallTotal['gold_weight'], 3) }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark">{{ $overallTotal['silver_tally'] }}</span>
                                        </td>
                                        <td class="text-center"><strong>{{ number_format($overallTotal['silver_weight'], 3) }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge bg-danger-subtle text-dark">{{ $overallTotal['bronze_tally'] }}</span>
                                        </td>
                                        <td class="text-center"><strong>{{ number_format($overallTotal['bronze_weight'], 3) }}</strong></td>
                                        <td class="text-center"><strong>{{ $overallTotal['total_medals'] }}</strong></td>
                                        <td class="text-center"><strong>{{ number_format($overallTotal['total_weight'], 3) }}</strong></td>
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


    <div id="exportProgress">
        <h4>Generating PDF Report...</h4>
        <div class="progress-bar">
            <div class="progress-fill" style="width: 0%"></div>
        </div>
        <p id="progressText">Preparing document...</p>
    </div>

    @endsection

    @section('script')
    @vite(['resources/js/pages/simple-datatables.init.js',])

    <!-- Include ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        // Add the SheetJS CDN link to the head section
// Add the SheetJS CDN link to the head section
document.addEventListener('DOMContentLoaded', function() {
    // Add Excel export button next to PDF export button
    const pdfButton = document.getElementById('exportPDF');
    const buttonContainer = pdfButton.parentNode;

    const excelButton = document.createElement('button');
    excelButton.id = 'exportExcel';
    excelButton.className = 'export-btn';
    excelButton.innerHTML = '<i class="ph ph-file-xls"></i> Export to Excel';

    buttonContainer.appendChild(excelButton);

    // Add Excel export functionality
    document.getElementById('exportExcel').addEventListener('click', function() {
        exportToExcel();
    });

    function exportToExcel() {
        // Show progress indicator
        const progressElement = document.getElementById('exportProgress');
        const progressFill = progressElement.querySelector('.progress-fill');
        const progressText = document.getElementById('progressText');

        progressElement.style.display = 'block';
        progressFill.style.width = '10%';
        progressText.textContent = 'Preparing data...';

        // Disable the export button
        document.getElementById('exportExcel').disabled = true;

        setTimeout(() => {
            try {
                // Update progress
                progressFill.style.width = '30%';
                progressText.textContent = 'Extracting medal data...';

                // Create workbook
                const wb = XLSX.utils.book_new();

                // Extract team name from heading
                const teamName = document.querySelector('h3.fw-bold').textContent
                    .trim()
                    .replace('🏆', '')
                    .replace('Medal Dashboard', '')
                    .trim();

                // Get current date for filename
                const today = new Date();
                const dateStr = today.toISOString().split('T')[0]; // YYYY-MM-DD format

                // FIRST WORKSHEET: Summary
                progressFill.style.width = '50%';
                progressText.textContent = 'Creating medal summary...';


                // SECOND WORKSHEET: Detailed breakdown
                progressFill.style.width = '70%';
                progressText.textContent = 'Creating detailed breakdown...';

                // Function to extract table data
                function extractTableData() {
                    const table = document.getElementById('saving-datatable-shesh');
                    if (!table) {
                        console.error("Table not found");
                        return [];
                    }

                    // Get column headers
                    const headers = [];
                    const headerRow = table.querySelector('thead tr');
                    headerRow.querySelectorAll('th').forEach(th => {
                        // Extract text without the medal icons
                        let headerText = th.textContent.trim();
                        if (th.querySelector('.medal-icon')) {
                            headerText = th.querySelector('.medal-icon').nextElementSibling.textContent.trim();
                            // Add prefix based on medal type
                            if (th.querySelector('.medal-gold')) {
                                headerText = 'Gold ' + headerText;
                            } else if (th.querySelector('.medal-silver')) {
                                headerText = 'Silver ' + headerText;
                            } else if (th.querySelector('.medal-bronze')) {
                                headerText = 'Bronze ' + headerText;
                            }
                        }
                        headers.push(headerText);
                    });

                    const tableData = [headers]; // Start with headers
                    let currentSport = '';
                    let sportRows = {};

                    // Helper function to extract text, including from badges
                    function getCellText(cell) {
                        if (!cell) return '';

                        // First try to get text from badge if it exists
                        const badge = cell.querySelector('.badge');
                        if (badge) {
                            return badge.textContent.trim();
                        }

                        // Otherwise get the regular text
                        return cell.textContent.trim();
                    }

                    // Process all rows
                    const rows = Array.from(table.querySelectorAll('tbody tr'));

                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = Array.from(row.cells);

                        // Check row type
                        if (row.classList.contains('table-primary')) {
                            // Overall total row - has colspan="2" in first cell
                            const rowData = ['Overall Total', ''];

                            // Start from cell index 2 (because of colspan=2)
                            for (let j = 2; j < cells.length; j++) {
                                rowData.push(getCellText(cells[j]));
                            }

                            tableData.push(rowData);
                        }
                        else if (row.classList.contains('table-secondary')) {
                            // Sport total row
                            const sportName = getCellText(cells[0]).replace('Total for', '').trim();
                            const rowData = ['Total for ' + sportName, ''];

                            // Get values from remaining cells
                            for (let j = 1; j < cells.length; j++) {
                                rowData.push(getCellText(cells[j]));
                            }

                            // Store the sport total row to insert after all event rows
                            if (sportRows[sportName]) {
                                tableData.push(...sportRows[sportName]);
                                tableData.push(rowData);
                                delete sportRows[sportName];
                            } else {
                                tableData.push(rowData);
                            }
                        }
                        else {
                            // Check if it's a row with a rowspan (first row for a sport)
                            if (cells[0] && cells[0].hasAttribute('rowspan')) {
                                // This is the first row for a sport
                                currentSport = getCellText(cells[0]);
                                const eventCategory = getCellText(cells[1]);

                                const rowData = [currentSport, eventCategory];

                                // Add all medal data
                                for (let j = 2; j < cells.length; j++) {
                                    rowData.push(getCellText(cells[j]));
                                }

                                // Initialize array for this sport if it doesn't exist
                                if (!sportRows[currentSport]) {
                                    sportRows[currentSport] = [];
                                }

                                sportRows[currentSport].push(rowData);
                            }
                            else if (currentSport) {
                                // This is a continued row for the current sport
                                const eventCategory = getCellText(cells[0]);

                                const rowData = [currentSport, eventCategory];

                                // Add all medal data
                                for (let j = 1; j < cells.length; j++) {
                                    rowData.push(getCellText(cells[j]));
                                }

                                // Add to the sport's rows
                                if (!sportRows[currentSport]) {
                                    sportRows[currentSport] = [];
                                }

                                sportRows[currentSport].push(rowData);
                            }
                        }
                    }

                    // Add any remaining sport rows
                    for (const sport in sportRows) {
                        if (sportRows[sport].length > 0) {
                            tableData.push(...sportRows[sport]);
                        }
                    }

                    return tableData;
                }

                const tableData = extractTableData();
                const detailedWs = XLSX.utils.aoa_to_sheet(tableData);
                XLSX.utils.book_append_sheet(wb, detailedWs, "Medal Breakdown");

                // Apply styling to detailed worksheet
                progressFill.style.width = '90%';
                progressText.textContent = 'Applying formatting...';

                // Set column widths
                detailedWs['!cols'] = [
                    { wch: 20 },  // Sports
                    { wch: 25 },  // Event Categories
                    { wch: 12 },  // Gold Count
                    { wch: 12 },  // Gold Weight
                    { wch: 12 },  // Silver Count
                    { wch: 12 },  // Silver Weight
                    { wch: 12 },  // Bronze Count
                    { wch: 12 },  // Bronze Weight
                    { wch: 12 },  // Total Medals
                    { wch: 12 }   // Total Weight
                ];

                // Generate the Excel file
                progressFill.style.width = '95%';
                progressText.textContent = 'Generating Excel file...';

                // Create the Excel file with formatted date/time
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const year = now.getFullYear();
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const monthName = monthNames[now.getMonth()];

                // Format hours for 12-hour clock with AM/PM
                const hours12 = now.getHours() % 12 || 12;
                const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                const formattedHours = String(hours12).padStart(2, '0');
                const formattedMinutes = String(now.getMinutes()).padStart(2, '0');

                // Create the formatted datetime string
                const formattedDateTime = `${day}-${monthName}-${year}_${formattedHours}-${formattedMinutes}-${ampm}`;
                const filename = `${teamName}_medal_report_${formattedDateTime}.xlsx`;

                XLSX.writeFile(wb, filename);

                // Complete progress
                progressFill.style.width = '100%';
                progressText.textContent = 'Excel export completed!';

                setTimeout(() => {
                    // Hide progress and re-enable button
                    progressElement.style.display = 'none';
                    document.getElementById('exportExcel').disabled = false;
                }, 1000);

            } catch (error) {
                console.error('Error exporting to Excel:', error);
                progressText.textContent = 'Error: ' + error.message;
                document.getElementById('exportExcel').disabled = false;
            }
        }, 500); // Small delay to show initial progress
    }
});
    </script>

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
        // Replace the entire existing PDF export event listener with this code
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.getElementById('exportPDF');
    const progressBar = document.getElementById('exportProgress');
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.getElementById('progressText');

    exportBtn.addEventListener('click', function() {
        progressBar.style.display = 'block';
        exportBtn.disabled = true;
        progressFill.style.width = '10%';
        progressText.textContent = 'Preparing document...';

        setTimeout(() => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');

            // Get team name
            const teamName = document.querySelector('.fw-bold.mb-0').textContent
                .trim()
                .replace('🏆', '')
                .replace('Medal Dashboard', '')
                .trim();

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

            // Add design element - changed from blue to white
            doc.setFillColor(...colors.white);
            doc.roundedRect(0, doc.internal.pageSize.height - 110, doc.internal.pageSize.width, 110, 80, 0, 'F');

            // Add header text (changed text color to dark for the bottom section)
            doc.setTextColor(...colors.white);
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(38);
            doc.text(teamName, 30, 50);

            doc.setFontSize(26);
            doc.text('Medal Report', 30, 65);

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
            doc.text(`Generated: ${new Date().toLocaleDateString()}`, 30, 140);

            // Add content page
            doc.addPage();

            // Create header
            doc.setFillColor(...colors.primary);
            doc.rect(0, 0, doc.internal.pageSize.width, 20, 'F');

            doc.setTextColor(...colors.white);
            doc.setFontSize(12);
            doc.setFont('helvetica', 'bold');
            doc.text(teamName, doc.internal.pageSize.width / 2, 13, { align: 'center' });

            progressFill.style.width = '30%';
            progressText.textContent = 'Adding medal summary...';

            // Summary boxes
            const summaryStartY = 30;
            const boxWidth = 60;
            const boxHeight = 30;
            const gapBetweenBoxes = 10;

            // Gold summary
            doc.setFillColor(...colors.gold);
            doc.roundedRect(20, summaryStartY, boxWidth, boxHeight, 3, 3, 'F');
            doc.setTextColor(...colors.darkText);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('Gold Medals', 50, summaryStartY + 10, { align: 'center' });
            doc.setFontSize(18);
            doc.text(document.querySelector('.col-md-3:nth-child(1) .fs-3.fw-bold').textContent.trim(),
                    50, summaryStartY + 22, { align: 'center' });

            // Silver summary
            doc.setFillColor(...colors.silver);
            doc.roundedRect(20 + boxWidth + gapBetweenBoxes, summaryStartY, boxWidth, boxHeight, 3, 3, 'F');
            doc.setTextColor(...colors.darkText);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('Silver Medals', 50 + boxWidth + gapBetweenBoxes, summaryStartY + 10, { align: 'center' });
            doc.setFontSize(18);
            doc.text(document.querySelector('.col-md-3:nth-child(2) .fs-3.fw-bold').textContent.trim(),
                    50 + boxWidth + gapBetweenBoxes, summaryStartY + 22, { align: 'center' });

            // Bronze summary
            doc.setFillColor(...colors.bronze);
            doc.roundedRect(20 + (boxWidth + gapBetweenBoxes) * 2, summaryStartY, boxWidth, boxHeight, 3, 3, 'F');
            doc.setTextColor(...colors.darkText);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('Bronze Medals', 50 + (boxWidth + gapBetweenBoxes) * 2, summaryStartY + 10, { align: 'center' });
            doc.setFontSize(18);
            doc.text(document.querySelector('.col-md-3:nth-child(3) .fs-3.fw-bold').textContent.trim(),
                    50 + (boxWidth + gapBetweenBoxes) * 2, summaryStartY + 22, { align: 'center' });

            // Total summary
            doc.setFillColor(...colors.primary);
            doc.roundedRect(20 + (boxWidth + gapBetweenBoxes) * 3, summaryStartY, boxWidth, boxHeight, 3, 3, 'F');
            doc.setTextColor(...colors.white);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('Total Medals', 50 + (boxWidth + gapBetweenBoxes) * 3, summaryStartY + 10, { align: 'center' });
            doc.setFontSize(18);
            doc.text(document.querySelector('.col-md-3:nth-child(4) .fs-3.fw-bold').textContent.trim(),
                    50 + (boxWidth + gapBetweenBoxes) * 3, summaryStartY + 22, { align: 'center' });

            // Section title
            doc.setTextColor(...colors.darkText);
            doc.setFontSize(14);
            doc.setFont('helvetica', 'bold');
            doc.text('Medal Breakdown by Sport & Event', 20, summaryStartY + boxHeight + 15);

            progressFill.style.width = '60%';
            progressText.textContent = 'Processing table data...';

            // COMPLETELY REWRITTEN TABLE DATA PROCESSING
            function extractTableData() {
                const table = document.getElementById('saving-datatable-shesh');
                if (!table) {
                    console.error("Table not found");
                    return [];
                }

                const tableData = [];
                let currentSport = '';

                // Helper function to extract text, including from badges
                function getCellText(cell) {
                    if (!cell) return '';

                    // First try to get text from badge if it exists
                    const badge = cell.querySelector('.badge');
                    if (badge) {
                        return badge.textContent.trim();
                    }

                    // Otherwise get the regular text
                    return cell.textContent.trim();
                }

                // Process all rows
                const rows = Array.from(table.querySelectorAll('tbody tr'));

                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];

                    // Skip hidden rows
                    if (window.getComputedStyle(row).display === 'none') {
                        continue;
                    }

                    // Get all cells
                    const cells = Array.from(row.cells);

                    // Check row type and process accordingly
                    if (row.classList.contains('table-primary')) {
                        // Overall total row (has colspan=2 in first cell)
                        const rowData = [
                            'Overall Total',
                            '',  // Empty cell for second column
                        ];

                        // Add all the medal data (start from index 2 because of colspan=2)
                        for (let j = 2; j < cells.length; j++) {
                            rowData.push(getCellText(cells[j]));
                        }

                        tableData.push(rowData);
                    }
                    else if (row.classList.contains('table-secondary')) {
                        // Sport total row
                        const sportTotalText = getCellText(cells[0]);

                        const rowData = [
                            sportTotalText,
                            '',  // Empty cell for event category
                        ];

                        // Add all the medal data (start from index 1)
                        for (let j = 1; j < cells.length; j++) {
                            rowData.push(getCellText(cells[j]));
                        }

                        tableData.push(rowData);
                    }
                    else {
                        // Regular event row or first sport row with rowspan

                        // Check if this is a row with rowspan (first row for a sport)
                        if (cells[0].hasAttribute('rowspan')) {
                            // Save the sport name for future rows
                            currentSport = getCellText(cells[0]);

                            const rowData = [
                                currentSport,
                                getCellText(cells[1]),  // Event name
                            ];

                            // Add all the medal data (start from index 2)
                            for (let j = 2; j < cells.length; j++) {
                                rowData.push(getCellText(cells[j]));
                            }

                            tableData.push(rowData);
                        }
                        else {
                            // Regular event row (continuing from previous sport)
                            const rowData = [
                                currentSport,  // Use the saved sport name
                                getCellText(cells[0]),  // Event name is the first cell
                            ];

                            // Add all the medal data (start from index 1)
                            for (let j = 1; j < cells.length; j++) {
                                rowData.push(getCellText(cells[j]));
                            }

                            tableData.push(rowData);
                        }
                    }
                }

                return tableData;
            }

            // Extract table data
            const tableData = extractTableData();
            console.log("Extracted table data:", tableData);

            progressFill.style.width = '80%';
            progressText.textContent = 'Generating table...';

            // Create table
            doc.autoTable({
                startY: summaryStartY + boxHeight + 20,
                head: [[
                    'Sports',
                    'Event Categories',
                    'Gold Count',
                    'Gold Weight',
                    'Silver Count',
                    'Silver Weight',
                    'Bronze Count',
                    'Bronze Weight',
                    'Total Medals',
                    'Total Weight'
                ]],
                body: tableData,
                theme: 'grid',
                styles: {
                    fontSize: 9,
                    cellPadding: 4,
                    lineColor: [240, 240, 240],
                    lineWidth: 0.1,
                    font: 'helvetica',
                },
                headStyles: {
                    fillColor: colors.primary,
                    textColor: colors.white,
                    fontSize: 10,
                    fontStyle: 'bold',
                    halign: 'center',
                    cellPadding: 5,
                    lineWidth: 0
                },
                columnStyles: {
                    0: { halign: 'left', fontStyle: 'bold', cellWidth: 'auto' },
                    1: { halign: 'left', cellWidth: 'auto' },
                    2: { halign: 'center', cellWidth: 'auto' },
                    3: { halign: 'center', cellWidth: 'auto' },
                    4: { halign: 'center', cellWidth: 'auto' },
                    5: { halign: 'center', cellWidth: 'auto' },
                    6: { halign: 'center', cellWidth: 'auto' },
                    7: { halign: 'center', cellWidth: 'auto' },
                    8: { halign: 'center', fontStyle: 'bold', cellWidth: 'auto' },
                    9: { halign: 'center', fontStyle: 'bold', cellWidth: 'auto' }
                },
                alternateRowStyles: {
                    fillColor: colors.light
                },
                didParseCell: function(data) {
                    const rowData = data.row.raw;
                    const cellText = data.cell.text.join(' ');

                    // Style medal count cells
                    if (data.column.index === 2 && data.section === 'body') {
                        data.cell.styles.textColor = colors.gold;
                        data.cell.styles.fontStyle = 'bold';
                    } else if (data.column.index === 4 && data.section === 'body') {
                        data.cell.styles.textColor = colors.silver;
                        data.cell.styles.fontStyle = 'bold';
                    } else if (data.column.index === 6 && data.section === 'body') {
                        data.cell.styles.textColor = colors.bronze;
                        data.cell.styles.fontStyle = 'bold';
                    }

                    // Style total rows
                    if (rowData[0].includes('Total for')) {
                        data.cell.styles.fillColor = [240, 245, 250];
                        data.cell.styles.textColor = colors.darkText;
                        data.cell.styles.fontStyle = 'bold';
                    } else if (rowData[0] === 'Overall Total') {
                        data.cell.styles.fillColor = colors.primary;
                        data.cell.styles.textColor = colors.white;
                        data.cell.styles.fontStyle = 'bold';
                    }
                },
                willDrawCell: function(data) {
                    // Add rounded corners to first and last column cells
                    if (data.section === 'body') {
                        if (data.column.index === 0) {
                            doc.roundedRect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 1, 1, 'F');
                        } else if (data.column.index === data.table.columns.length - 1) {
                            doc.roundedRect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 1, 1, 'F');
                        }
                    }
                },
                margin: { top: 20, left: 10, right: 10 },
            });

            // Add footers
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
                    'Medal Tally System',
                    20,
                    doc.internal.pageSize.height - 7
                );

                // Add team name
                doc.text(
                    teamName,
                    doc.internal.pageSize.width - 20,
                    doc.internal.pageSize.height - 7,
                    { align: 'right' }
                );
            }

            progressFill.style.width = '100%';
            progressText.textContent = 'Download starting...';

            setTimeout(() => {
                const now = new Date();
                const day = String(now.getDate()).padStart(2, '0');
                const year = now.getFullYear();
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                const monthName = monthNames[now.getMonth()];

                // Format hours for 12-hour clock with AM/PM
                const hours12 = now.getHours() % 12 || 12;
                const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
                const formattedHours = String(hours12).padStart(2, '0');
                const formattedMinutes = String(now.getMinutes()).padStart(2, '0');

                // Create the formatted datetime string
                const formattedDateTime = `${day}-${monthName}-${year}_${formattedHours}-${formattedMinutes}-${ampm}`;
                const filename = `${teamName}_medal_report_${formattedDateTime}.pdf`;

                doc.save(filename);
                progressBar.style.display = 'none';
                exportBtn.disabled = false;
            }, 500);
        }, 100);
    });
});
    </script>
    @endsection

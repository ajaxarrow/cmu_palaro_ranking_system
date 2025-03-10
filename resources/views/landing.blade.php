<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMU PALARO 2024 - Medal Tally</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    @vite(['resources/css/landingpage.css'])

  
</head>
<body>

<style>
  .hero-section {
    position: relative;
    min-height: 100vh;
    background: url('{{ Vite::asset('resources/images/landingPageBkg.jpg') }}') center/cover no-repeat !important;
    overflow: hidden;
}


.analytics-section {
    position: relative;
    overflow: hidden;
}

.analytics-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 400px;
    background: linear-gradient(180deg, rgba(0,73,30,0.03) 0%, rgba(255,255,255,0) 100%);
    pointer-events: none;
}

.chart-card {
    background: white;
    border-radius: 24px;
    padding: 1.5rem;
    box-shadow: 
        0 4px 6px -1px rgba(0,0,0,0.05),
        0 2px 4px -1px rgba(0,0,0,0.03),
        0 0 0 1px rgba(0,0,0,0.02);
    transition: all 0.3s ease;
    overflow: hidden;
}

.chart-card:hover {
    transform: translateY(-2px);
    box-shadow: 
        0 20px 25px -5px rgba(0,0,0,0.05),
        0 10px 10px -5px rgba(0,0,0,0.02),
        0 0 0 1px rgba(0,0,0,0.02);
}

.featured-chart {
    background: linear-gradient(145deg, #ffffff, #f8fafc);
    border: 1px solid rgba(0,73,30,0.1);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 0.25rem;
}

.chart-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
}

.chart-actions {
    display: flex;
    gap: 0.5rem;
}

.chart-action-btn {
    padding: 0.5rem;
    border-radius: 8px;
    color: #6b7280;
    transition: all 0.2s ease;
}

.chart-action-btn:hover {
    background: rgba(0,73,30,0.05);
    color: #00491E;
}
</style>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        
        <div class="hero-content container mx-auto px-4 pt-20 pb-32">
            <!-- Header -->
            <div class="text-center mb-[150px]" data-aos="fade-down">
                <h1 class="text-7xl font-extrabold text-white mb-6 tracking-tight">
                    <span class="title-accent">CMU PALARO {{ date('Y') }}</span>
                    <span class="block text-3xl mt-8 bg-clip-text text-transparent bg-gradient-to-r from-yellow-400 to-yellow-200">
                        
                    Excellence Through Sports

                    </span>
                </h1>
            </div>

            <!-- Podium Section -->
            <div class="podium-section max-w-6xl mx-auto mb-20" data-aos="fade-up">
                <!-- Desktop Podium -->
                <div class="podium-desktop">
                    <div class="flex justify-center items-end h-[400px] gap-4">
                        <!-- Second Place -->
                        <div class="podium-item second-place">
                            @if(isset($medalTally[1]))
                            <div class="podium-content">
                                <div class="winner-image">
                                    <img src="{{ $medalTally[1]['team_image'] ? Vite::asset('storage/app/public/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[1]['team'] }}"
                                         class="relative z-10">
                                    <div class="winner-badge silver">2</div>
                                </div>
                                <h3 class="winner-name">{{ $medalTally[1]['team'] }}</h3>
                                <div class="medal-stats">
                                    <div class="medal gold">
                                        <span>🥇</span>
                                        <span>{{ $medalTally[1]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver">
                                        <span>🥈</span>
                                        <span>{{ $medalTally[1]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze">
                                        <span>🥉</span>
                                        <span>{{ $medalTally[1]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-points">
                                    {{ number_format($medalTally[1]['total']['total_weight'], 2) }} pts
                                </div>
                            </div>
                            <div class="podium-stand silver"></div>
                            @endif
                        </div>

                        <!-- First Place -->
                        <div class="podium-item champion">
                            @if(isset($medalTally[0]))
                            <div class="podium-content">
                                <div class="winner-image">
                                    <img src="{{ $medalTally[0]['team_image'] ? Vite::asset('storage/app/public/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[0]['team'] }}"
                                         class="relative z-10">
                                    <div class="winner-badge gold">1</div>
                                </div>
                                <h3 class="winner-name">{{ $medalTally[0]['team'] }}</h3>
                                <div class="medal-stats">
                                    <div class="medal gold">
                                        <span>🥇</span>
                                        <span>{{ $medalTally[0]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver">
                                        <span>🥈</span>
                                        <span>{{ $medalTally[0]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze">
                                        <span>🥉</span>
                                        <span>{{ $medalTally[0]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-points champion">
                                    {{ number_format($medalTally[0]['total']['total_weight'], 2) }} pts
                                </div>
                            </div>
                            <div class="podium-stand gold"></div>
                            @endif
                        </div>

                        <!-- Third Place -->
                        <div class="podium-item third-place">
                            @if(isset($medalTally[2]))
                            <div class="podium-content">
                                <div class="winner-image">
                                    <img src="{{ $medalTally[2]['team_image'] ? Vite::asset('storage/app/public/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[2]['team'] }}"
                                         class="relative z-10">
                                    <div class="winner-badge bronze">3</div>
                                </div>
                                <h3 class="winner-name">{{ $medalTally[2]['team'] }}</h3>
                                <div class="medal-stats">
                                    <div class="medal gold">
                                        <span>🥇</span>
                                        <span>{{ $medalTally[2]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver">
                                        <span>🥈</span>
                                        <span>{{ $medalTally[2]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze">
                                        <span>🥉</span>
                                        <span>{{ $medalTally[2]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-points">
                                    {{ number_format($medalTally[2]['total']['total_weight'], 2) }} pts
                                </div>
                            </div>
                            <div class="podium-stand bronze"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mobile Podium -->
                <div class="podium-mobile">
                    <div class="mobile-podium-grid">
                        <!-- Champion Card -->
                        @if(isset($medalTally[0]))
                        <div class="mobile-podium-card champion">
                            <div class="card-content">
                                <div class="position-marker">1st</div>
                                <div class="team-image-wrapper">
                                    <img src="{{ $medalTally[0]['team_image'] ? Vite::asset('storage/app/public/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[0]['team'] }}">
                                </div>
                                <div class="team-info">
                                    <h3>{{ $medalTally[0]['team'] }}</h3>
                                    <div class="medal-row">
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥇</span>
                                            <span class="medal-count">{{ $medalTally[0]['total']['gold_tally'] }}</span>
                                        </div>
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥈</span>
                                            <span class="medal-count">{{ $medalTally[0]['total']['silver_tally'] }}</span>
                                        </div>
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥉</span>
                                            <span class="medal-count">{{ $medalTally[0]['total']['bronze_tally'] }}</span>
                                        </div>
                                    </div>
                                    <div class="total-score">
                                        {{ number_format($medalTally[0]['total']['total_weight'], 2) }} pts
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Second Place Card -->
                        @if(isset($medalTally[1]))
                        <div class="mobile-podium-card silver">
                            <div class="card-content">
                                <div class="position-marker">2nd</div>
                                <div class="team-image-wrapper">
                                    <img src="{{ $medalTally[1]['team_image'] ? Vite::asset('storage/app/public/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[1]['team'] }}">
                                </div>
                                <div class="team-info">
                                    <h3>{{ $medalTally[1]['team'] }}</h3>
                                    <div class="medal-row">
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥇</span>
                                            <span class="medal-count">{{ $medalTally[1]['total']['gold_tally'] }}</span>
                                        </div>
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥈</span>
                                            <span class="medal-count">{{ $medalTally[1]['total']['silver_tally'] }}</span>
                                        </div>
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥉</span>
                                            <span class="medal-count">{{ $medalTally[1]['total']['bronze_tally'] }}</span>
                                        </div>
                                    </div>
                                    <div class="total-score">
                                        {{ number_format($medalTally[1]['total']['total_weight'], 2) }} pts
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Third Place Card -->
                        @if(isset($medalTally[2]))
                        <div class="mobile-podium-card bronze">
                            <div class="card-content">
                                <div class="position-marker">3rd</div>
                                <div class="team-image-wrapper">
                                    <img src="{{ $medalTally[2]['team_image'] ? Vite::asset('storage/app/public/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[2]['team'] }}">
                                </div>
                                <div class="team-info">
                                    <h3>{{ $medalTally[2]['team'] }}</h3>
                                    <div class="medal-row">
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥇</span>
                                            <span class="medal-count">{{ $medalTally[2]['total']['gold_tally'] }}</span>
                                        </div>
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥈</span>
                                            <span class="medal-count">{{ $medalTally[2]['total']['silver_tally'] }}</span>
                                        </div>
                                        <div class="medal-cell">
                                            <span class="medal-icon">🥉</span>
                                            <span class="medal-count">{{ $medalTally[2]['total']['bronze_tally'] }}</span>
                                        </div>
                                    </div>
                                    <div class="total-score">
                                        {{ number_format($medalTally[2]['total']['total_weight'], 2) }} pts
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="stats-container" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-grid">
                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="100">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #00491e, #02681e)">🏅</div>
                        <div class="stat-number animated-number">
                            {{ $overallTotal['total_medals'] }}
                        </div>
                        <div class="stat-label">Total Medals</div>
                    </div>

                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="200">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #FFD700, #FFA500)">🥇</div>
                        <div class="stat-number animated-number" style="background: linear-gradient(135deg, #FFD700, #FFA500); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $overallTotal['gold_tally'] }}
                        </div>
                        <div class="stat-label">Gold Medals</div>
                    </div>

                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="300">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #C0C0C0, #A9A9A9)">🥈</div>
                        <div class="stat-number animated-number" style="background: linear-gradient(135deg, #C0C0C0, #A9A9A9); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $overallTotal['silver_tally'] }}
                        </div>
                        <div class="stat-label">Silver Medals</div>
                    </div>

                    <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #CD7F32, #8B4513)">🥉</div>
                        <div class="stat-number animated-number" style="background: linear-gradient(135deg, #CD7F32, #8B4513); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $overallTotal['bronze_tally'] }}
                        </div>
                        <div class="stat-label">Bronze Medals</div>
                    </div>
                </div>
            </div>

            <!-- Scroll Button -->
            <div class="text-center mt-16">
                <a href="#standings" 
                   class="inline-flex items-center justify-center px-8 py-3 text-lg font-medium text-white bg-green-600 border border-transparent rounded-full hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                    View Full Standings
                    <svg class="w-5 h-5 ml-2 -mr-1 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Team Standings -->
    <section id="standings" class="py-20 bg-gray-50">

        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16" data-aos="fade-up">
                Team Standings
                <div class="w-20 h-1 bg-green-600 mx-auto mt-4"></div>
            </h2>



            <div class="grid grid-cols-1 gap-[40px]">

                @foreach($medalTally as $index => $team)

                <div class="team-card" 
                     data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}" 
                     data-aos-delay="{{ $index * 100 }}"
                     data-team-id="{{ $index }}">
                    <div class="modern-card-wrapper">
                        <!-- Add gradient background -->
                        <div class="card-gradient-bg"></div>
                        <div class="card-gradient-bg right"></div>
                     
                     
                        
                        <div class="card-glass-effect"></div>
                        <div class="card-content">
                        <div class="flex flex-col lg:flex-row items-center gap-8">
                           
                            <!-- Team Info Left Side -->
                            <div class="flex-1 text-left">
                                <div class="team-header">
                                    <span class="team-label">Team</span>
                                    <h3 class="team-name">{{ $team['team'] }}</h3>
                                    <div class="d-flex column gap-0">
                                    @foreach($team['colleges'] as $college)
                                        <p class="college-name">College of {{ $college }}</p>
                                    @endforeach
                                    </div>
                                   
                                </div>
                                <div class="team-description">
                                    <p>{{ $team['description'] ?? 'Representing excellence in sports and academics.' }}</p>
                                    <div class="rank-indicator">
                                        <span class="rank-pill font-bold">
                                            Rank #{{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                             <!-- Team Image Center -->
                             <div class="team-image-section">
                                <div class="modern-image-container">
                                    <div class="rank-badge {{ 'rank-' . ($index + 1) }}">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div class="image-glow"></div>
                                    <img 
                                        src="{{ $team['team_image'] ? Vite::asset('storage/app/public/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                        alt="{{ $team['team'] }}"
                                        class="team-image"
                                        crossorigin="anonymous"
                                        loading="lazy"
                                    >
                                </div>
                            </div>

                            

                            <!-- Team Stats Right Side -->
                            <div class="flex-1 space-y-6">
                                <div class="modern-stats-grid">
                                    <div class="modern-stat-pill gold">
                                        <div class="stat-icon">🥇</div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $team['total']['gold_tally'] }}</div>
                                            <div class="stat-label">Gold</div>
                                            <div class="stat-points">{{ number_format($team['total']['gold_weight'], 2) }} pts</div>
                                        </div>
                                    </div>
                                    <div class="modern-stat-pill silver">
                                        <div class="stat-icon">🥈</div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $team['total']['silver_tally'] }}</div>
                                            <div class="stat-label">Silver</div>
                                            <div class="stat-points">{{ number_format($team['total']['silver_weight'], 2) }} pts</div>
                                        </div>
                                    </div>
                                    <div class="modern-stat-pill bronze">
                                        <div class="stat-icon">🥉</div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $team['total']['bronze_tally'] }}</div>
                                            <div class="stat-label">Bronze</div>
                                            <div class="stat-points">{{ number_format($team['total']['bronze_weight'], 2) }} pts</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Total Score -->
                                <div class="modern-score-container">
                                    <div class="score-display">
                                        <div class="score-value">
                                            {{ number_format($team['total']['total_weight'], 2) }}
                                        </div>
                                        <div class="score-label">Total Points</div>
                                    </div>
                                    <div class="total-medals">
                                        {{ $team['total']['total_medals'] }} total medals
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
</div>

                @endforeach
            </div>
        </div>
    </section>

    <!-- Ultra-Modern Analytics Section -->
    <section class="analytics-section py-24 bg-gradient-to-b from-gray-50 via-white to-gray-50">
        <div class="container mx-auto px-4">
            <!-- Modern Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-5xl font-black text-gray-800 mb-4 tracking-tight">
                    Performance Analytics
                    <span class="block text-lg font-medium text-gray-500 mt-2">
                        Comprehensive analysis of team achievements
                    </span>
                </h2>
                <div class="flex justify-center items-center gap-2 mt-4">
                    <span class="w-8 h-1 bg-green-500 rounded-full"></span>
                    <span class="w-3 h-1 bg-green-300 rounded-full"></span>
                    <span class="w-2 h-1 bg-green-200 rounded-full"></span>
                </div>
            </div>

            <!-- Main Analytics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
                <!-- Featured Chart - Medal Distribution -->
                <div class="lg:col-span-8" data-aos="fade-right">
                    <div class="chart-card featured-chart">
                        <div class="chart-header">
                            <h3 class="chart-title">Medal Distribution</h3>
                            <div class="chart-actions">
                                <button class="chart-action-btn" onclick="toggleChartView('medalDistribution')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div id="medalDistributionChart" class="h-[400px]"></div>
                    </div>
                </div>

                <!-- Performance Radar -->
                <div class="lg:col-span-4" data-aos="fade-left">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Team Performance</h3>
                        </div>
                        <div id="performanceRadarChart" class="h-[400px]"></div>
                    </div>
                </div>
            </div>

            <!-- Secondary Analytics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Points Progress -->
                <div data-aos="fade-up">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Points Progression</h3>
                            <span class="chart-subtitle">Total points accumulated</span>
                        </div>
                        <div id="pointsProgressChart" class="h-[300px]"></div>
                    </div>
                </div>

                <!-- Medal Distribution Pie -->
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Medal Breakdown</h3>
                            <span class="chart-subtitle">Distribution by type</span>
                        </div>
                        <div id="medalPieChart" class="h-[300px]"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modern Footer -->
    <footer class="bg-gradient-to-b from-gray-900 to-green-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                
        </div>
    </section>

    <!-- Footer -->
    <footer class="hero-gradient text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-6">
                <h3 class="text-3xl font-bold mb-2">CMU PALARO 2024</h3>
                <p class="text-gray-300">Excellence Through Sports</p>
            </div>
            <div class="text-sm text-gray-300">
                <p>&copy; {{ date('Y') }} Central Mindanao University. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize AOS with custom settings
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Prepare data for charts
        const medalData = @json($medalTally);
        const teams = medalData.map(team => team.team);
        const goldCounts = medalData.map(team => team.total.gold_tally);
        const silverCounts = medalData.map(team => team.total.silver_tally);
        const bronzeCounts = medalData.map(team => team.total.bronze_tally);
        const totalPoints = medalData.map(team => team.total.total_weight);

        // 1. Medal Distribution Chart
        const medalDistChart = new ApexCharts(document.querySelector("#medalDistributionChart"), {
            series: [{
                name: 'Gold',
                data: goldCounts
            }, {
                name: 'Silver',
                data: silverCounts
            }, {
                name: 'Bronze',
                data: bronzeCounts
            }],
            chart: {
                type: 'bar',
                height: 400,
                stacked: true,
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: false,
                        zoomin: false,
                        zoomout: false,
                        pan: false,
                        reset: false
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    }
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 8,
                    columnWidth: '70%',
                    dataLabels: {
                        total: {
                            enabled: true,
                            style: {
                                fontSize: '13px',
                                fontWeight: 900
                            }
                        }
                    }
                }
            },
            colors: ['#FFD700', '#C0C0C0', '#CD7F32'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val || '';
                },
                style: {
                    fontSize: '12px'
                }
            },
            xaxis: {
                categories: teams,
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 500
                    },
                    rotate: -45,
                    trim: true
                }
            },
            yaxis: {
                title: {
                    text: 'Number of Medals'
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left'
            },
            fill: {
                opacity: 1
            }
        });
        medalDistChart.render();

        // 2. Performance Radar Chart
        const radarChart = new ApexCharts(document.querySelector("#performanceRadarChart"), {
            series: medalData.map(team => ({
                name: team.team,
                data: [
                    team.total.gold_tally,
                    team.total.silver_tally,
                    team.total.bronze_tally,
                    team.total.total_medals,
                    team.total.total_weight
                ]
            })),
            chart: {
                height: 400,
                type: 'radar',
                dropShadow: {
                    enabled: true,
                    blur: 1,
                    left: 1,
                    top: 1
                },
                toolbar: {
                    show: true,
                    tools: {
                        download: true
                    }
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
                categories: ['Gold', 'Silver', 'Bronze', 'Total Medals', 'Points']
            },
            yaxis: {
                show: false
            }
        });
        radarChart.render();

        // 3. Points Progress Chart
        const progressChart = new ApexCharts(document.querySelector("#pointsProgressChart"), {
            series: [{
                name: 'Total Points',
                data: totalPoints
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 6,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            colors: ['#00491E'],
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(2) + ' pts';
                },
                style: {
                    fontSize: '12px',
                    colors: ['#333']
                }
            },
            xaxis: {
                categories: teams,
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            }
        });
        progressChart.render();

        // 4. Medal Distribution Pie Chart
        const pieChart = new ApexCharts(document.querySelector("#medalPieChart"), {
            series: [
                medalData.reduce((sum, team) => sum + team.total.gold_tally, 0),
                medalData.reduce((sum, team) => sum + team.total.silver_tally, 0),
                medalData.reduce((sum, team) => sum + team.total.bronze_tally, 0)
            ],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Gold', 'Silver', 'Bronze'],
            colors: ['#FFD700', '#C0C0C0', '#CD7F32'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Medals',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 250
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        });
        pieChart.render();

        // 5. Ranking Timeline Chart
        const timelineChart = new ApexCharts(document.querySelector("#rankingTimelineChart"), {
            series: [{
                name: 'Points',
                data: totalPoints
            }],
            chart: {
                height: 300,
                type: 'line',
                zoom: {
                    enabled: false
                },
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1);
                }
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 6,
                strokeWidth: 0,
                hover: {
                    size: 9
                }
            },
            xaxis: {
                categories: teams,
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Points'
                }
            }
        });
        timelineChart.render();

        // Handle responsiveness
        function updateChartsResponsiveness() {
            const width = window.innerWidth;
            if (width < 768) {
                medalDistChart.updateOptions({
                    plotOptions: {
                        bar: {
                            columnWidth: '90%'
                        }
                    },
                    xaxis: {
                        labels: {
                            rotate: -90
                        }
                    }
                });
                
                radarChart.updateOptions({
                    chart: {
                        height: 300
                    }
                });
            } else {
                medalDistChart.updateOptions({
                    plotOptions: {
                        bar: {
                            columnWidth: '70%'
                        }
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        }
                    }
                });
                
                radarChart.updateOptions({
                    chart: {
                        height: 400
                    }
                });
            }
        }

        // Initial call and event listener for resize
        updateChartsResponsiveness();
        window.addEventListener('resize', updateChartsResponsiveness);
    </script>

    <script>
        // Dominant color function implementation with improved settings
        async function getDominantColor(imageUrl) {
            return new Promise((resolve) => {
                const img = new Image();
                img.crossOrigin = "Anonymous";
                
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');
                    
                    // Increase sample size for better color analysis
                    const maxDimension = 150;
                    let width = img.naturalWidth;
                    let height = img.naturalHeight;
                    
                    if (width > height && width > maxDimension) {
                        height = Math.round(height * (maxDimension / width));
                        width = maxDimension;
                    } else if (height > maxDimension) {
                        width = Math.round(width * (maxDimension / height));
                        height = maxDimension;
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    try {
                        const imageData = ctx.getImageData(0, 0, width, height).data;
                        const colorCounts = {};
                        const whiteThreshold = 240;
                        const blackThreshold = 30;
                        const saturationThreshold = 20;
                        
                        for (let i = 0; i < imageData.length; i += 4) {
                            const r = imageData[i];
                            const g = imageData[i + 1];
                            const b = imageData[i + 2];
                            
                            // Skip white and black pixels
                            if ((r > whiteThreshold && g > whiteThreshold && b > whiteThreshold) ||
                                (r < blackThreshold && g < blackThreshold && b < blackThreshold)) {
                                continue;
                            }
                            
                            // Calculate saturation
                            const max = Math.max(r, g, b);
                            const min = Math.min(r, g, b);
                            const saturation = max === 0 ? 0 : (max - min) / max * 100;
                            
                            if (saturation < saturationThreshold) continue;
                            
                            const key = `${r},${g},${b}`;
                            colorCounts[key] = (colorCounts[key] || 0) + 1;
                        }
                        
                        // Find the most common color
                        let maxCount = 0;
                        let dominantColor = { r: 0, g: 73, b: 30 }; // CMU green as fallback
                        
                        for (const key in colorCounts) {
                            if (colorCounts[key] > maxCount) {
                                maxCount = colorCounts[key];
                                const [r, g, b] = key.split(',').map(Number);
                                dominantColor = { r, g, b };
                            }
                        }
                        
                        resolve(dominantColor);
                    } catch (error) {
                        console.error('Error processing image:', error);
                        resolve({ r: 0, g: 73, b: 30 }); // CMU green as fallback
                    }
                };
                
                img.onerror = function() {
                    console.error('Error loading image:', imageUrl);
                    resolve({ r: 0, g: 73, b: 30 }); // CMU green as fallback
                };
                
                img.src = imageUrl;
            });
        }

        // Initialize team cards
        async function initializeTeamCard(imgElement, teamId) {
            try {
                const dominantColor = await getDominantColor(imgElement.src);
                const card = document.querySelector(`[data-team-id="${teamId}"]`);
                
                if (card) {
                    // Set the CSS variable for the team color
                    card.style.setProperty('--team-color-rgb', `${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}`);
                    
                    // Apply gradient background
                    const gradientBg = card.querySelector('.card-gradient-bg');
                    if (gradientBg) {
                        // Create a more dynamic gradient based on the dominant color
                        const gradient = `
                            rgba(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}) 100%
                           `;
                        
                        gradientBg.style.background = gradient;
                        
                    
                    
                    }

                    // Rest of your existing color applications...
                    const teamLabel = card.querySelector('.team-label');
                    if (teamLabel) {
                        teamLabel.style.color = `rgb(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b})`;
                    }
                    
                    const teamName = card.querySelector('.team-name');
                    if (teamName) {
                        teamName.style.background = `linear-gradient(135deg, 
                            rgb(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}),
                            rgba(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}, 0.8)
                        )`;
                        teamName.style.webkitBackgroundClip = 'text';
                        teamName.style.webkitTextFillColor = 'transparent';
                    }
                }
            } catch (error) {
                console.error('Error initializing team card:', error);
            }
        }

        // Initialize all team cards when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            const teamImages = document.querySelectorAll('[data-team-id] img');
            
            teamImages.forEach(img => {
                const teamId = img.closest('[data-team-id]').dataset.teamId;
                
                // Check if image is already loaded
                if (img.complete) {
                    initializeTeamCard(img, teamId);
                } else {
                    // If not loaded yet, wait for load event
                    img.onload = () => initializeTeamCard(img, teamId);
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.querySelector('.podium-scroll-container');
            let isDown = false;
            let startX;
            let scrollLeft;

            if (scrollContainer) {
                scrollContainer.addEventListener('mousedown', (e) => {
                    isDown = true;
                    scrollContainer.classList.add('active');
                    startX = e.pageX - scrollContainer.offsetLeft;
                    scrollLeft = scrollContainer.scrollLeft;
                });

                scrollContainer.addEventListener('mouseleave', () => {
                    isDown = false;
                    scrollContainer.classList.remove('active');
                });

                scrollContainer.addEventListener('mouseup', () => {
                    isDown = false;
                    scrollContainer.classList.remove('active');
                });

                scrollContainer.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - scrollContainer.offsetLeft;
                    const walk = (x - startX) * 2;
                    scrollContainer.scrollLeft = scrollLeft - walk;
                });

                // Snap to closest card after scroll
                scrollContainer.addEventListener('scroll', () => {
                    clearTimeout(scrollContainer.scrollTimeout);
                    scrollContainer.scrollTimeout = setTimeout(() => {
                        const cards = document.querySelectorAll('.podium-card');
                        const scrollPosition = scrollContainer.scrollLeft;
                        const cardWidth = cards[0].offsetWidth;
                        const targetIndex = Math.round(scrollPosition / cardWidth);
                        scrollContainer.scrollTo({
                            left: targetIndex * cardWidth,
                            behavior: 'smooth'
                        });
                    }, 150);
                });
            }
        });
    </script>
</body>
</html>
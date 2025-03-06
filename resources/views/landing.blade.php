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
    background: url('{{ Vite::asset('resources/images/landingPageBkg.jpg') }}') center/cover no-repeat;
    overflow: hidden;
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
                <div class="flex justify-center items-end h-[400px] gap-4">
                    <!-- Second Place -->
                    <div class="w-[250px] relative" data-aos="fade-up" data-aos-delay="200">
                        @if(isset($medalTally[1]))
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <div class="relative z-20">
                                <!-- Team Image -->
                                <div class="winner-image">
                                    <img 
                                        src="{{ $medalTally[0]['team_image'] ? Vite::asset('storage/' . $medalTally[0]['team_image']) : Vite::asset('resources/images/default.png') }}"
                                        alt="{{ $medalTally[0]['team'] }}"
                                        class="relative z-10"
                                    >
                                    <div class="winner-badge bg-gradient-to-r from-gray-300 to-gray-400">2</div>
                                </div>
                                
                                <!-- Team Info -->
                                <div class="text-center mt-4">
                                    <h3 class="font-bold text-xl mb-2 text-white">{{ $medalTally[1]['team'] }}</h3>
                                    <div class="medal-container">
                                        <div class="medal-item medal-gold-bg">
                                            <span class="medal-emoji">🥇</span>
                                            <span class="medal-count text-yellow-400">{{ $medalTally[1]['total']['gold_tally'] }}</span>
                                        </div>
                                        <div class="medal-item medal-silver-bg">
                                            <span class="medal-emoji">🥈</span>
                                            <span class="medal-count text-gray-300">{{ $medalTally[1]['total']['silver_tally'] }}</span>
                                        </div>
                                        <div class="medal-item medal-bronze-bg">
                                            <span class="medal-emoji">🥉</span>
                                            <span class="medal-count text-orange-300">{{ $medalTally[1]['total']['bronze_tally'] }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-lg font-semibold text-gray-200">
                                        {{ number_format($medalTally[1]['total']['total_weight'], 2) }} pts
                                    </div>
                                </div>
                            </div>
                            <!-- Podium -->
                            <div class="h-[150px] bg-gradient-to-t from-gray-400 to-gray-300 rounded-t-lg relative">
                                <div class="absolute bottom-0 w-full h-1/3 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- First Place -->
                    <div class="w-[300px] relative -mt-10" data-aos="fade-up" data-aos-delay="100">
                        @if(isset($medalTally[0]))
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <div class="relative z-20">
                                <!-- Trophy Animation -->
                                <div class="absolute -top-16 left-1/2 transform -translate-x-1/2">
                                    
                                </div>
                                
                                <!-- Team Image -->
                                <div class="winner-image ">
                                    <img 
                                        src="{{ $medalTally[0]['team_image'] ? Vite::asset('storage/' . $medalTally[0]['team_image']) : Vite::asset('resources/images/default.png') }}"
                                        alt="{{ $medalTally[0]['team'] }}"
                                        class="relative z-10"
                                    >
                                    <div class="winner-badge bg-gradient-to-r from-yellow-400 to-yellow-500">1</div>
                                </div>
                                
                                <!-- Team Info -->
                                <div class="text-center mt-4">
                                    <h3 class="font-bold text-2xl mb-2 text-white">{{ $medalTally[0]['team'] }}</h3>
                                    <div class="medal-container">
                                        <div class="medal-item medal-gold-bg">
                                            <span class="medal-emoji">🥇</span>
                                            <span class="medal-count text-yellow-400">{{ $medalTally[0]['total']['gold_tally'] }}</span>
                                        </div>
                                        <div class="medal-item medal-silver-bg">
                                            <span class="medal-emoji">🥈</span>
                                            <span class="medal-count text-gray-300">{{ $medalTally[0]['total']['silver_tally'] }}</span>
                                        </div>
                                        <div class="medal-item medal-bronze-bg">
                                            <span class="medal-emoji">🥉</span>
                                            <span class="medal-count text-orange-300">{{ $medalTally[0]['total']['bronze_tally'] }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-xl font-bold text-yellow-400">
                                        {{ number_format($medalTally[0]['total']['total_weight'], 2) }} pts
                                    </div>
                                </div>
                            </div>
                            <!-- Podium -->
                            <div class="h-[200px] bg-gradient-to-t from-yellow-500 to-yellow-300 rounded-t-lg relative">
                                <div class="absolute bottom-0 w-full h-1/3 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Third Place -->
                    <div class="w-[200px] relative" data-aos="fade-up" data-aos-delay="300">
                        @if(isset($medalTally[2]))
                        <div class="transform hover:scale-105 transition-all duration-300">
                            <div class="relative z-20">
                                <!-- Team Image -->
                                <div class="winner-image">
                                    <img 
                                        src="{{ $medalTally[0]['team_image'] ? Vite::asset('storage/' . $medalTally[0]['team_image']) : Vite::asset('resources/images/default.png') }}"
                                        alt="{{ $medalTally[0]['team'] }}"
                                        class="relative z-10"
                                    >
                                    <div class="winner-badge bg-gradient-to-r from-orange-400 to-orange-500">3</div>
                                </div>
                                
                                <!-- Team Info -->
                                <div class="text-center mt-4">
                                    <h3 class="font-bold text-lg mb-2 text-white">{{ $medalTally[2]['team'] }}</h3>
                                    <div class="medal-container">
                                        <div class="medal-item medal-gold-bg">
                                            <span class="medal-emoji">🥇</span>
                                            <span class="medal-count text-yellow-400">{{ $medalTally[2]['total']['gold_tally'] }}</span>
                                        </div>
                                        <div class="medal-item medal-silver-bg">
                                            <span class="medal-emoji">🥈</span>
                                            <span class="medal-count text-gray-300">{{ $medalTally[2]['total']['silver_tally'] }}</span>
                                        </div>
                                        <div class="medal-item medal-bronze-bg">
                                            <span class="medal-emoji">🥉</span>
                                            <span class="medal-count text-orange-300">{{ $medalTally[2]['total']['bronze_tally'] }}</span>
                                        </div>
                                    </div>
                                    <div class="mt-3 text-lg font-semibold text-gray-200">
                                        {{ number_format($medalTally[2]['total']['total_weight'], 2) }} pts
                                    </div>
                                </div>
                            </div>
                            <!-- Podium -->
                            <div class="h-[100px] bg-gradient-to-t from-orange-400 to-orange-300 rounded-t-lg relative">
                                <div class="absolute bottom-0 w-full h-1/3 bg-gradient-to-t from-black/20 to-transparent"></div>
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

            <div class="grid grid-cols-1 gap-8">
                @foreach($medalTally as $index => $team)
                <div class="team-card" 
                     data-aos="{{ $index % 2 == 0 ? 'fade-right' : 'fade-left' }}" 
                     data-aos-delay="{{ $index * 100 }}"
                     data-team-id="{{ $index }}">
                    <div class="modern-card-wrapper">
                        <div class="card-glass-effect"></div>
                        <div class="card-content">
                            <div class="flex flex-col lg:flex-row items-center gap-8">
                                <!-- Team Info Left Side -->
                                <div class="flex-1 text-left">
                                    <div class="team-header">
                                        <span class="team-label">Team</span>
                                        <h3 class="team-name">{{ $team['team'] }}</h3>
                                        <p class="college-name">College of {{ $team['college_name'] ?? 'Unknown' }}</p>
                                    </div>
                                    <div class="team-description">
                                        <p>{{ $team['description'] ?? 'Representing excellence in sports and academics.' }}</p>
                                        <div class="rank-indicator">
                                            <span class="rank-pill">
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
                                            src="{{ $team['team_image'] ? Vite::asset('storage/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
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

    <!-- Charts Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16" data-aos="fade-up">
                Performance Analysis
                <div class="w-20 h-1 bg-green-600 mx-auto mt-4"></div>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-right">
                    <div id="medalDistributionChart" class="h-[400px]"></div>
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-6" data-aos="fade-left">
                    <div id="performanceRadarChart" class="h-[400px]"></div>
                </div>
            </div>
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

        // Medal Count Distribution Chart
        new ApexCharts(document.querySelector("#medalDistributionChart"), {
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
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4
                },
            },
            xaxis: {
                categories: teams
            },
            colors: ['#FFD700', '#C0C0C0', '#CD7F32'],
            title: {
                text: 'Medal Count Distribution',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 600
                }
            }
        }).render();

        // Performance Chart (Radar)
        new ApexCharts(document.querySelector("#performanceRadarChart"), {
            series: medalData.map(team => ({
                name: team.team,
                data: [
                    team.total.gold_weight,
                    team.total.silver_weight,
                    team.total.bronze_weight,
                    team.total.total_weight,
                    team.total.total_medals
                ]
            })),
            chart: {
                height: 400,
                type: 'radar',
                toolbar: {
                    show: false
                }
            },
            xaxis: {
                categories: ['Gold Weight', 'Silver Weight', 'Bronze Weight', 'Total Weight', 'Total Medals']
            },
            title: {
                text: 'Team Performance Analysis',
                align: 'center',
                style: {
                    fontSize: '16px',
                    fontWeight: 600
                }
            }
        }).render();
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
                    
                    // Scale down for performance
                    const maxDimension = 100;
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
                        const grayThreshold = 30;
                        
                        for (let i = 0; i < imageData.length; i += 4) {
                            const r = imageData[i];
                            const g = imageData[i + 1];
                            const b = imageData[i + 2];
                            
                            // Skip white pixels
                            if (r > whiteThreshold && g > whiteThreshold && b > whiteThreshold) continue;
                            
                            // Skip black pixels
                            if (r < blackThreshold && g < blackThreshold && b < blackThreshold) continue;
                            
                            // Check for gray pixels
                            const max = Math.max(r, g, b);
                            const min = Math.min(r, g, b);
                            const saturation = (max === 0) ? 0 : (max - min) / max * 100;
                            
                            if (saturation < grayThreshold) continue;
                            
                            // Quantize colors
                            const quantizedR = Math.round(r / 10) * 10;
                            const quantizedG = Math.round(g / 10) * 10;
                            const quantizedB = Math.round(b / 10) * 10;
                            
                            const colorKey = `${quantizedR},${quantizedG},${quantizedB}`;
                            
                            if (colorCounts[colorKey]) {
                                colorCounts[colorKey].count++;
                                colorCounts[colorKey].saturation = Math.max(colorCounts[colorKey].saturation, saturation);
                            } else {
                                colorCounts[colorKey] = {
                                    r: quantizedR,
                                    g: quantizedG,
                                    b: quantizedB,
                                    count: 1,
                                    saturation: saturation
                                };
                            }
                        }
                        
                        // Convert to array and sort
                        let colorsArray = Object.values(colorCounts);
                        
                        // Sort by count and saturation
                        colorsArray.sort((a, b) => {
                            const aSignificant = a.count > (width * height * 0.05);
                            const bSignificant = b.count > (width * height * 0.05);
                            
                            if (aSignificant && !bSignificant) return -1;
                            if (!aSignificant && bSignificant) return 1;
                            
                            if (aSignificant && bSignificant) {
                                if (a.saturation > b.saturation) return -1;
                                if (a.saturation < b.saturation) return 1;
                            }
                            
                            return b.count - a.count;
                        });
                        
                        // Use the most dominant color or fallback to CMU green
                        const dominantColor = colorsArray.length > 0 ? colorsArray[0] : { r: 0, g: 73, b: 30 };
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
                    
                    // Apply color to team label
                    const teamLabel = card.querySelector('.team-label');
                    if (teamLabel) {
                        teamLabel.style.color = `rgb(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b})`;
                    }
                    
                    // Apply gradient to team name
                    const teamName = card.querySelector('.team-name');
                    if (teamName) {
                        teamName.style.background = `linear-gradient(135deg, 
                            rgb(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}),
                            rgba(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}, 0.8)
                        )`;
                        teamName.style.webkitBackgroundClip = 'text';
                        teamName.style.webkitTextFillColor = 'transparent';
                    }
                    
                    // Update stat pills and score container
                    const statPills = card.querySelectorAll('.modern-stat-pill');
                    statPills.forEach(pill => {
                        pill.style.borderColor = `rgba(${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}, 0.2)`;
                    });
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
</body>
</html>
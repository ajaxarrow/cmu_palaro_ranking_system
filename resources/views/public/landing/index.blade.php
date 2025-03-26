<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/palaroLogo.png') }}">
    <title>CMU PALARO {{ date('Y') }} - Medal Tally</title>
    <meta name="theme-color" content="#380505">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />


    @vite(['resources/css/landingpage.css'])
</head>
<body>

<style>
  .hero-section {
    position: relative;
    min-height: 100vh;
    background: url('{{ Vite::asset('resources/images/palaroBkg.jpg') }}') center/cover no-repeat !important;
    overflow: hidden;
    background-size: 10px;
}



.theme-slogan span{
    /* width: 800px; */
    margin: 0 auto;
    color: white !important;
    font-family: "Niconne", cursive;
    font-weight:400;
    letter-spacing: 0.5px; line-height: 1.2;
    font-size: clamp(1.8rem, 2vw, 2.2rem);
}

/* Feather styles */
.feather {
    position: absolute;
    width: 300px;
    height: 400px;
    background: url('{{ Vite::asset('resources/images/feather.png') }}') no-repeat center center/contain;
    filter:  saturate(150%);
    --parallax-speed: 0.1;
    transition: transform 0.4s cubic-bezier(0.25, 0.1, 0.25, 1);
    z-index: 1000;
    will-change: transform;
}

.feather-1 {
    top: 5%;
    left: 15%;
    --parallax-speed: 0.08;
    filter: drop-shadow(0 0 20px #ffd000) blur(0.5px) saturate(300%);
    transform: rotate(25deg) scale(1.4) !important;

    opacity: 0.85;
}


.feather-2 {
    top: 35%;
    right: 5%;
    --parallax-speed: 0.12;
    filter: blur(1px)  saturate(100%) drop-shadow(0 0 20px #ffd000);
    transform: rotate(-45deg) scale(0.8) scaleX(-1) !important; /* Flipped horizontally */
    opacity: 0.95;
}

.feather-3 {
    top: -50px;
    right: -100px;
    --parallax-speed: 0.1;
    filter: blur(4px)  saturate(300%) drop-shadow(0 0 20px #ffd000);
    transform: rotate(-100deg) scale(2) scaleX(1.2) scaleY(1.2) !important; /* Flipped both ways */
    opacity: 0.85;
}

.feather-4 {
    top: 15%;
    right: 20%;
    --parallax-speed: 0.06;
    filter: blur(1px)  saturate(150%)drop-shadow(0 0 20px #ffd000);
    transform: rotate(-15deg) scale(1.6) scaleY(-1) !important; /* Flipped vertically */
    opacity: 0.8;
}

.feather-5 {

    top: -50px;
    left: -100px;
    --parallax-speed: 0.1;
    filter: blur(2px)  saturate(150%) drop-shadow(0 0 20px #ffd000);
    transform: rotate(100deg) scale(1.5) scaleX(-1.2) scaleY(1.2) !important; /* Flipped both ways */
    opacity: 0.85;
    animation: float 3s ease-in-out infinite !important;
}

.feather-6 {
    top: 60%;
    left: 8%;
    --parallax-speed: 0.09;
    filter: blur(1.8px)  saturate(150%) drop-shadow(0 0 20px #ffd000);
    transform: rotate(35deg) scale(1.25) !important;
    opacity: 0.9;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0) rotate(var(--rotate, 0deg)) scale(var(--scale, 1)) !important;
    }
    50% {
        transform: translateY(-15px) rotate(calc(var(--rotate, 0deg) + 2deg)) scale(var(--scale, 1)) !important;
    }
}

@keyframes float-slow {
    0%, 100% {
        transform: translateY(0) rotate(var(--rotate, 0deg)) scale(var(--scale, 1)) !important;
    }
    50% {
        transform: translateY(-8px) rotate(calc(var(--rotate, 0deg) + 1deg)) scale(var(--scale, 1)) !important;
    }
}

.hero-3d-container {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    overflow: hidden;
    perspective: 1000px;
    perspective-origin: center center;
    pointer-events: none; /* Allow clicks to pass through */
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



@include('public.includes.loader')


    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>

           <!-- 3D Feathers Container -->
        <div class="hero-3d-container">
            <div class="feather feather-1" data-depth="0.1" data-aos="fade"></div>
            <div class="feather feather-2" data-depth="0.2" data-aos="fade"></div>
            <div class="feather feather-3" data-depth="0.3" data-aos="fade"></div>
            <div class="feather feather-4" data-depth="0.05" data-aos="fade"></div>
            <div class="feather feather-5" data-depth="0.15" data-aos="fade"></div>
            <div class="feather feather-6" data-depth="0.25" data-aos="fade"></div>
        </div>

        <div class="hero-content container mx-auto px-4 pt-5 pb-32">
            <!-- Header -->
            <div class="text-center mb-[50px]" data-aos="fade-down">
                <h1 class="theme-slogan text-7xl font-extrabold text-white mb-6 tracking-tight">
                    <img class="hero-logo"  src="{{ Vite::asset('resources/images/palaroLogo.png') }}" alt=""  />
                    <span  class="block mt-8 bg-clip-text ">

                    Lead Beyond Limits: Cultivate Global Mindset and Excellence Through Sports

                    </span>
                </h1>
            </div>


            <!-- Podium Section -->
            <div class="podium-section max-w-6xl mx-auto mb-20" data-aos="fade-up">

            @if(count($results) > 0)
                <!-- Desktop Podium -->
                <div class="podium-desktop">
                    <div class="flex justify-center items-end h-[400px] gap-4">
                        <!-- Second Place (1st Runner Up) -->
                        <div class="podium-item second-place" data-aos="fade-up" data-aos-delay="200">
                            @if(isset($medalTally[1]))
                            <div class="podium-content">
                            <a href="{{ route('public.standings.details', $medalTally[1]['team_id']) }}">

                                <div class="winner-image">
                                    <img src="{{ $medalTally[1]['team_image'] ? asset('storage/' . $medalTally[1]['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[1]['team'] }}"
                                         class="relative z-10">
                                    <div class="winner-badge silver">2nd</div>
                                </div>
                                <h3 class="winner-name">{{ $medalTally[1]['team'] }}</h3>
                                </a>
                                <div class="medal-stats">
                                    <div class="medal gold" title="Gold Medals">
                                        <span class="medal-emoji">🥇</span>
                                        <span class="medal-count">{{ $medalTally[1]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver" title="Silver Medals">
                                        <span class="medal-emoji">🥈</span>
                                        <span class="medal-count">{{ $medalTally[1]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze" title="Bronze Medals">
                                        <span class="medal-emoji">🥉</span>
                                        <span class="medal-count">{{ $medalTally[1]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-points">
                                    {{ number_format($medalTally[1]['total']['total_weight'], 3) }} pts
                                </div>
                            </div>
                            <div class="podium-stand silver">
                                <div class="podium-shine"></div>
                            </div>
                            @endif
                        </div>

                        <!-- Champion -->
                        <div class="podium-item champion" data-aos="fade-up">
                            @if(isset($medalTally[0]))
                            <div class="podium-content">
                                <a href="{{ route('public.standings.details', $medalTally[0]['team_id']) }}">

                                <div class="winner-image">
                                    <img src="{{ $medalTally[0]['team_image'] ? asset('storage/' . $medalTally[0]['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[0]['team'] }}"
                                         class="relative z-10">
                                    <div class="winner-badge gold">1st</div>
                                </div>
                                <h3 class="winner-name">{{ $medalTally[0]['team'] }}</h3>
                                </a>
                                <div class="medal-stats">
                                    <div class="medal gold" title="Gold Medals">
                                        <span class="medal-emoji">🥇</span>
                                        <span class="medal-count">{{ $medalTally[0]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver" title="Silver Medals">
                                        <span class="medal-emoji">🥈</span>
                                        <span class="medal-count">{{ $medalTally[0]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze" title="Bronze Medals">
                                        <span class="medal-emoji">🥉</span>
                                        <span class="medal-count">{{ $medalTally[0]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-points champion">
                                    {{ number_format($medalTally[0]['total']['total_weight'], 3) }} pts
                                </div>
                                <div class="champion-crown">👑</div>
                            </div>
                            <div class="podium-stand gold">
                                <div class="podium-shine"></div>
                            </div>
                            @endif
                        </div>

                        <!-- Third Place (2nd Runner Up) -->
                        <div class="podium-item third-place" data-aos="fade-up" data-aos-delay="400">
                            @if(isset($medalTally[2]))
                            <div class="podium-content">
                            <a href="{{ route('public.standings.details', $medalTally[2]['team_id']) }}">

                                <div class="winner-image">
                                    <img src="{{ $medalTally[2]['team_image'] ? asset('storage/' . $medalTally[2]['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[2]['team'] }}"
                                         class="relative z-10">
                                    <div class="winner-badge bronze">3rd</div>
                                </div>
                                <h3 class="winner-name">{{ $medalTally[2]['team'] }}</h3>
                                </a>
                                <div class="medal-stats">
                                    <div class="medal gold" title="Gold Medals">
                                        <span class="medal-emoji">🥇</span>
                                        <span class="medal-count">{{ $medalTally[2]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver" title="Silver Medals">
                                        <span class="medal-emoji">🥈</span>
                                        <span class="medal-count">{{ $medalTally[2]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze" title="Bronze Medals">
                                        <span class="medal-emoji">🥉</span>
                                        <span class="medal-count">{{ $medalTally[2]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-points">
                                    {{ number_format($medalTally[2]['total']['total_weight'], 3) }} pts
                                </div>
                            </div>
                            <div class="podium-stand bronze">
                                <div class="podium-shine"></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mobile Podium -->
                <div class="podium-mobile">
                    <div class="mobile-podium-grid">
                        <!-- Champion Card -->
                        @if(isset($medalTally[0]))
                        <div class="mobile-podium-card champion" data-aos="fade-up">
                            <div class="card-content">
                                <div class="position-marker">1st Runner Up</div>
                                <a href="{{ route('public.standings.details', $medalTally[0]['team_id']) }}">

                                <div class="team-image-wrapper">
                                    <img src="{{ $medalTally[0]['team_image'] ? asset('storage/' . $medalTally[0]['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[0]['team'] }}"
                                         class="team-image">
                                    <div class="champion-crown mobile">👑</div>
                                </div>
                                <h3 class="team-name">{{ $medalTally[0]['team'] }}</h3>
                                </a>
                                <div class="medal-counts">
                                    <div class="medal gold">
                                        <span>🥇 {{ $medalTally[0]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver">
                                        <span>🥈 {{ $medalTally[0]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze">
                                        <span>🥉 {{ $medalTally[0]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-score">
                                    {{ number_format($medalTally[0]['total']['total_weight'], 3) }} pts
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- First Runner Up Card -->
                        @if(isset($medalTally[1]))
                        <div class="mobile-podium-card silver" data-aos="fade-up" data-aos-delay="100">
                            <div class="card-content">
                                <div class="position-marker">2nd Runner Up</div>
                                <a href="{{ route('public.standings.details', $medalTally[1]['team_id']) }}">
                                <div class="team-image-wrapper">
                                    <img src="{{ $medalTally[1]['team_image'] ? asset('storage/' . $medalTally[1]['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[1]['team'] }}"
                                         class="team-image">
                                </div>

                                <h3 class="team-name">{{ $medalTally[1]['team'] }}</h3>
                                </a>
                                <div class="medal-counts">
                                    <div class="medal gold">
                                        <span>🥇 {{ $medalTally[1]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver">
                                        <span>🥈 {{ $medalTally[1]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze">
                                        <span>🥉 {{ $medalTally[1]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-score">
                                    {{ number_format($medalTally[1]['total']['total_weight'], 3) }} pts
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Second Runner Up Card -->
                        @if(isset($medalTally[2]))
                        <div class="mobile-podium-card bronze" data-aos="fade-up" data-aos-delay="200">
                            <div class="card-content">
                                <div class="position-marker">3rd Runner Up</div>
                                <div class="team-image-wrapper">
                                    <img src="{{ $medalTally[2]['team_image'] ? asset('storage/' . $medalTally[2]['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                         alt="{{ $medalTally[2]['team'] }}"
                                         class="team-image">
                                </div>
                                <h3 class="team-name">{{ $medalTally[2]['team'] }}</h3>
                                <div class="medal-counts">
                                    <div class="medal gold">
                                        <span>🥇 {{ $medalTally[2]['total']['gold_tally'] }}</span>
                                    </div>
                                    <div class="medal silver">
                                        <span>🥈 {{ $medalTally[2]['total']['silver_tally'] }}</span>
                                    </div>
                                    <div class="medal bronze">
                                        <span>🥉 {{ $medalTally[2]['total']['bronze_tally'] }}</span>
                                    </div>
                                </div>
                                <div class="total-score">
                                    {{ number_format($medalTally[2]['total']['total_weight'], 3) }} pts
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            @else

                            <!-- Empty State for Podium (Desktop) -->
                <div class="podium-desktop">
                    <div class="flex justify-center items-end h-[400px] gap-4">
                        <!-- Second Place Empty -->
                        <div class="podium-item second-place" data-aos="fade-up" data-aos-delay="200">
                            <div class="podium-content empty-state">
                                <div class="empty-winner-image">
                                    <div class="empty-badge silver">1st</div>
                                    <div class="empty-icon">🏆</div>
                                </div>
                                <h3 class="empty-name">Results Pending</h3>
                                <div class="medal-stats empty">
                                    <div class="medal gold" title="Gold Medals">
                                        <span class="medal-emoji">🥇</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                    <div class="medal silver" title="Silver Medals">
                                        <span class="medal-emoji">🥈</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                    <div class="medal bronze" title="Bronze Medals">
                                        <span class="medal-emoji">🥉</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                </div>
                                <div class="total-points">
                                    Coming Soon
                                </div>
                            </div>
                            <div class="podium-stand silver">
                                <div class="podium-shine"></div>
                            </div>
                        </div>

                        <!-- Champion Empty -->
                        <div class="podium-item champion" data-aos="fade-up">
                            <div class="podium-content empty-state">
                                <div class="empty-winner-image">
                                    <div class="empty-badge gold">C</div>
                                    <div class="empty-icon">👑</div>
                                </div>
                                <h3 class="empty-name">Results Pending</h3>
                                <div class="medal-stats empty">
                                    <div class="medal gold" title="Gold Medals">
                                        <span class="medal-emoji">🥇</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                    <div class="medal silver" title="Silver Medals">
                                        <span class="medal-emoji">🥈</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                    <div class="medal bronze" title="Bronze Medals">
                                        <span class="medal-emoji">🥉</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                </div>
                                <div class="total-points champion">
                                    Stay Tuned!
                                </div>
                                <!-- <div class="champion-crown empty">👑</div> -->
                            </div>
                            <div class="podium-stand gold">
                                <div class="podium-shine"></div>
                            </div>
                        </div>

                        <!-- Third Place Empty -->
                        <div class="podium-item third-place" data-aos="fade-up" data-aos-delay="400">
                            <div class="podium-content empty-state">
                                <div class="empty-winner-image">
                                    <div class="empty-badge bronze">2nd</div>
                                    <div class="empty-icon">🏆</div>
                                </div>
                                <h3 class="empty-name">Results Pending</h3>
                                <div class="medal-stats empty">
                                    <div class="medal gold" title="Gold Medals">
                                        <span class="medal-emoji">🥇</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                    <div class="medal silver" title="Silver Medals">
                                        <span class="medal-emoji">🥈</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                    <div class="medal bronze" title="Bronze Medals">
                                        <span class="medal-emoji">🥉</span>
                                        <span class="medal-count">-</span>
                                    </div>
                                </div>
                                <div class="total-points">
                                    Coming Soon
                                </div>
                            </div>
                            <div class="podium-stand bronze">
                                <div class="podium-shine"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State for Podium (Mobile) - Redesigned -->
                <div class="podium-mobile">
                    <div class="empty-state-container" data-aos="fade-up">
                        <div class="empty-state-icon">🏆</div>
                        <h2 class="empty-state-title">Results Coming Soon</h2>
                        <p class="empty-state-message">The competition is still ongoing. Check back later for final standings!</p>

                        <div class="empty-mobile-podium">
                            <div class="empty-podium-pedestal silver" data-position="1st">
                                <div class="pedestal-label">1st</div>
                                <div class="pedestal-shine"></div>
                            </div>
                            <div class="empty-podium-pedestal gold" data-position="C">
                                <div class="pedestal-label">C</div>
                                <div class="pedestal-shine"></div>
                                <!-- <div class="pedestal-crown">👑</div> -->
                            </div>
                            <div class="empty-podium-pedestal bronze" data-position="2nd">
                                <div class="pedestal-label">2nd</div>
                                <div class="pedestal-shine"></div>
                            </div>
                        </div>
                    </div>
                </div>

            @endif
            </div>



            <!-- Stats Section -->
            <div class="stats-container" >
                <div class="stats-grid">
                    <div class="stat-card" >
                        <div class="stat-icon" style="background: linear-gradient(135deg, #00491e, #02681e)">🏅</div>
                        <div class="stat-number animated-number">
                            {{ $overallTotal['total_medals'] }}
                        </div>
                        <div class="stat-label">Total Medals</div>
                    </div>

                    <div class="stat-card" >
                        <div class="stat-icon" style="background: linear-gradient(135deg, #FFD700, #FFA500)">🥇</div>
                        <div class="stat-number animated-number" style="background: linear-gradient(135deg, #FFD700, #FFA500); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $overallTotal['gold_tally'] }}
                        </div>
                        <div class="stat-label">Gold Medals</div>
                    </div>

                    <div class="stat-card" >
                        <div class="stat-icon" style="background: linear-gradient(135deg, #C0C0C0, #A9A9A9)">🥈</div>
                        <div class="stat-number animated-number" style="background: linear-gradient(135deg, #C0C0C0, #A9A9A9); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            {{ $overallTotal['silver_tally'] }}
                        </div>
                        <div class="stat-label">Silver Medals</div>
                    </div>

                    <div class="stat-card" >
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
                   class="custom-btn inline-flex items-center justify-center px-8 py-3 text-lg font-medium text-white  transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                    View Full Standings
                    <svg class="w-5 h-5 ml-2 -mr-1 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <section class="championship-carousel py-24 bg-gradient-to-b from-white to-gray-50">
            <!-- <div class="feather feather-4" style="z-index: 2" data-depth="0.3" data-aos="fade"></div> -->
            <div class="feather feather-4" style="z-index: 2; top: 100px;" data-depth="0.3" data-aos="fade"></div>

    <div class="container mx-auto px-4">
        <!-- Section Header -->
        <div class="carousel-header text-center mb-16 z-[10] relative">
            <h2 class="text-5xl mb-4 font-black tracking-tight">
                Sports Event Results
                <span class="block text-lg font-medium mt-2">
                    Celebrating excellence in sports
                </span>
            </h2>
            <div class="flex justify-center items-center gap-2 mt-4">
                <span class="w-8 h-1 bg-yellow-600 rounded-full"></span>
                <span class="w-3 h-1 bg-yellow-400 rounded-full"></span>
                <span class="w-2 h-1 bg-yellow-200 rounded-full"></span>
            </div>
        </div>

        <!-- Carousel Container -->
        @if(count($results) > 0)
        <div class="swiper sports-championship-swiper">

            <div class="swiper-wrapper">

                @foreach($flattenedEvents as $event)
                @if($event['gold_winner'] || $event['silver_winner'] || count($event['bronze_winners']) > 0)
                <div class="swiper-slide">
                    <div class="event-card">
                        <div class="event-header">
                            <span class="sport-badge">{{ $event['sport_name'] }}</span>
                            <div class="event-header-content">
                                <h3 class="event-name">{{ $event['name'] }}</h3>
                                <div class="event-description">CMU Palaro {{ date('Y') }}</div>
                            </div>
                            <div class="sport-icon">
                                @if($event['sport_icon'])
                                <img src="{{ Vite::asset('resources/images/sports/' . $event['sport_icon']) }}" alt="{{ $event['sport_name'] }}" class="sport-icon-img">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sport-icon-svg">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 2v20M2 12h20"></path>
                                </svg>
                                @endif
                            </div>
                        </div>

                        <div class="podium-container">

                            @if($event['gold_winner'] || $event['silver_winner'] || count($event['bronze_winners']) > 0)
                                @if($event['gold_winner'])
                                <div class="medal-tier gold-tier">
                                <a href="{{ route('public.standings.details', $event['gold_winner']['team_id']) }}" >

                                    <div class="medal-header">
                                        <div class="medal-icon gold">C</div>
                                        <img
                                        src="{{ $event['gold_winner']['team_image'] ? asset('storage/' . $event['gold_winner']['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                        alt="{{ $event['gold_winner']['team_name'] }}"
                                        class="h-[40px] w-[40px] mr-1"
                                        crossorigin="anonymous"
                                        loading="lazy">
                                        <div class="medal-title">{{ $event['gold_winner']['team_name'] }}</div>

                                    </div>
                                    </a>

                                </div>
                                @endif

                                @if($event['silver_winner'])

                                <div class="medal-tier silver-tier">
                                <a href="{{ route('public.standings.details', $event['silver_winner']['team_id']) }}" >

                                    <div class="medal-header">
                                        <div class="medal-icon silver">1</div>
                                        <img
                                        src="{{ $event['silver_winner']['team_image'] ? asset('storage/' . $event['silver_winner']['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                        alt="{{ $event['silver_winner']['team_name'] }}"
                                        class="h-[40px] w-[40px] mr-1"
                                        crossorigin="anonymous"
                                        loading="lazy">
                                        <div class="medal-title">{{ $event['silver_winner']['team_name'] }}</div>
                                    </div>
                                    </a>
                                </div>
                                @endif

                                @if(count($event['bronze_winners']) > 0)
                                    @foreach($event['bronze_winners'] as $index => $bronzeWinner)
                                    <div class="medal-tier bronze-tier">
                                    <a href="{{ route('public.standings.details', $bronzeWinner['team_id']) }}" >
                                    <div class="medal-header">
                                            <div class="medal-icon bronze">2</div>
                                            <img
                                                src="{{ $bronzeWinner['team_image'] ? asset('storage/' . $bronzeWinner['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                                alt="{{ $bronzeWinner['team_name'] }}"
                                                class="h-[40px] w-[40px] mr-1"
                                                crossorigin="anonymous"
                                                loading="lazy">
                                            <div class="medal-title">{{ $bronzeWinner['team_name'] }}</div>
                                        </div>

                                    </a>


                                    </div>
                                    @endforeach
                                @endif
                            @else
                                <div class="no-results">
                                    <div class="no-results-icon">🏆</div>
                                    <div>Results coming soon</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                @endforeach


            </div>



            <!-- Navigation Controls -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        @else
                <div class="no-results m-auto">
                    <div class="no-results-icon">🏆</div>
                    <div>Results coming soon</div>
                </div>

            @endif
    </div>
    </section>


<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize AOS with custom settings
  AOS.init({
    duration: 1000,
    once: true,
    offset: 100
  });


});

  // Initialize Swiper
  const sportsSwiper = new Swiper('.sports-championship-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    centeredSlides: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    effect: 'coverflow',
    coverflowEffect: {
      rotate: 5,
      stretch: 0,
      depth: 100,
      modifier: 1,
      slideShadows: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
      640: {
        slidesPerView: 2,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
    },
  });

</script>

    <section id="standings" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16">
                Team Standings
                <div class="w-20 h-1  mx-auto mt-4"></div>
            </h2>
            
            <div class="grid grid-cols-1 gap-[40px]">

            @if(count($results) > 0)
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
                            <div class="flex-1 text-left w-full">
                            <div class="team-header">
                                <span class="team-label">Team</span>
                                <a href="{{ route('public.standings.details', $team['team_id']) }}" class="team-name">
                                    {{ $team['team'] }}
                                </a>

                                <div class="d-flex flex-column gap-0">
                                    @foreach($team['colleges'] as $college)
                                        <p class="college-name">{{ $college }}</p>
                                    @endforeach
                                </div>
                            </div>
                                <div class="team-description">
                                    <p>{{ $team['description'] ?? '' }}</p>
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
                                    <a href="{{ route('public.standings.details', $team['team_id']) }}" >

                                    <img
                                        src="{{ $team['team_image'] ? asset('storage/' . $team['team_image']) : Vite::asset('resources/images/05.webp') }}"
                                        alt="{{ $team['team'] }}"
                                        class="team-image"
                                        crossorigin="anonymous"
                                        loading="lazy"
                                    >

                                    </a>
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
                                            <div class="stat-points">{{ number_format($team['total']['gold_weight'], 3) }} pts</div>
                                        </div>
                                    </div>
                                    <div class="modern-stat-pill silver">
                                        <div class="stat-icon">🥈</div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $team['total']['silver_tally'] }}</div>
                                            <div class="stat-label">Silver</div>
                                            <div class="stat-points">{{ number_format($team['total']['silver_weight'], 3) }} pts</div>
                                        </div>
                                    </div>
                                    <div class="modern-stat-pill bronze">
                                        <div class="stat-icon">🥉</div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ $team['total']['bronze_tally'] }}</div>
                                            <div class="stat-label">Bronze</div>
                                            <div class="stat-points">{{ number_format($team['total']['bronze_weight'], 3 )}} pts</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Score -->
                                <div class="modern-score-container">
                                    <div class="score-display">
                                        <div class="score-value">
                                            {{ number_format($team['total']['total_weight'], 3) }}
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
            @else
               <!-- Empty State Component -->

                    <div class="modern-empty-card mx-auto">
                        <!-- Background elements -->
                        <div class="empty-gradient-bg"></div>
                        <div class="empty-gradient-bg right"></div>
                        <div class="empty-glass-effect"></div>

                        <!-- Animated shapes -->
                        <div class="floating-shape shape1"></div>
                        <div class="floating-shape shape2"></div>
                        <div class="floating-shape shape3"></div>

                        <!-- Content -->
                        <div class="empty-content">
                            <div class="empty-icon-container">
                                <div class="empty-icon-glow"></div>
                                <div class="empty-icon">🏆</div>
                            </div>
                            <h3 class="empty-title">No Posted Results Found</h3>
                            <p class="empty-description">There are currently no Posted Results. Check back later for updates.</p>
                            <a href="/" class="empty-action-btn">Refresh Results</a>
                        </div>
                    </div>

            @endif
            </div>
        </div>
    </section>

    <!-- Ultra-Modern Analytics Section -->
    <section class="analytics-section py-24 bg-gradient-to-b from-gray-50 via-white to-gray-50">
        <div class="container mx-auto px-4">
            <!-- Modern Header -->
            <div class="text-center mb-20">
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
                <div class="lg:col-span-8" >
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
                <div class="lg:col-span-4" >
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
                <div >
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Points Progression</h3>
                            <span class="chart-subtitle">Total points accumulated</span>
                        </div>
                        <div id="pointsProgressChart" class="h-[300px]"></div>
                    </div>
                </div>

                <!-- Medal Distribution Pie -->
                <div >
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



    <!-- Footer -->
    <footer class="hero-gradient text-white py-12">
        <div class="mx-auto px-4 text-center">
            <div class="mb-6">
            <img class="w-[200px] mx-auto mb-6"  src="{{ Vite::asset('resources/images/palaroLogo.png') }}" alt=""  />
                <p class="text-gray-300">  Lead Beyond Limits: Cultivate Global Mindset and Excellence Through Sports</p>
            </div>
            <div class="text-sm text-gray-300">
                <p>&copy; {{ date('Y') }} CMU Palaro 2025 All rights reserved.</p>
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

                        // Find the most common darker color
                        let maxCount = 0;
                        let dominantColor = { r: 0, g: 73, b: 30 }; // CMU green as fallback

                        for (const key in colorCounts) {
                            const [r, g, b] = key.split(',').map(Number);
                            if (r + g + b < 200 && colorCounts[key] > maxCount) {
                                maxCount = colorCounts[key];
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

    <script src="{{ Vite::asset('resources/js/pages/loader.js') }}"></script>

</body>
</html>

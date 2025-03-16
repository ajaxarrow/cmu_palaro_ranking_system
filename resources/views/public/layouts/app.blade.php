<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CMU PALARO Medal Tracker')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts - Modern Sans & Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons (modern alternative to Feather) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- AOS Animations -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        cmu: {
                            // Modern green palette
                            50: '#e6f5ee',
                            100: '#c2e5d3',
                            200: '#9ad4b6',
                            300: '#72c398',
                            400: '#4ab57d',
                            500: '#00a65e', // primary green
                            600: '#00964f',
                            700: '#00863e',
                            800: '#00762e',
                            900: '#00551f',
                        },
                        medal: { 
                            gold: '#FFD700', 
                            silver: '#E0E0E0', 
                            bronze: '#CD7F32' 
                        },
                        // Modern neutral palette for backgrounds and text
                        neutral: {
                            50: '#fafafa',
                            100: '#f5f5f5',
                            200: '#e5e5e5',
                            300: '#d4d4d4',
                            400: '#a3a3a3',
                            500: '#737373',
                            600: '#525252',
                            700: '#404040',
                            800: '#262626',
                            900: '#171717',
                        }
                    },
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    },
                    boxShadow: {
                        'soft': '0 2px 15px rgba(0, 0, 0, 0.04)',
                        'medal': '0 4px 20px rgba(0, 0, 0, 0.1)',
                        'card': '0 10px 30px rgba(0, 0, 0, 0.04), 0 0 8px rgba(0, 0, 0, 0.06)',
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.5rem',
                        '3xl': '2rem',
                    }
                }
            }
        }
    </script>
    
    <style>
        /* Core Styles Based on Landing Page */
body { 
    font-family: 'Plus Jakarta Sans', sans-serif; 
    scroll-behavior: smooth;
}

.display-font {
    font-family: 'Outfit', sans-serif;
}

/* Modern Glass Effect */
.glass {
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Modern Gradients */
.gold-gradient { 
    background: linear-gradient(135deg, #FFD700 10%, #FFA500 90%);
}

.silver-gradient { 
    background: linear-gradient(135deg, #E0E0E0 10%, #A9A9A9 90%);
}

.bronze-gradient { 
    background: linear-gradient(135deg, #CD7F32 10%, #A0522D 90%);
}

/* Card Hover Effect */
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 0 8px rgba(0, 0, 0, 0.06);
}

/* Medal Shine Effect */
.medal-shine {
    position: relative;
    overflow: hidden;
}

.medal-shine::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to bottom right,
        rgba(255,255,255,0) 0%,
        rgba(255,255,255,0.1) 50%,
        rgba(255,255,255,0) 100%
    );
    transform: rotate(30deg);
    animation: shine 3s infinite linear;
}

@keyframes shine {
    0% { transform: translateX(-100%) rotate(30deg); }
    100% { transform: translateX(100%) rotate(30deg); }
}

/* Chart-specific styles */
.apexcharts-tooltip {
    background: rgba(255, 255, 255, 0.98) !important;
    border: none !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 0 8px rgba(0, 0, 0, 0.06) !important;
    border-radius: 1rem !important;
    padding: 0.75rem 1rem !important;
}

.apexcharts-legend-text {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-weight: 500 !important;
}

.apexcharts-title-text {
    font-family: 'Outfit', sans-serif !important;
    font-weight: 600 !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chart-card {
        padding: 1rem;
    }
    
    .analytics-section {
        padding: 2rem 1rem;
    }
}

/* Stats Grid Styles */
.stats-container {
    padding: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.stat-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 0 8px rgba(0, 0, 0, 0.06);
}

.stat-number {
    font-family: 'Outfit', sans-serif;
    font-size: 2.5rem;
    font-weight: 700;
    color: #262626;
}

.stat-label {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.875rem;
    color: #737373;
}

/* Analytics Section Styles */
.analytics-section {
    position: relative;
    overflow: hidden;
    padding: 3rem 2rem;
}

.analytics-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 400px;
    background: linear-gradient(180deg, rgba(0,166,94,0.05) 0%, rgba(255,255,255,0) 100%);
    pointer-events: none;
}

.chart-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 0 8px rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.chart-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.02);
}

/* Podium Section Styles */
.podium-section {
    padding: 2rem;
}

.podium-section .flex {
    display: flex;
    align-items: flex-end;
    justify-content: center;
    height: 400px;
}

.winner-image {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Mobile Podium Design */
.podium-mobile {
    display: none;
}

@media (max-width: 768px) {
    .podium-desktop {
        display: none;
    }

    .podium-mobile {
        display: block;
        padding: 1rem;
    }

    .podium-card {
        background: white;
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 0 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }

    .podium-card.champion {
        background: linear-gradient(135deg, rgba(255, 215, 0, 0.05), rgba(255, 165, 0, 0.05));
        border: 2px solid rgba(255, 215, 0, 0.2);
        transform: scale(1.05);
    }

    .podium-card.second-place {
        background: linear-gradient(135deg, rgba(192, 192, 192, 0.05), rgba(169, 169, 169, 0.05));
        border: 1px solid rgba(192, 192, 192, 0.2);
    }

    .podium-card.third-place {
        background: linear-gradient(135deg, rgba(205, 127, 50, 0.05), rgba(139, 69, 19, 0.05));
        border: 1px solid rgba(205, 127, 50, 0.2);
    }

    .podium-card:hover {
        transform: translateY(-5px);
    }

    .podium-card.champion:hover {
        transform: scale(1.08);
    }

    .mobile-winner-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        margin: 0 auto;
        border: 4px solid white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-card {
        padding: 1rem;
    }

    .stat-number {
        font-size: 2rem;
    }

    .stat-label {
        font-size: 0.75rem;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .podium-section .flex {
        height: 300px;
    }

    .w-[250px] {
        width: 30%;
    }

    .w-[300px] {
        width: 40%;
    }

    .w-[200px] {
        width: 30%;
    }
}

/* Medal Container Styles */
.medal-container {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.medal-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    background: white;
    border-radius: 0.5rem;
    padding: 0.5rem;
    min-width: 60px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.medal-emoji {
    font-size: 1.5rem;
}

.medal-count {
    font-weight: 600;
    font-family: 'Outfit', sans-serif;
}

/* Custom Scrollbar - Consistent with landing page */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #00a65e;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #00863e;
}
    </style>
    
    @yield('styles')
</head>
<body class="bg-neutral-50 text-neutral-800">
    <!-- Modern Navbar with Glass Effect -->
    <nav class="bg-white/10 backdrop-blur-md fixed w-[100%] top-0 z-50 shadow-soft">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-left items-center">
                <a href="{{ route('public.landing.index') }}" class="flex items-center space-x-3">
                    <span class="text-2xl font-bold text-cmu-500 display-font">CMU PALARO</span>
                    <span class="px-3 py-1 bg-cmu-100 text-cmu-800 rounded-full text-xs font-medium">{{ date('Y') }}</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
        
   
    </main>

    <!-- Modern Footer -->
    <footer class="bg-neutral-900 text-white pt-16 pb-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4 display-font text-cmu-300">CMU PALARO</h3>
                    <p class="text-neutral-400 mb-6 max-w-md">The official medal tracking system for Central Mindanao University's annual sports festival, celebrating athletic excellence and team spirit.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="h-10 w-10 rounded-full bg-neutral-800 hover:bg-cmu-500 flex items-center justify-center transition-colors">
                            <i class="ph ph-facebook-logo text-white"></i>
                        </a>
                        <a href="#" class="h-10 w-10 rounded-full bg-neutral-800 hover:bg-cmu-500 flex items-center justify-center transition-colors">
                            <i class="ph ph-twitter-logo text-white"></i>
                        </a>
                        <a href="#" class="h-10 w-10 rounded-full bg-neutral-800 hover:bg-cmu-500 flex items-center justify-center transition-colors">
                            <i class="ph ph-instagram-logo text-white"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('public.standings') }}" class="text-neutral-400 hover:text-white transition-colors">Standings</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition-colors">Events</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition-colors">Teams</a></li>
                        <li><a href="#" class="text-neutral-400 hover:text-white transition-colors">About</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white">Contact</h3>
                    <ul class="space-y-3 text-neutral-400">
                        <li class="flex items-center">
                            <i class="ph ph-map-pin mr-3 text-cmu-400"></i>
                            Central Mindanao University, Musuan, Bukidnon
                        </li>
                        <li class="flex items-center">
                            <i class="ph ph-envelope mr-3 text-cmu-400"></i>
                            palaro@cmu.edu.ph
                        </li>
                        <li class="flex items-center">
                            <i class="ph ph-phone mr-3 text-cmu-400"></i>
                            +63 (88) 123-4567
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-neutral-800 mt-12 pt-8 text-center text-neutral-500">
                <p>&copy; {{ date('Y') }} CMU PALARO Medal Tracker. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        // Initialize AOS animations
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });
            
            // Mobile menu toggle with modern animation
            const menuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('hidden');
                
                // Toggle icon between list and x
                const icon = menuButton.querySelector('i');
                if (icon.classList.contains('ph-list')) {
                    icon.classList.remove('ph-list');
                    icon.classList.add('ph-x');
                } else {
                    icon.classList.remove('ph-x');
                    icon.classList.add('ph-list');
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
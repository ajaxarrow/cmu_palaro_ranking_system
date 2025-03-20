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

    <!-- FONT -->
    <link href="https://api.fontshare.com/v2/css?f[]=rosaline@400&display=swap" rel="stylesheet">

    
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

    </style>
    
    @yield('styles')
</head>
<body class="bg-neutral-50 text-neutral-800">
    <!-- Modern Navbar with Glass Effect -->
    <nav class="bg-white/10 backdrop-blur-md mt-2 fixed w-[200px] top-0 left-[50%] -translate-x-1/2 z-50 shadow-soft rounded-2xl overflow-hidden">
        <div class="container mx-auto px-6 py-4 flex justify-center items-center shadow-[0_0_20px_5px_rgba(0,0,0,0.1)]">
            <a href="{{ route('public.landing.index') }}" class="flex items-center space-x-3 transition-all duration-300 hover:scale-110 hover:filter hover:brightness-110 hover:drop-shadow-lg">
                <img src="{{ Vite::asset('resources/images/palaroCleanLogo.png') }}" alt="CMU Logo" class="w-auto h-12">
            </a>
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
                    <!-- <div class="flex space-x-4">
                        <a href="#" class="h-10 w-10 rounded-full bg-neutral-800 hover:bg-cmu-500 flex items-center justify-center transition-colors">
                            <i class="ph ph-facebook-logo text-white"></i>
                        </a>
                        <a href="#" class="h-10 w-10 rounded-full bg-neutral-800 hover:bg-cmu-500 flex items-center justify-center transition-colors">
                            <i class="ph ph-twitter-logo text-white"></i>
                        </a>
                        <a href="#" class="h-10 w-10 rounded-full bg-neutral-800 hover:bg-cmu-500 flex items-center justify-center transition-colors">
                            <i class="ph ph-instagram-logo text-white"></i>
                        </a>
                    </div> -->
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4 text-white"></h3>
                    <ul>
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
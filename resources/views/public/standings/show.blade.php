    {{-- resources/views/public/standings/show.blade.php --}}
    @extends('public.layouts.app')

    @section('title', isset($team->name) ? $team->name . ' - Medal Details' : 'Team Details')

    @section('styles')
    <style>
        :root {
            --team-color-rgb: 0, 73, 30; /* Default color, will be updated via JS */
            --team-color-rgb-dark: 0, 53, 20; /* Darker version for accessibility */
            --team-color: rgb(var(--team-color-rgb));
            --team-color-dark: rgb(var(--team-color-rgb-dark));
            --team-color-light: rgba(var(--team-color-rgb), 0.1);
            --team-color-medium: rgba(var(--team-color-rgb), 0.5);
        }
        
        /* Modern medal design with subtle shadow and gradient */
        .medal-circle { 
            @apply flex items-center justify-center rounded-full shadow-md text-center font-bold;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            backdrop-filter: blur(4px);
        }
        .medal-circle.xs { @apply w-8 h-8 text-xs; }
        .medal-circle.sm { @apply w-10 h-10 text-sm; }
        .medal-circle.md { @apply w-12 h-12 text-base; }
        .medal-circle.lg { @apply w-16 h-16 text-2xl; }
        .medal-circle.xl { @apply w-20 h-20 text-3xl; }
        
        .medal-circle:hover { 
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 7px 14px rgba(0,0,0,0.2);
        }
        
        .gold { background: linear-gradient(135deg, #FEF9C3, #FCD34D, #EAB308); color: #78350F; }
        .silver { background: linear-gradient(135deg, #F9FAFB, #E5E7EB, #9CA3AF); color: #1F2937; }
        .bronze { background: linear-gradient(135deg, #FEF3C7, #D97706, #92400E); color: #FFFBEB; }
        
        /* Modern progress bars with rounded caps */
        .progress-container { 
            @apply h-3 rounded-full bg-gray-100 overflow-hidden shadow-inner; 
        }
        .progress-bar { 
            @apply h-full rounded-full transition-all duration-700;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Dynamic team header with texture overlay */
        .team-header-bg {
            background: linear-gradient(135deg, 
                rgba(var(--team-color-rgb-dark), 0.95), 
                rgba(var(--team-color-rgb), 0.8)),
                url('/images/texture-pattern.svg');
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .team-header-bg::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.15), transparent 70%);
        }
        
        /* Modern card hover effects */
        .card-hover { 
            @apply transition-all duration-300;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover { 
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1), 0 5px 15px rgba(0,0,0,0.07);
        }
        
        /* Glassmorphism for medal count cards */
        .medal-count-card {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }
        
        /* Sport card with custom border */
        .sport-card {
            border-left: 5px solid var(--team-color);
            transition: all 0.3s ease;
        }
        
        .sport-card:hover {
            border-left-width: 8px;
        }
        
        /* Table row hover effect */
        .table-row-hover { 
            @apply transition-all duration-200; 
        }
        .table-row-hover:hover { 
            @apply bg-gray-50;
            transform: translateX(3px);
        }
        
        /* Modern wave divider */
        .wave-divider {
            height: 120px;
            overflow: hidden;
            position: relative;
            margin-top: -20px;
        }
        
        /* Modern medal labels */
        .medal-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.75rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        /* Stats styling */
        .stat-value {
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.025em;
        }
        
        .stat-label {
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            opacity: 0.7;
        }
        
        /* Animated buttons */
        .btn-animated {
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .btn-animated::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.5s;
        }
        
        .btn-animated:hover::after {
            left: 100%;
        }
        
        /* Responsive font size utility classes */
        .text-responsive {
            font-size: clamp(1.5rem, 5vw, 3.5rem);
        }
        
        /* Modern scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--team-color-medium);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: var(--team-color);
        }
        
        /* Flag container with reflection effect */
        .flag-container {
            position: relative;
        }
        
        .flag-container::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 10%;
            right: 10%;
            height: 40%;
            background: linear-gradient(to bottom, var(--team-color), transparent);
            filter: blur(15px);
            opacity: 0.3;
            transform: scaleY(-1);
            z-index: -1;
        }
    </style>
    @endsection

    @section('content')
    <!-- Check for error message -->
    @if(isset($error))
        <div class="container mx-auto px-4 py-8">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-6 rounded-xl mb-6 shadow-md" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="font-medium">Error</p>
                </div>
                <p class="mt-2">{{ $error }}</p>
            </div>
            <a href="{{ route('public.standings') }}" class="inline-flex items-center px-5 py-3 bg-red-600 text-white rounded-xl shadow-lg hover:bg-red-700 transition-colors btn-animated">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Back to Standings
            </a>
        </div>
    @else
        <!-- Team Hero Section with Dynamic Background -->
        <div class="team-header-bg text-white relative">
            <div class="container mx-auto px-6 py-20 relative z-10">
                <a href="{{ route('public.standings') }}" class="inline-flex items-center px-5 py-2.5 bg-white/20 backdrop-blur-md text-white rounded-xl shadow-md hover:bg-white/30 transition-all mb-8 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transition-transform group-hover:-translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Back to Standings
                </a>
                
                <div class="flex flex-col md:flex-row items-center md:items-start gap-8" data-aos="fade-up">
                    @if($team->image)
                        <div class="flag-container w-50 h-50 md:w-48 md:h-48 border-none overflow-visible ">
                        <img
                                            src="{{ $team->image ? Vite::asset('storage/app/public/' . $team->image) : Vite::asset('resources/images/05.webp') }}"
                                            alt="{{ $team['team'] }}"
                                            class="w-full h-full object-cover" id="teamFlag"
                                            crossorigin="anonymous"
                                            loading="lazy"
                                        >
                        </div>
                    @endif
                    <div>
                        <h1 class="text-responsive font-extrabold tracking-tight text-center md:text-left">{{ $team->name }}</h1>
                        @if($team->code)
                            <p class="text-xl md:text-2xl mt-2 opacity-90 font-light text-center md:text-left">{{ $team->code }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="wave-divider bg-white">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
                <path fill="var(--team-color)" fill-opacity="1" d="M0,160L48,149.3C96,139,192,117,288,133.3C384,149,480,203,576,202.7C672,203,768,149,864,128C960,107,1056,117,1152,138.7C1248,160,1344,192,1392,208L1440,224L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
            </svg>
        </div>

        <div class="container mx-auto px-6 py-12">
            <!-- Medal Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 -mt-24 mb-16" data-aos="fade-up" data-aos-delay="100">
                <!-- Gold Medal Card -->
                <div class="medal-count-card rounded-2xl shadow-lg overflow-hidden card-hover">
                    <div class="p-6 text-center">
                        <div class="medal-circle xl gold mx-auto mb-4 flex items-center justify-center" data-aos="zoom-in" data-aos-delay="200">G</div>
                        <div class="stat-value mb-2" data-medal-count="{{ $overallTotal['gold_tally'] }}">0</div>
                        <div class="stat-label">Gold Medals</div>
                    </div>
                </div>
                
                <!-- Silver Medal Card -->
                <div class="medal-count-card rounded-2xl shadow-lg overflow-hidden card-hover">
                    <div class="p-6 text-center">
                        <div class="medal-circle xl silver mx-auto mb-4 flex items-center justify-center" data-aos="zoom-in" data-aos-delay="300">S</div>
                        <div class="stat-value mb-2" data-medal-count="{{ $overallTotal['silver_tally'] }}">0</div>
                        <div class="stat-label">Silver Medals</div>
                    </div>
                </div>
                
                <!-- Bronze Medal Card -->
                <div class="medal-count-card rounded-2xl shadow-lg overflow-hidden card-hover">
                    <div class="p-6 text-center">
                        <div class="medal-circle xl bronze mx-auto mb-4 flex items-center justify-center" data-aos="zoom-in" data-aos-delay="400">B</div>
                        <div class="stat-value mb-2" data-medal-count="{{ $overallTotal['bronze_tally'] }}">0</div>
                        <div class="stat-label">Bronze Medals</div>
                    </div>
                </div>
                
                <!-- Total Medals Card -->
                <div class="rounded-2xl shadow-lg overflow-hidden card-hover text-white" style="background: linear-gradient(135deg, var(--team-color-dark), var(--team-color));">
                    <div class="p-6 text-center">
                        <div class="medal-circle xl bg-white mx-auto mb-4 flex items-center justify-center" data-aos="zoom-in" data-aos-delay="500" style="color: var(--team-color)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <div class="stat-value mb-2" data-medal-count="{{ $overallTotal['total_medals'] }}">0</div>
                        <div class="stat-label">Total Medals</div>
                    </div>
                </div>
            </div>

            <!-- Medal Distribution -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-16 card-hover" data-aos="fade-up" style="border-top: 4px solid var(--team-color)">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-2xl font-bold flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" style="color: var(--team-color)" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                        Medal Distribution
                    </h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center">
                                        <div class="medal-circle xs gold mr-3 flex items-center justify-center">G</div>
                                        <span class="font-medium">Gold</span>
                                    </div>
                                    <span class="text-sm font-semibold px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full">
                                        {{ $overallTotal['gold_tally'] }} 
                                        ({{ $overallTotal['total_medals'] > 0 ? round(($overallTotal['gold_tally'] / $overallTotal['total_medals']) * 100) : 0 }}%)
                                    </span>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar bg-yellow-400 width-animate" data-width="{{ $overallTotal['total_medals'] > 0 ? ($overallTotal['gold_tally'] / $overallTotal['total_medals']) * 100 : 0 }}%" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="mb-8">
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center">
                                        <div class="medal-circle xs silver mr-3 flex items-center justify-center">S</div>
                                        <span class="font-medium">Silver</span>
                                    </div>
                                    <span class="text-sm font-semibold px-3 py-1 bg-gray-100 text-gray-800 rounded-full">
                                        {{ $overallTotal['silver_tally'] }} 
                                        ({{ $overallTotal['total_medals'] > 0 ? round(($overallTotal['silver_tally'] / $overallTotal['total_medals']) * 100) : 0 }}%)
                                    </span>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar bg-gray-400 width-animate" data-width="{{ $overallTotal['total_medals'] > 0 ? ($overallTotal['silver_tally'] / $overallTotal['total_medals']) * 100 : 0 }}%" style="width: 0%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <div class="flex items-center">
                                        <div class="medal-circle xs bronze mr-3 flex items-center justify-center">B</div>
                                        <span class="font-medium">Bronze</span>
                                    </div>
                                    <span class="text-sm font-semibold px-3 py-1 bg-yellow-50 text-yellow-800 rounded-full">
                                        {{ $overallTotal['bronze_tally'] }} 
                                        ({{ $overallTotal['total_medals'] > 0 ? round(($overallTotal['bronze_tally'] / $overallTotal['total_medals']) * 100) : 0 }}%)
                                    </span>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar bg-yellow-700 width-animate" data-width="{{ $overallTotal['total_medals'] > 0 ? ($overallTotal['bronze_tally'] / $overallTotal['total_medals']) * 100 : 0 }}%" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="bg-gray-50 rounded-2xl p-6 h-full flex flex-col justify-between shadow-inner">
                                <h3 class="text-lg font-semibold mb-4" style="color: var(--team-color)">Medal Stats</h3>
                                
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="font-medium">Total Medal Weight:</span>
                                        <span class="font-bold text-lg" data-count="{{ number_format($overallTotal['total_weight'], 1) }}">0.0</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="font-medium">Gold Weight:</span>
                                        <span class="font-bold text-lg text-yellow-600" data-count="{{ number_format($overallTotal['gold_weight'], 1) }}">0.0</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                        <span class="font-medium">Silver Weight:</span>
                                        <span class="font-bold text-lg text-gray-600" data-count="{{ number_format($overallTotal['silver_weight'], 1) }}">0.0</span>
                                    </div>
                                    <div class="flex justify-between items-center py-3">
                                        <span class="font-medium">Bronze Weight:</span>
                                        <span class="font-bold text-lg text-yellow-800" data-count="{{ number_format($overallTotal['bronze_weight'], 1) }}">0.0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sports Breakdown Section Header -->
            <div class="flex items-center justify-between mb-8" data-aos="fade-up">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3" style="color: var(--team-color)" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd" />
                    </svg>
                    Medal Breakdown by Sport
                </h2>
            </div>

            <!-- Sports Cards -->
            @forelse($medalTally as $sportName => $sportData)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 sport-card transform transition-all hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    <div class="p-5 flex flex-col md:flex-row md:items-center justify-between" style="background-color: var(--team-color-light)">
                        <div class="flex items-center mb-4 md:mb-0">
                            <div class="rounded-full p-3 mr-4" style="background-color: var(--team-color-light)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="color: var(--team-color)" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold" style="color: var(--team-color)">{{ $sportName }}</h3>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <div class="medal-circle sm gold text-xs mr-2 flex items-center justify-center">{{ $sportData['total']['gold_tally'] }}</div>
                                <span class="text-sm font-medium">Gold</span>
                            </div>
                            <div class="flex items-center">
                                <div class="medal-circle sm silver text-xs mr-2 flex items-center justify-center">{{ $sportData['total']['silver_tally'] }}</div>
                                <span class="text-sm font-medium">Silver</span>
                            </div>
                            <div class="flex items-center">
                                <div class="medal-circle sm bronze text-xs mr-2 flex items-center justify-center">{{ $sportData['total']['bronze_tally'] }}</div>
                                <span class="text-sm font-medium">Bronze</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Gold</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Silver</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bronze</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($sportData['events'] as $event)
                                        <tr class="table-row-hover">
                                            <td class="px-4 py-4 text-sm text-gray-900 font-medium">{{ $event['event_category'] }}</td>
                                            <td class="px-4 py-4 text-sm text-center">
                                                @if($event['gold_tally'] > 0)
                                                    <span class="medal-label bg-yellow-100 text-yellow-800">
                                                        {{ $event['gold_tally'] }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-center">
                                                @if($event['silver_tally'] > 0)
                                                    <span class="medal-label bg-gray-100 text-gray-800">
                                                        {{ $event['silver_tally'] }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                    @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-center">
                                                @if($event['bronze_tally'] > 0)
                                                    <span class="medal-label bg-yellow-50 text-yellow-800">
                                                        {{ $event['bronze_tally'] }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4 text-sm text-center font-semibold">
                                                {{ $event['gold_tally'] + $event['silver_tally'] + $event['bronze_tally'] }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-center font-semibold" style="color: var(--team-color)">
                                                {{ number_format($event['total_weight'], 1) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8 p-8 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Medal Data Available</h3>
                    <p class="text-gray-500">This team hasn't won any medals yet. Check back later for updates!</p>
                </div>
            @endforelse

            <!-- Back to Standings Button -->
            <div class="mt-12 mb-6 text-center">
                <a href="{{ route('public.standings') }}" class="inline-flex items-center px-6 py-3 text-white rounded-xl shadow-lg transition-all btn-animated" style="background-color: var(--team-color)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L4.414 9H17a1 1 0 110 2H4.414l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back to Medal Standings
                </a>
            </div>
        </div>
    @endif
    @endsection

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Extract dominant color from flag image
            const teamFlag = document.getElementById('teamFlag');
            if (teamFlag) {
                // Create virtual canvas to analyze image
                setTimeout(function() {
                    try {
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.width = teamFlag.width;
                        canvas.height = teamFlag.height;
                        context.drawImage(teamFlag, 0, 0, teamFlag.width, teamFlag.height);
                        
                        // Sample colors from the image
                        const imageData = context.getImageData(0, 0, canvas.width, canvas.height).data;
                        
                        // Simple dominant color detection
                        let colorCounts = {};
                        let maxCount = 0;
                        let dominantColor = { r: 0, g: 73, b: 30 }; // Default color
                        
                        // Sample every 10th pixel to improve performance
                        for (let i = 0; i < imageData.length; i += 40) {
                            const r = imageData[i];
                            const g = imageData[i + 1];
                            const b = imageData[i + 2];
                            const a = imageData[i + 3];
                            
                            // Skip transparent pixels
                            if (a < 125) continue;
                            
                            // Skip very light or very dark colors
                            const brightness = (r + g + b) / 3;
                            if (brightness < 30 || brightness > 220) continue;
                            
                            // Create a key for this color (rounded to reduce variations)
                            const colorKey = Math.round(r/10)*10 + ',' + Math.round(g/10)*10 + ',' + Math.round(b/10)*10;
                            
                            if (!colorCounts[colorKey]) {
                                colorCounts[colorKey] = 0;
                            }
                            
                            colorCounts[colorKey]++;
                            
                            if (colorCounts[colorKey] > maxCount) {
                                maxCount = colorCounts[colorKey];
                                dominantColor = { 
                                    r: Math.round(r/10)*10, 
                                    g: Math.round(g/10)*10, 
                                    b: Math.round(b/10)*10 
                                };
                            }
                        }
                        
                        // Calculate a darker version for contrast
                        const darkerColor = {
                            r: Math.max(0, dominantColor.r - 20),
                            g: Math.max(0, dominantColor.g - 20),
                            b: Math.max(0, dominantColor.b - 20)
                        };
                        
                        // Set the CSS variables
                        document.documentElement.style.setProperty('--team-color-rgb', `${dominantColor.r}, ${dominantColor.g}, ${dominantColor.b}`);
                        document.documentElement.style.setProperty('--team-color-rgb-dark', `${darkerColor.r}, ${darkerColor.g}, ${darkerColor.b}`);
                    } catch (e) {
                        console.error("Error processing flag image:", e);
                    }
                }, 500);
            }
            
            // Animate progress bars
            setTimeout(function() {
                document.querySelectorAll('.width-animate').forEach(function(bar) {
                    bar.style.width = bar.dataset.width;
                });
            }, 300);
            
            // Animate counters
            document.querySelectorAll('[data-medal-count]').forEach(function(element) {
                animateCounter(element, parseInt(element.dataset.medalCount));
            });
            
            document.querySelectorAll('[data-count]').forEach(function(element) {
                animateCounter(element, parseFloat(element.dataset.count), 1);
            });
            
            function animateCounter(element, target, decimals = 0) {
                let start = 0;
                const duration = 1500;
                const startTime = performance.now();
                
                function updateCounter(currentTime) {
                    const elapsedTime = currentTime - startTime;
                    const progress = Math.min(elapsedTime / duration, 1);
                    
                    // Use an easing function for more natural animation
                    const easedProgress = 1 - Math.pow(1 - progress, 3); // Cubic ease-out
                    
                    const value = start + (target - start) * easedProgress;
                    
                    if (decimals > 0) {
                        element.textContent = value.toFixed(decimals);
                    } else {
                        element.textContent = Math.floor(value);
                    }
                    
                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    }
                }
                
                requestAnimationFrame(updateCounter);
            }
        });
    </script>
    @endsection
    {{-- resources/views/public/standings/show.blade.php --}}
@extends('public.layouts.app')

@section('title', isset($team->name) ? $team->name . ' - Medal Details' : 'Team Details')

@section('styles')
    @vite(['resources/css/teamdetails.css'])
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
       
    </div>
@else
<!-- Team Hero Section with Dynamic Background -->
{{-- Modern Sporty Hero Section for CMU PALARO Standings Page --}}
<!-- Modern Sporty Hero Section -->
<section class="team-hero-section pt-[10vh] h-[100vh]">
    <!-- Background Elements -->
    <img
        src="{{ $team->image ? Vite::asset('storage/app/public/' . $team->image) : Vite::asset('resources/images/05.webp') }}"
        alt="{{ $team['team'] }}"
        class="hero-background-img w-full h-full object-cover absolute"
        crossorigin="anonymous"
        loading="lazy"
    >
    <div class="hero-pattern"></div>
    <div class="hero-lines"></div>
    <div class="hero-diagonal"></div>
    
    <div class="hero-content">
        <div class="container mx-auto px-4 py-8">
           
            
            <!-- Team Info -->
            <div class="text-center mb-12" data-aos="fade-up">
                @if($team->image)
                    <div class="team-image-wrapper">
                        <img
                            src="{{ $team->image ? Vite::asset('storage/app/public/' . $team->image) : Vite::asset('resources/images/05.webp') }}"
                            alt="{{ $team['team'] }}"
                            class="w-full h-full object-cover" id="teamFlag"
                            crossorigin="anonymous"
                            loading="lazy"
                        >
                    </div>
                @endif
                <h1 class="team-title">{{ $team->name }}</h1>
                @if($team->code)
                    <p class="team-subtitle">{{ $team->code }}</p>
                @endif
            </div>
            
            <!-- Medal Stats Grid -->
            <div class="medal-stats-grid" data-aos="fade-up" data-aos-delay="100">
                <!-- Gold Medal Card -->
                <div class="medal-stat-card">
                    <div class="text-center">
                        <div class="medal-circle xl gold mx-auto mb-6" data-aos="zoom-in" data-aos-delay="200">G</div>
                        <div class="text-4xl font-bold text-white mb-2" data-medal-count="{{ $overallTotal['gold_tally'] }}">0</div>
                        <div class="text-sm uppercase tracking-wider text-white/70">Gold</div>
                    </div>
                </div>
                
                <!-- Silver Medal Card -->
                <div class="medal-stat-card">
                    <div class="text-center">
                        <div class="medal-circle xl silver mx-auto mb-6" data-aos="zoom-in" data-aos-delay="300">S</div>
                        <div class="text-4xl font-bold text-white mb-2" data-medal-count="{{ $overallTotal['silver_tally'] }}">0</div>
                        <div class="text-sm uppercase tracking-wider text-white/70">Silver</div>
                    </div>
                </div>
                
                <!-- Bronze Medal Card -->
                <div class="medal-stat-card">
                    <div class="text-center">
                        <div class="medal-circle xl bronze mx-auto mb-6" data-aos="zoom-in" data-aos-delay="400">B</div>
                        <div class="text-4xl font-bold text-white mb-2" data-medal-count="{{ $overallTotal['bronze_tally'] }}">0</div>
                        <div class="text-sm uppercase tracking-wider text-white/70">Bronze</div>
                    </div>
                </div>
                
                <!-- Total Medals Card -->
                <div class="medal-stat-card" style="background: linear-gradient(135deg, rgba(var(--team-color-rgb), 0.5), rgba(var(--team-color-rgb), 0.2));">
                    <div class="text-center">
                        <div class="medal-circle xl bg-white mx-auto mb-6" data-aos="zoom-in" data-aos-delay="500" style="color: var(--team-color)">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <div class="text-4xl font-bold text-white mb-2" data-medal-count="{{ $overallTotal['total_medals'] }}">0</div>
                        <div class="text-sm uppercase tracking-wider text-white/70">Total</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

        <div class="container mx-auto px-6 py-12">
     

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

         
        </div>
@endif
@endsection

@section('scripts')
    @vite(['resources/js/teamdetails.js'])
@endsection
{{-- resources/views/public/standings/index.blade.php --}}
@extends('public.layouts.app')

@section('title', 'Medal Standings')

@section('styles')
<style>
    .team-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .medal-icon {
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        font-weight: bold;
        margin-right: 5px;
    }
    .gold {
        background: linear-gradient(135deg, #FFD700, #FFA500);
    }
    .silver {
        background: linear-gradient(135deg, #C0C0C0, #A9A9A9);
    }
    .bronze {
        background: linear-gradient(135deg, #CD7F32, #A0522D);
    }
    .medal-count {
        font-weight: bold;
        margin-right: 10px;
    }
    .highlight-row {
        background-color: rgba(0,123,255,0.05);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-4 fw-bold text-center mb-4">Medal Standings</h1>
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center">#</div>
                        <div class="col-md-5">Team</div>
                        <div class="col-md-4">Medals</div>
                        <div class="col-md-2 text-end">Score</div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($teams as $index => $team)
                            @php
                                $goldCount = $team->medalRecords->sum('gold_tally');
                                $silverCount = $team->medalRecords->sum('silver_tally');
                                $bronzeCount = $team->medalRecords->sum('bronze_tally');
                                $totalMedals = $goldCount + $silverCount + $bronzeCount;
                                $score = $goldCount * 3 + $silverCount * 2 + $bronzeCount;
                                $highlightClass = $index % 2 === 0 ? 'highlight-row' : '';
                            @endphp
                            <a href="{{ route('public.standings.details', $team->id) }}" class="list-group-item list-group-item-action team-card {{ $highlightClass }}">
                                <div class="row align-items-center py-2">
                                    <div class="col-md-1 text-center fw-bold fs-5">{{ $index + 1 }}</div>
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center">
                                            @if($team->flag_url)
                                                <img src="{{ $team->flag_url }}" alt="{{ $team->name }} flag" class="me-3" width="40">
                                            @endif
                                            <div>
                                                <h5 class="mb-0 fw-bold">{{ $team->name }}</h5>
                                                <small class="text-muted">{{ $team->code }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center">
                                            <div class="medal-icon gold">G</div>
                                            <span class="medal-count">{{ $goldCount }}</span>
                                            <div class="medal-icon silver">S</div>
                                            <span class="medal-count">{{ $silverCount }}</span>
                                            <div class="medal-icon bronze">B</div>
                                            <span class="medal-count">{{ $bronzeCount }}</span>
                                            <span class="text-muted ms-2">(Total: {{ $totalMedals }})</span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end fw-bold fs-5">{{ $score }}</div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <p>No team standings available at this time.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
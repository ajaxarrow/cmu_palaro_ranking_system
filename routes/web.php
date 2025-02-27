<?php

use App\Http\Controllers\AppUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\MedalRecordController;
use App\Http\Controllers\MedalWeightCategoryController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamCompositionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware('auth')->group(function () {

    // Team Routes
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{id}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/{id}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{id}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('teams.destroy');

    // College Routes
    Route::get('/colleges', [CollegeController::class, 'index'])->name('colleges.index');
    Route::get('/colleges/create', [CollegeController::class, 'create'])->name('colleges.create');
    Route::post('/colleges', [CollegeController::class, 'store'])->name('colleges.store');
    Route::get('/colleges/{id}', [CollegeController::class, 'show'])->name('colleges.show');
    Route::get('/colleges/{id}/edit', [CollegeController::class, 'edit'])->name('colleges.edit');
    Route::put('/colleges/{id}', [CollegeController::class, 'update'])->name('colleges.update');
    Route::delete('/colleges/{id}', [CollegeController::class, 'destroy'])->name('colleges.destroy');

    // Team Composition Routes
    Route::get('/team-compositions', [TeamCompositionController::class, 'index'])->name('team_compositions.index');
    Route::get('/team-compositions/create', [TeamCompositionController::class, 'create'])->name('team_compositions.create');
    Route::post('/team-compositions', [TeamCompositionController::class, 'store'])->name('team_compositions.store');
    Route::get('/team-compositions/{id}', [TeamCompositionController::class, 'show'])->name('team_compositions.show');
    Route::get('/team-compositions/{id}/edit', [TeamCompositionController::class, 'edit'])->name('team_compositions.edit');
    Route::put('/team-compositions/{id}', [TeamCompositionController::class, 'update'])->name('team_compositions.update');
    Route::delete('/team-compositions/{id}', [TeamCompositionController::class, 'destroy'])->name('team_compositions.destroy');

    // Sport Routes
    Route::get('/sports', [SportController::class, 'index'])->name('sports.index');
    Route::get('/sports/create', [SportController::class, 'create'])->name('sports.create');
    Route::post('/sports', [SportController::class, 'store'])->name('sports.store');
    Route::get('/sports/{id}', [SportController::class, 'show'])->name('sports.show');
    Route::get('/sports/{id}/edit', [SportController::class, 'edit'])->name('sports.edit');
    Route::put('/sports/{id}', [SportController::class, 'update'])->name('sports.update');
    Route::delete('/sports/{id}', [SportController::class, 'destroy'])->name('sports.destroy');

    // Event Category Routes
    Route::get('/event-categories', [EventCategoryController::class, 'index'])->name('event_categories.index');
    Route::get('/event-categories/create', [EventCategoryController::class, 'create'])->name('event_categories.create');
    Route::post('/event-categories', [EventCategoryController::class, 'store'])->name('event_categories.store');
    Route::get('/event-categories/{id}', [EventCategoryController::class, 'show'])->name('event_categories.show');
    Route::get('/event-categories/{id}/edit', [EventCategoryController::class, 'edit'])->name('event_categories.edit');
    Route::put('/event-categories/{id}', [EventCategoryController::class, 'update'])->name('event_categories.update');
    Route::delete('/event-categories/{id}', [EventCategoryController::class, 'destroy'])->name('event_categories.destroy');

    // Medal Weight Category Routes
    Route::get('/medal-weight-categories', [MedalWeightCategoryController::class, 'index'])->name('medal_weight_categories.index');
    Route::get('/medal-weight-categories/create', [MedalWeightCategoryController::class, 'create'])->name('medal_weight_categories.create');
    Route::post('/medal-weight-categories', [MedalWeightCategoryController::class, 'store'])->name('medal_weight_categories.store');
    Route::get('/medal-weight-categories/{id}', [MedalWeightCategoryController::class, 'show'])->name('medal_weight_categories.show');
    Route::get('/medal-weight-categories/{id}/edit', [MedalWeightCategoryController::class, 'edit'])->name('medal_weight_categories.edit');
    Route::put('/medal-weight-categories/{id}', [MedalWeightCategoryController::class, 'update'])->name('medal_weight_categories.update');
    Route::delete('/medal-weight-categories/{id}', [MedalWeightCategoryController::class, 'destroy'])->name('medal_weight_categories.destroy');

    // Medal Record Routes
    Route::get('/medal-records', [MedalRecordController::class, 'index'])->name('medal_records.index');
    Route::get('/medal-records/create', [MedalRecordController::class, 'create'])->name('medal_records.create');
    Route::post('/medal-records', [MedalRecordController::class, 'store'])->name('medal_records.store');
    Route::get('/medal-records/{id}', [MedalRecordController::class, 'show'])->name('medal_records.show');
    Route::get('/medal-records/{id}/edit', [MedalRecordController::class, 'edit'])->name('medal_records.edit');
    Route::put('/medal-records/{id}', [MedalRecordController::class, 'update'])->name('medal_records.update');
    Route::delete('/medal-records/{id}', [MedalRecordController::class, 'destroy'])->name('medal_records.destroy');

    Route::get('/results', [ResultsController::class, 'index'])->name('results.index');
    Route::get('/users', [AppUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AppUserController::class, 'store'])->name('users.store');
    Route::put('/users/{id}', [AppUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AppUserController::class, 'destroy'])->name('users.destroy');


});




require __DIR__.'/auth.php';

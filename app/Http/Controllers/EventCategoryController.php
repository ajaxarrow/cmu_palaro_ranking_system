<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCategory;
use App\Models\Sport;
use App\Models\MedalWeightCategory;

class EventCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventCategories = EventCategory::with('sport', 'medalWeightCategory')->get();
        $sports = Sport::all();
        $medalWeightCategories = MedalWeightCategory::all();
        return view('admin.event_categories.index', compact('eventCategories', 'sports', 'medalWeightCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('event_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'is_double_bronze' => 'nullable|boolean',
            'sport_id' => 'required|exists:sports,id',
            'medal_weight_category_id' => 'required|exists:medal_weight_categories,id',
            'medal_count' => 'required|numeric',
        ]);

        // If checkbox is not in request, set it to false
        $validatedData['is_double_bronze'] = $request->boolean('is_double_bronze', false);

        EventCategory::create($validatedData);

        return redirect()->route('event_categories.index')->with('success', 'Event Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);
        return view('event_categories.show', compact('eventCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);
        return view('event_categories.edit', compact('eventCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
{

    $validatedData = $request->validate([
        'medal_count' => 'required|numeric',
        'name' => 'required|string|max:255',
        'is_double_bronze' => 'nullable|boolean',
        'sport_id' => 'required|exists:sports,id',
        'medal_weight_category_id' => 'required|exists:medal_weight_categories,id',
    ]);

    // If checkbox is not in request, set it to false
    $validatedData['is_double_bronze'] = $request->boolean('is_double_bronze', false);

    $eventCategory = EventCategory::findOrFail($id);
    $eventCategory->update($validatedData);

    return redirect()->route('event_categories.index')->with('success', 'Event Category updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $eventCategory = EventCategory::findOrFail($id);
        $eventCategory->delete();

        return redirect()->route('event_categories.index')->with('success', 'Event Category deleted successfully.');
    }
}

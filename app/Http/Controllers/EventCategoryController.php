<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventCategory;

class EventCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eventCategories = EventCategory::all();
        return view('admin.event_categories.index', compact('eventCategories'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'is_double_bronze' => 'required|boolean',
            'sport_id' => 'required|exists:sports,id',
            'medal_weight_category_id' => 'required|exists:medal_weight_categories,id',
        ]);

        EventCategory::create($request->all());

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
        $formFields = $request->validate([
            'name' => 'required|string|max:255',
            'is_double_bronze' => 'required|boolean',
            'sport_id' => 'required|exists:sports,id',
            'medal_weight_category_id' => 'required|exists:medal_weight_categories,id',
        ]);

        $eventCategory = EventCategory::findOrFail($id);
        $eventCategory->update($formFields);

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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedalWeightCategory;

class MedalWeightCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = MedalWeightCategory::all();
        return view('admin.medal_weight_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medal_weight_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_no' => 'required|integer',
            'name' => 'required|string|max:255',
            'gold_weight' => 'required|numeric',
            'silver_weight' => 'required|numeric',
            'bronze_weight' => 'required|numeric',
        ]);

        MedalWeightCategory::create($request->all());
        return redirect()->route('medal_weight_categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedalWeightCategory $medalWeightCategory)
    {
        return view('medal_weight_categories.show', compact('medalWeightCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedalWeightCategory $medalWeightCategory)
    {
        return view('medal_weight_categories.edit', compact('medalWeightCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $medalWeightCategory = MedalWeightCategory::findOrFail($id);
        $formFields = $request->validate([
            'category_no' => 'required|integer',
            'name' => 'required|string|max:255',
            'gold_weight' => 'required|numeric',
            'silver_weight' => 'required|numeric',
            'bronze_weight' => 'required|numeric',
        ]);

        $medalWeightCategory->update($formFields);
        return redirect()->route('medal_weight_categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedalWeightCategory $medalWeightCategory)
    {
        $medalWeightCategory->delete();
        return redirect()->route('medal_weight_categories.index');
    }
}

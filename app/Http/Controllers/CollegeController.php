<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\College;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges = College::all();
        return view('admin.colleges.index', compact('colleges'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colleges.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'acronym' => 'required|string|max:10',
        ]);

        College::create($request->all());
        return redirect()->route('colleges.index')->with('success', 'College created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $college = College::findOrFail($id);
        return view('colleges.show', compact('college'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $college = College::findOrFail($id);
        return view('colleges.edit', compact('college'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'acronym' => 'required|string|max:10',
        ]);

        $college = College::findOrFail($id);
        $college->update($request->all());
        return redirect()->route('colleges.index')->with('success', 'College updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        College::findOrFail($id)->delete();
        return redirect()->route('colleges.index')->with('success', 'College deleted successfully.');
    }
}

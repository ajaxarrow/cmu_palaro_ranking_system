<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedalRecord;

class MedalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medalRecords = MedalRecord::all();
        return view('medal_records.index', compact('medalRecords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medal_records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'gold_tally' => 'required|integer',
            'silver_tally' => 'required|integer',
            'bronze_tally' => 'required|integer',
            'entry_by' => 'required|exists:users,id',
            'posted_by' => 'nullable|exists:users,id',
            'is_posted' => 'boolean',
            'number_of_participant' => 'required|integer',
        ]);

        MedalRecord::create($request->all());
        return redirect()->route('medal_records.index')->with('success', 'Medal record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedalRecord $medalRecord)
    {
        return view('medal_records.show', compact('medalRecord'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedalRecord $medalRecord)
    {
        return view('medal_records.edit', compact('medalRecord'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedalRecord $medalRecord)
    {
        $request->validate([
            'year' => 'required|integer',
            'gold_tally' => 'required|integer',
            'silver_tally' => 'required|integer',
            'bronze_tally' => 'required|integer',
            'entry_by' => 'required|exists:users,id',
            'posted_by' => 'nullable|exists:users,id',
            'is_posted' => 'boolean',
            'number_of_participant' => 'required|integer',
        ]);

        $medalRecord->update($request->all());
        return redirect()->route('medal_records.index')->with('success', 'Medal record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedalRecord $medalRecord)
    {
        $medalRecord->delete();
        return redirect()->route('medal_records.index')->with('success', 'Medal record deleted successfully.');
    }
}

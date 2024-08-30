<?php

namespace App\Http\Controllers;

use App\Models\Day;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Day $day)
    {
        return view('admin.days.show', compact('day'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Day $day)
    {
        return view('admin.days.edit', compact('day'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

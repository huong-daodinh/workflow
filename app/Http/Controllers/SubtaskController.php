<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function markAsDone($id)
    {
      try {
        $subtask = SubTask::findOrFail($id);
        $subtask->update(['status' => 'done']);
        return response()->json([ 'type' => 'success', 'message' => 'Task updated']);
      }catch (\Exception $e) {
        return response()->json([ 'type' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
      }
    }
}

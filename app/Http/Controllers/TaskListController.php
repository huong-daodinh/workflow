<?php

namespace App\Http\Controllers;

use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TaskListController extends Controller
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
        $validated = $request->validate([
            'name' => 'required||max:255',
            'project_id' => 'required|exists:projects,id',
        ]);
        TaskList::create($validated);
        Session::flash('flash', [
          'type' => 'success',
          'message' => 'Task list created'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $taskList = TaskList::find($id);
        if (!$taskList) {
          Session::flash('flash', [
              'type' => 'error',
              'message' => 'Task list not found'
          ]);
        } else {
          $validated = $request->validate([
              'name' => 'required|max:255',
              'project_id' => 'required|exists:projects,id',
          ]);
          $taskList->update($validated);
          Session::flash('flash', [
              'type' => 'success',
              'message' => 'Task list updated'
          ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $taskList = TaskList::find($id);
        if (!$taskList) {
          Session::flash('flash', [
              'type' => 'error',
              'message' => 'Task list not found'
          ]);
        } else {
          $taskList->delete();
          Session::flash('flash', [
              'type' => 'success',
              'message' => 'Task list deleted'
          ]);
        }
    }
}

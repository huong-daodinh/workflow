<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubtaskRequest;
use App\Models\SubTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

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
    public function store(SubtaskRequest $request)
    {
        $validated = $request->validated();
        $subtask = SubTask::create($validated);
        Session::flash('flash', ['type' => 'success', 'message' => 'Subtask created']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $subtask = SubTask::findOrFail($id);
        $subtask->load([
          'assigner',
          'assignee'
        ]);
        return response()->json($subtask);
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
    public function update(SubtaskRequest $request, $id)
    {
        $validated = $request->validated();
        $subtask = SubTask::findOrFail($id);
        $subtask->update($validated);
        Session::flash('flash', ['type' => 'success', 'message' => 'Subtask updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function markAsDone(Request $request, $id)
    {
      try {
        $subtask = SubTask::findOrFail($id);
        $response = Gate::inspect('complete', $subtask);
        if ($response->allowed()) {
          if (!$subtask->result) {
            throw new \Exception('Chưa cập nhật kết quả làm việc');
          }
          $subtask->update(['status' => $request->status]);
          Session::flash('flash', ['type' => 'success', 'message' => 'Subtask updated']);
        } else {
          throw new \Exception('Bạn không thể thực hiện hành động này');
        }
      }catch (\Exception $e) {
        Session::flash('flash', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
      }
    }

    public function startTask(Request $request, $id) {
      try {
        $task = SubTask::findOrFail($id);
        $response = Gate::inspect('start', $task);
        if ($response->allowed()) {
          $validated = $request->validate([
            'started_at' => 'required',
            'status' => 'required|string',
          ]);
          $task->update($validated);
          Session::flash('flash', ['type' => 'success', 'message' => 'Task started']);
        } else {
          throw new \Exception('Không thể thực hiện hành động này');
        }
      } catch(\Exception $e) {
        Session::flash('flash', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
      }
  }
}

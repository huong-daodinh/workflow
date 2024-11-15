<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Task;
use App\Models\TaskAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
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
        $task = Task::findOrFail($id);
        $task->load(['subTasks', 'assigner', 'messages', 'tags', 'followers', 'attachments']);
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

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

    public function markAsDone( $taskId)
    {
        try {
          $task = Task::findOrFail($taskId);
          if ($task->result) {
            $task->update(['status' => 'done']);
            $completedTag = Tag::where('slug', 'completed')->first();
            $task->tags()->sync([$completedTag->id]);
            return response()->json(['type' => 'success', 'message' => 'Task updated']);
          } else {
            return response()->json(['type' => 'error', 'message' => 'Enter task result before marking as done']);
          }
        } catch(\Exception $e) {
          return response()->json(['type' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function updateResult(Request $request, $taskId)
    {
        try {
          $task = Task::findOrFail($taskId);
          $data = $request->validate([
            'result' => 'required',
          ]);
          $task->update($data);
          return response()->json(['type' => 'success', 'message' => 'Result updated']);
        } catch (\Exception $e) {
          return response()->json(['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function uploadAttachment(Request $request)
    {
      $validated = $request->validate([
        'file' => ['nullable', 'file', 'max:10240'],
      ]);
      $data['url'] = uploadFile($validated['file'], 'task-attachments');
      $data['slug'] = $validated['file']->getClientOriginalName();
      if ($request->has('task_id')) {
        $data['task_id'] = $request->task_id;
      }
      $attachment = TaskAttachment::create($data);
      Session::flash('flash_data', [
        'file' => $attachment,
      ]);
    }

    public function downloadAttachment($attachment)
    {
      $attachment = TaskAttachment::find($attachment);
      if (!$attachment || !file_exists(public_path($attachment->url))) {
        Session::flash('flash', ['type' => 'error', 'message' => 'File not found']);
      } else {
        return response()->download(public_path($attachment->url), $attachment->slug);
      }
    }
}

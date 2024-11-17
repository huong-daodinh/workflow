<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Tag;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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
        $task->load([
          'subTasks.assignee',
          'subTasks.tags',
          'assigner',
          'messages',
          'tags',
          'followers',
          'attachments',
        ]);
        return response()->json(['task' => $task]);
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
    public function update(TaskRequest $request, $task)
    {
      try {
        $task = Task::findOrFail($task);
        $validated = $request->validated();
        $task->update($validated);
        Session::flash('flash', ['type' => 'success', 'message' => 'Task updated']);
      } catch (\Exception $e) {
        Session::flash('flash', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
      }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    public function startTask(Request $request, $id) {
      try {
        $task = Task::findOrFail($id);
        $response = Gate::inspect('start', $task);
        if ($response->allowed()) {
          $validated = $request->validate([
            'started_at' => 'required|date',
            'status' => 'required|string',
          ]);
          $task->update($validated);
          Session::flash('flash', ['type' => 'success', 'message' => 'Task started']);
        } else {
          throw new \Exception('Không thể thực hiện hành động này');
        }
      } catch(\Exception $e) {
        return Session::flash('flash', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
      }
    }

    public function markAsDone($task)
    {
        try {
          $task = Task::findOrFail($task);
          $response = Gate::inspect('done', $task);
          if ($response->allowed()) {
            if ($task->result) {
              $task->update(['status' => 'done']);
              Session::flash('flash', ['type' => 'success', 'message' => 'Task updated']);
            } else {
              throw new \Exception('Chưa cập nhật kết quả làm việc');
            }
          } else {
            throw new \Exception('Không thể thực hiện hành động này');
          }
        } catch(\Exception $e) {
          Session::flash('flash', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
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

    public function deleteAttachment($attachment)
    {
      $attachment = TaskAttachment::find($attachment);
      if (!$attachment || !file_exists(public_path($attachment->url))) {
        Session::flash('flash', ['type' => 'error', 'message' => 'File not found']);
      } else {
        unlink(public_path($attachment->url));
        $attachment->delete();
        Session::flash('flash', ['type' => 'success', 'message' => 'Attachment deleted']);
      }
    }

    public function closeTask($id) {
        $task = Task::findOrFail($id);
        $response = Gate::inspect('process-task', $task);
        if ($response->allowed()) {
          $task->update(['status' => 'closed']);
          Session::flash('flash', ['type' => 'success', 'message' => 'Task closed']);
        }
    }

    public function rejectTask($id) {
        $task = Task::findOrFail($id);
        $response = Gate::inspect('process-task', $task);
        if ($response->allowed()) {
          $task->update(['status' => 'todo']);
          Session::flash('flash', ['type' => 'success', 'message' => 'Task rejected']);
        }

    }

    public function updateTaskResult(Request $request, $task) {
      try {
        $task = Task::findOrFail($task);
        $validated = $request->validate([
          'result' => 'required|string'
        ]);
        $task->update($validated);
        Session::flash('flash', ['type' => 'success', 'message' => 'Task updated']);
      } catch (\Exception $e) {
        Session::flash('flash', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
      }
    }
}

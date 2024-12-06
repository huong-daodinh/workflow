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
        $data = $request->validate([
          'title' => 'required|string|min:5|max:255',
          'priority' => 'nullable|string',
          'description' => 'required|string',
          'status' => 'nullable|string',
          'created_by' => 'required|integer|exists:users,id',
          'assigner_id' => 'required|integer|exists:users,id',
          'due_date' => 'required|date',
          'assignee_id' => 'required|integer|exists:users,id',
          'tags' => 'nullable|array',
          'tags.*' => 'integer|exists:tags,id',
          'project_id' => 'required|integer|exists:projects,id',
          'task_list_id' => 'nullable|integer|exists:task_lists,id',
        ]);
        $task = Task::create($data);
        $task->tags()->sync($data['tags']);
        $task->followers()->sync([$data['assigner_id'], $data['assignee_id']]);
        Session::flash('flash', ['type' => 'success', 'message' => 'Task created']);
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
        $nonFollowers = User::whereDoesntHave('tasks', function ($query) use ($task) {
          $query->where('task_id', $task->id);
        })->get();
        return response()->json([
          'task' => $task,
          'nonFollowers' => $nonFollowers,
        ]);
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
        $response = Gate::inspect('process', $task);
        if ($response->allowed()) {
          $task->update(['status' => 'closed']);
          Session::flash('flash', ['type' => 'success', 'message' => 'Task closed']);
        } else {
          Session::flash('flash', ['type' => 'error', 'message' => 'Không thể thực hiện']);

        }
    }

    public function rejectTask($id) {
        $task = Task::findOrFail($id);
        $response = Gate::inspect('process', $task);
        if ($response->allowed()) {
          $task->update(['status' => 'doing']);
          Session::flash('flash', ['type' => 'success', 'message' => 'Task rejected']);
        } else {
          Session::flash('flash', ['type'=> 'error', 'message' => 'Không thể thực hiện']);
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

    public function addFollowers(Request $request, $task) {

      try {
        $task = Task::findOrFail($task);
        $data = $request->validate([
          'followers' => 'required|array',
          'followers.*' => 'integer|exists:users,id',
        ]);
        foreach($data['followers'] as $follower) {
          $task->followers()->attach($follower);
        }
        Session::flash('flash', ['type' => 'success', 'message' => 'Followers added']);
      } catch (\Exception $e) {
        Session::flash('flash', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
      }
    }
}

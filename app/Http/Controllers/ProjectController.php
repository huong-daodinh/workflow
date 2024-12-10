<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Services\ProjectService;
use App\Models\Project;
use App\Models\ProjectAttachment;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class ProjectController extends Controller
{

    public function __construct(public ProjectService $projectService)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authUser = Auth::user();
        $projects = Project::with('attachments', 'createdBy')->filter()
        ->when('admin' !== $authUser->role, function($query) use($authUser) {
            $query->where('department_id', $authUser->department_id);
        })
        ->get();
        return Inertia::render('Project/Index', [
            'projects' => $projects
        ]);
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
    public function store(ProjectRequest $request)
    {
        if (Gate::denies('create-project')) {
          Session::flash('flash', [
            'type' => 'error',
            'message' => 'Bạn không có quyền này'
          ]);
          return ;
        }
        $validated = $request->validated();
        $validated['created_by'] = Auth::user()->id;
        $project = Project::create($validated);
        $attachments = ProjectAttachment::whereIn('id', $validated['files'])->get();
        foreach($attachments as $attachment) {
            $attachment->update(['project_id' => $project->id]);
        }
        Session::flash('flash', [
            'type' => 'success',
            'message' => 'Project created'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);
        $project->load(
          [
            'tasks',
            'createdBy',
            'attachments',
            'taskLists.project',
            'taskLists.tasks',
            'taskLists.tasks.subTasks',
            'taskLists.tasks.subTasks.assignee',
            'taskLists.tasks.subTasks.tags',
            'taskLists.tasks.assignee',
            'taskLists.tasks.tags'
          ]
        );
        $availableAssigners = User::where([['role', '=', 'manager'], ['department_id', '=', $project->department_id]])->get();
        $availableAssignees = User::where([['role', '=', 'member'], ['department_id', '=', $project->department_id]])->get();
        $tags = Tag::whereIn('slug', ['important', 'urgent'])->get();
        return Inertia::render('Project/ProjectDetail', [
            'project' => $project,
            'assignees' => $availableAssignees,
            'assigners' => $availableAssigners,
            'tags' => $tags
        ]);
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
    public function update(ProjectRequest $request, $id)
    {
      $project = Project::findOrFail($id);
      if (Gate::denies('update-project', $project)) {
        Session::flash('flash', [
          'type' => 'error',
          'message' => 'Bạn không có quyền cập nhật'
        ]);
        return ;
      }
      $validated = $request->validated();
      $project->update($validated);
      $attachments = ProjectAttachment::whereIn('id', $validated['files'])->get();
      foreach($attachments as $attachment) {
          $attachment->update(['project_id' => $project->id]);
      }
      Session::flash('flash', [
          'type' => 'success',
          'message' => 'Project updated'
      ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
      $project = Project::findOrFail($id);
      if (Gate::denies('delete-project', $project)) {
        Session::flash('flash', [
          'type' => 'error',
          'message' => 'Bạn không có quyền xoá'
        ]);
        return ;
      }
      $project->delete();
      Session::flash('flash', [
        'type' => 'success',
        'message' => 'Project deleted'
      ]);
    }

    public function analyze($id) {
      $project = Project::findOrFail($id);
      $project->load('taskLists');
      $taskLists = $project->taskLists;
      $taskListIds = $taskLists->pluck('id');
      $tasksByStatus = [];
      $tasks = Task::where(function ($query) use ($id, $taskListIds) {
            $query->where('project_id', $id)
                  ->orWhereIn('task_list_id', $taskListIds);
      })->orderBy('created_at', 'desc')->get();

        $totalTasks = $tasks->count();
        $recentTasks = $tasks->take(5);
        foreach($recentTasks as $task) {
          $task->load('assigner', 'assignee');
        }
        $statuses = [
            'doing' => ['color' => 'blue'],
            'pending' => ['color' => 'sky'],
            'overdue' => ['color' => 'red'],
            'done' => ['color' => 'green']
        ];

        foreach ($statuses as $slug => $details) {
            $task = $tasks->filter(function ($task) use ($slug, $details) {
              return $task->tags->contains('slug', $slug) && (!isset($details['status']) || $task->status == $details['status']);
            });
            $tasksByStatus[$slug] = [
              'count' => $task->count(),
              'color' => $details['color'],
              'slug' => $slug,
                'rate' => $totalTasks > 0 ? round(($task->count() / $totalTasks) * 100, 2) : 0
            ];
        }
        return response()->json([
        'tasksByStatus' => $tasksByStatus,
        'recentTasks' => $recentTasks,
        'totalTasks' => $totalTasks
      ]);
    }
}

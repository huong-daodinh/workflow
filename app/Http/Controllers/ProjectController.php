<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Http\Services\ProjectService;
use App\Models\Project;
use App\Models\ProjectAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $projects = $this->projectService->getAllProjects();
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
        $project->load(['createdBy', 'attachments', 'task_lists']);
        foreach($project->task_lists as $taskList) {
            $taskList->load('tasks.tags');
            foreach($taskList->tasks as $task) {
                $task->load(['sub_tasks', 'assignee']);
                foreach($task->sub_tasks as $subTask) {
                    $subTask->load(['assignee', 'tags']);
                }
            }
        }

        return Inertia::render('Project/ProjectDetail', [
            'project' => $project
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
}

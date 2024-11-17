<?php
namespace App\Http\Repositories;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectRepository {

    public function getAllProjects() {
        $authUser = Auth::user();
        $projects = Project::filter()->where([['type', '=', 'P'], ['created_by', '=', $authUser->id]])
        ->orWhere('department_id', $authUser->department_id)->get();
        foreach($projects as $project) {
            $project->load('createdBy');
        }
        return $projects;
    }
}

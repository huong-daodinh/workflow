<?php
namespace App\Http\Repositories;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectRepository {

    public function getAllProjects() {
        $authUser = Auth::user();
        $projects = Project::with('attachments', 'createdBy')->filter()
        ->where('department_id', $authUser->department_id)->get();
        return $projects;
    }
}

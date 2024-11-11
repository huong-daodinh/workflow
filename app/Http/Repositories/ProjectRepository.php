<?php
namespace App\Http\Repositories;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectRepository {

    public function getAllProjects() {
        $authUser = Auth::user();
        $projects = Project::filter()->where([['type', '=', 'P'], ['created_by', '=', $authUser->id]])
        ->orWhere(function ($query) use($authUser) {
            $query->where('type', 'T')
                  ->whereIn('team_id', function($query) use($authUser) {
                    $query->select('team_id')->from('team_members')->where('member_id', $authUser->id);
                  });
        })->get();
        foreach($projects as $project) {
            $project->load('createdBy');
        }
        return $projects;
    }
}

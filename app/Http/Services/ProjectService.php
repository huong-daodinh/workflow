<?php
namespace App\Http\Services;

use App\Http\Repositories\ProjectRepository;

class ProjectService {
    public function __construct(public ProjectRepository $projectRepo)
    {

    }

    public function getAllProjects() {
        return $this->projectRepo->getAllProjects();
    }
}

<?php

namespace App\Http\Services;

use App\Http\Repositories\TeamRepository;
use App\Models\Team;

class TeamService
{
    public function __construct(public TeamRepository $teamRepository)
    {
    }
    /**
     * Get all teams.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllTeams()
    {
        if (auth()->user()->role === 'admin') {
            return Team::all();
        }
        return $this->teamRepository->getUserTeams(auth()->id());
    }

    /**
     * Get a team by ID.
     *
     * @param int $id
     * @return Team|null
     */
    public function getTeamById($id)
    {
        return Team::find($id);
    }

    /**
     * Create a new team.
     *
     * @param array $data
     * @return Team
     */
    public function createTeam(array $data)
    {
        return Team::create($data);
    }

    /**
     * Update a team by ID.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateTeam($id, array $data)
    {
        $team = Team::find($id);
        if ($team) {
            return $team->update($data);
        }
        return false;
    }

    /**
     * Delete a team by ID.
     *
     * @param int $id
     * @return bool|null
     */
    public function deleteTeam($id)
    {
        $team = Team::find($id);
        if ($team) {
            return $team->delete();
        }
        return false;
    }
}

<?php
namespace App\Http\Repositories;

use App\Models\Team;


class TeamRepository
{
    protected $model;

    public function __construct(Team $team)
    {
        $this->model = $team;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $team = $this->model->find($id);
        if ($team) {
            $team->update($data);
            return $team;
        }
        return null;
    }

    public function delete($id)
    {
        $team = $this->model->findOrFail($id);
        return $team->delete();
    }

    public function getUserTeams($userId)
    {
        return $this->model->where('created_by', $userId)
            ->orWhereHas('teamMembers', function ($query) use ($userId) {
            $query->where('member_id', $userId);
            })->orderBy('created_at', 'desc')->get();
    }
}

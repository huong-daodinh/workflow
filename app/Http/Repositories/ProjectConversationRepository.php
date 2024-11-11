<?php

namespace App\Http\Repositories;

use App\Models\ProjectConversation;


class ProjectConversationRepository
{
    protected $model;

    public function __construct(ProjectConversation $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $conversation = $this->model->find($id);
        if ($conversation) {
            $conversation->update($data);
            return $conversation;
        }
        return null;
    }

    public function delete($id)
    {
        $conversation = $this->model->find($id);
        if ($conversation) {
            return $conversation->delete();
        }
        return false;
    }
}

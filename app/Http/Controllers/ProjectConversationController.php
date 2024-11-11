<?php

namespace App\Http\Controllers;

use App\Http\Services\ProjectConversationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectConversationController extends Controller
{
    public  function __construct(public ProjectConversationService $projectConversationService)
    {

    }

    public function getConversation($projectId)
    {
        $messages = $this->projectConversationService->getMessages($projectId);
        return Inertia::render('Project/Conversation', [
            'messages' => $messages
        ]);
    }
}

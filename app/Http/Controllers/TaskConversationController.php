<?php

namespace App\Http\Controllers;

use App\Events\TaskMessagePosted;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskConversationController extends Controller
{
    public function store(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $user = Auth::user();
        $data = [
          'task_id' => $task->id,
          'sent_by' => $user->id,
          'content' => $request->message
        ];
        $message = $task->messages()->create($data);
        $message->load('sentBy');
        broadcast(new TaskMessagePosted($task, $message))->toOthers();
        return response()->json(['message' => 'Message sent!']);
    }

    public function getMessages($id)
    {
        $task = Task::findOrFail($id);
        $task->load('messages');
        foreach($task->messages as $message){
            $message->load('sentBy');
        }
        return $task->messages;
    }
}

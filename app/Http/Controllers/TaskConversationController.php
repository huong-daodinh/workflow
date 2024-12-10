<?php

namespace App\Http\Controllers;

use App\Events\TaskMessagePosted;
use App\Models\Task;
use App\Models\TaskMessage;
use App\Parsers\CustomMentionParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class TaskConversationController extends Controller
{
    public function store(Request $request, $taskId)
    {
        $user = Auth::user();
        $data = [
          'task_id' => $taskId,
          'sent_by' => $user->id,
          'content' => $request->message
        ];
        $message = TaskMessage::create($data);
        $parser = new CustomMentionParser($message, [
          'pool' => 'users',
          'notify' => false,
          'regex_replacement' => [
              '{character}' => '@',
              '{pattern}' => '\(([^)]+)\)',
              '{rules}' => '{1,20}'
            ]
        ]);
        $message->content = $parser->parse($message->content);
        $message->save();
        $message->load('sentBy');
        broadcast(new TaskMessagePosted($message))->toOthers();
        return response()->json(['message' => $message]);
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

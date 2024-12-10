<?php

namespace App\Http\Controllers;

use App\Events\ChatCreated;
use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;


class ChatController extends Controller
{
    public function index() {
      $chats = Chat::with('members')
            ->whereHas('members', function (Builder $query) {
              $query->where('member_id', Auth::id());
            })
            ->get();

      foreach ($chats as $chat) {
        $chat->active = true;
        $chat->has_room = true;
        if ($chat->is_private_chat) {
          $tempUser = $chat->members->firstWhere('id', '!=', Auth::id());
          if ($tempUser) {
            $chat->name = $tempUser->name;
            $chat->avatar = $tempUser->avatar;
            $chat->active = $chat->members->where('id', '!=', Auth::id())
              ->where('last_seen', '>=', Carbon::now()->subMinutes(5))
              ->isNotEmpty();
            $chat->last_seen = $tempUser->last_seen;
          }
        }
      }

      return Inertia::render('Chat', [
        'rooms' => $chats
      ]);
    }

    public function getMessages($id) {
        $chat = Chat::findOrFail($id);
        $chat->load(['messages.sentBy', 'messages.replyTo']);
        $messages = $chat->messages;
        return response()->json(['messages' => $messages,]);
    }

    public function sendMessage(Request $request) {
      $validated = $request->validate([
        'content' => 'required|string',
        'sent_by' => 'required|integer',
        'sent_to' => 'nullable|integer',
        'chat_id' => 'required|integer',
        'replied_to' => 'nullable|integer',
      ]);
      $message = Message::create($validated);
      $message->load('sentBy',  'replyTo', 'chat');
      broadcast(new MessageSent($message))->toOthers();
      return response()->json(['message'=> $message]);
    }

    public function createChatRoom(Request $request) {
      $validated = $request->validate([
        'members' => 'required|array',
        'members.*' => 'required|integer|exists:users,id',
        'name' => 'required|string',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
      ]);
      $validated['members'][] = Auth::id();
      $validated['is_private_chat'] = false;
      if (!empty($validated['avatar'])) {
        $validated['avatar'] = uploadFile($validated['avatar'], 'project-attachments');
      } else {
        $validated['avatar'] = '/assets/images/group_chat.jpg';
      }
      $chat = Chat::create($validated);
      $chat->members()->sync(array_fill_keys($validated['members'], ['is_leader' => false]));
      $chat->members()->updateExistingPivot(Auth::id(), ['is_leader' => true]);
      foreach ($validated['members'] as $member) {
        if ($member != Auth::id()) {
          broadcast(new ChatCreated($member, $chat))->toOthers();
        }
      }
      Session::flash('flash_data', [
        'chat' => $chat
      ]);
    }

    public function search(Request $request) {
        $name = $request->search;
        $chats = Chat::with('members')
            ->whereHas('members', function (Builder $query) {
              $query->where('member_id', Auth::id());
          })->where('name', 'like', '%' . $name . '%')->get();
        foreach( $chats as $chat ) {
          $chat->has_room = true;
        }

        $users = User::where('name', 'like', '%' . $name . '%')
               ->where('id', '!=', Auth::id())
               ->get();

        foreach ($users as $user) {
            $hasRoom = Chat::where(function (Builder $query) use ($user) {
                        $query->where('name', $user->id . '__' . Auth::id())
                          ->orWhere('name', Auth::id() . '__' . $user->id);
                      })->first();
            // if private chat is created, show the chat room id instead of user id
            if ($hasRoom) {
              $user->has_room = true;
              $user->id = $hasRoom->id;
            }
            $user->active = $user->last_seen >= Carbon::now()->subMinutes(5);
        }

        $results = $chats->merge($users);
        return response()->json(['chats' => $results]);
    }

    public function createPrivateChat(Request $request) {
        $isExisted = Chat::where('name', $request->id . '__' . Auth::id())->orWhere('name', Auth::id() . '__' . $request->id)->exists();
        if (!$isExisted) {
          $data = [
            'name' => $request->name,
            'avatar' => null,
            'is_private_chat' => true,
          ];
          $chat = Chat::create($data);
          $chat->members()->sync([
            Auth::id() => ['is_leader' => true],
            $request->id => ['is_leader' => false]
          ]);
          $tempUser = User::where('id', $request->id)->first();
          $chat->has_room = true;
          $chat->name = $tempUser->name;
          $chat->avatar = $tempUser->avatar;
          $chat->active = $tempUser->last_seen >= Carbon::now()->subMinutes(5);
          $broadChat = [
            'id' => $chat->id,
            'name' => Auth::user()->name,
            'avatar' => Auth::user()->avatar,
            'active' => $chat->active,
            'has_room' => true
          ];
          broadcast(new ChatCreated($request->id, $broadChat))->toOthers();
          return response()->json(['chat'=> $chat]);
      }
    }

    public function getMembers() {
      $members = User::where('department_id', Auth::user()->department_id)
             ->where('id', '!=', Auth::id())
             ->get();
      return response()->json(['members'=> $members]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index() {
        $user = User::with(['tasks.tags'])->find(Auth::id());
        $tasksByStatus = [];
        $tasks = Task::where(function ($query) use ($user) {
            $query->where('assignee_id', $user->id)
            ->orWhere('created_by', $user->id);
        })->orderBy('created_at', 'desc')->get();

        $totalTasks = $tasks->count();
        $recentTasks = $tasks->take(5);
        foreach($recentTasks as $task) {
          $task->load('assigner');
        }
        $statuses = [
            'doing' => ['color' => 'blue'],
            'pending' => ['color' => 'sky'],
            'overdue' => ['color' => 'red'],
            'done' => ['color' => 'green']
        ];

        foreach ($statuses as $slug => $details) {
            $tasks = $user->tasks->filter(function ($task) use ($slug, $details) {
          return $task->tags->contains('slug', $slug) && (!isset($details['status']) || $task->status == $details['status']);
            });
            $tasksByStatus[$slug] = [
          'count' => $tasks->count(),
          'color' => $details['color'],
          'slug' => $slug,
          'rate' => $totalTasks > 0 ? round(($tasks->count() / $totalTasks) * 100, 2) : 0
            ];
        }

        return Inertia::render('Dashboard', [
          'tasksByStatus' => $tasksByStatus,
          'recentTasks' => $recentTasks,
          'totalTasks' => $totalTasks,
        ]);
    }
}

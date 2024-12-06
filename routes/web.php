<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectAttachmentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskConversationController;
use App\Http\Controllers\TaskListController;
use App\Http\Controllers\TaskResultController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TimesheetController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/setlocale/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'vi'])) {
      $locale = 'vi';
    }
    Session::put('locale', $locale);
    Cookie::queue(Cookie::forever('locale', $locale));
    return redirect()->back();
})->name('set-locale');

Route::get('/user/profile/{id}', function($id) {
  $user = User::findOrFail($id);
  return Inertia::render('ProfilePage');
})->name('user.profile');

Route::middleware(['auth', 'online'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/members', [ChatController::class, 'getMembers'])->name('chat.members');
    Route::get('/chat/search', [ChatController::class, 'search'])->name('chat.search');
    Route::post('/chat/create-private-chat', [ChatController::class, 'createPrivateChat'])->name('private-chat.create');
    Route::post('/chat/create', [ChatController::class, 'createChatRoom'])->name('chat.create');
    Route::get('/chat/messages/{chat}', [ChatController::class,'getMessages'])->name('chat.get-messages');
    Route::post('/chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.send-message');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

    Route::get('/analyze', function () {
        return Inertia::render('Analyze');
    })->name('analyze');

    Route::post('project/upload-attachment', [ProjectAttachmentController::class, 'upload'])->name('project-attachment.upload');
    Route::get('project/download-attachment/{attachment}', [ProjectAttachmentController::class, 'download'])->name('project-attachment.download');
    Route::get('project/analyze/{project}', [ProjectController::class, 'analyze'])->name('project.analyze');
    Route::resource('project', ProjectController::class);
    Route::resource('timesheet', TimesheetController::class);
    Route::resource('department', DepartmentController::class);

    Route::get('/task/messages/{task}', [TaskConversationController::class, 'getMessages'])->name('task.messages');
    Route::post('/task/send-message/{task}', [TaskConversationController::class, 'store'])->name('task.send-message');
    Route::post('/task/start-start/{task}', [TaskController::class, 'startTask'])->name('task.start');
    Route::post('/task/mark-as-done/{task}', [TaskController::class, 'markAsDone'])->name('task.mark-as-done');
    Route::post('/task/update-result/{taskId}', [TaskController::class, 'updateTaskResult'])->name('task.update-result');
    Route::post('/task/upload-attachment', [TaskController::class, 'uploadAttachment'])->name('task-attachment.upload');
    Route::get('/task/download-attachment/{attachment}', [TaskController::class, 'downloadAttachment'])->name('task-attachment.download');
    Route::delete('/task/delete-attachment/{attachment}', [TaskController::class, 'deleteAttachment'])->name('task-attachment.delete');
    Route::post('/task/close/{task}', [TaskController::class, 'closeTask'])->name('task.close');
    Route::post('/task/reject/{task}', [TaskController::class, 'rejectTask'])->name('task.reject');
    Route::post('task/add-followers/{task}', [TaskController::class, 'addFollowers'])->name('task.add-followers');
    Route::resource('task', TaskController::class);

    Route::post('subtask/mark-as-done/{subtask}', [SubtaskController::class, 'markAsDone'])->name('subtask.mark-as-done');
    Route::post('subtask/start/{subtask}', [SubtaskController::class, 'startTask'])->name('subtask.start');
    Route::resource('subtask', SubtaskController::class);

    Route::resource('task-list', TaskListController::class);
});


require __DIR__.'/auth.php';

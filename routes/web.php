<?php

use App\Http\Controllers\ChatController;
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
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
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



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::get('/analyze', function () {
        return Inertia::render('Analyze');
    })->name('analyze');

    Route::post('project/upload-attachment', [ProjectAttachmentController::class, 'upload'])->name('project-attachment.upload');
    Route::get('project/download-attachment/{attachment}', [ProjectAttachmentController::class, 'download'])->name('project-attachment.download');
    Route::resource('project', ProjectController::class);
    Route::resource('timesheet', TimesheetController::class);
    Route::resource('team', TeamController::class);

    Route::get('/task/messages/{task}', [TaskConversationController::class, 'getMessages'])->name('task.messages');
    Route::post('/task/send-message/{task}', [TaskConversationController::class, 'store'])->name('task.send-message');
    Route::post('/task/mark-as-done/{task}', [TaskController::class, 'markAsDone'])->name('task.mark-as-done');
    Route::post('/task/update-result/{taskId}', [TaskController::class, 'updateResult'])->name('task-result.update');
    Route::post('/task/upload-attachment', [TaskController::class, 'uploadAttachment'])->name('task-attachment.upload');
    Route::get('/task/download-attachment/{attachment}', [TaskController::class, 'downloadAttachment'])->name('task-attachment.download');
    Route::resource('task', TaskController::class);

    Route::post('subtask/mark-as-done/{subtask}', [SubtaskController::class, 'markAsDone'])->name('subtask.mark-as-done');
    Route::resource('subtask', SubtaskController::class);

    Route::resource('task-list', TaskListController::class);
});


require __DIR__.'/auth.php';

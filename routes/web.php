<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectAttachmentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskConversationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
    Route::resource('task', TaskController::class);
});

require __DIR__.'/auth.php';

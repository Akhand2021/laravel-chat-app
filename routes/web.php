<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AIChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::resource('posts', \App\Http\Controllers\Blog\PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
    Route::get('/messages', [App\Http\Controllers\ChatController::class, 'getMessages']);
    Route::post('/messages', [App\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::get('/messages/unread-count', [App\Http\Controllers\ChatController::class, 'getUnreadCount']);
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

Route::get('/whoami', function () {
    return auth()->user(); // should return ID 4
});
Route::get('/ai-chat', [AIChatController::class, 'index']);
Route::post('/ai-chat', [AIChatController::class, 'ask']);
Route::post('ckeditor/upload', [App\Http\Controllers\Blog\PostController::class, 'uploadImage'])->name('ckeditor.upload');

Broadcast::routes(["middleware" => ["web", "auth"]]);

require __DIR__ . '/auth.php';

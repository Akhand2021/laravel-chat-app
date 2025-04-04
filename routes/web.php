<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
    Route::get('/messages', [App\Http\Controllers\ChatController::class, 'getMessages']);
    Route::post('/messages', [App\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::get('/messages/unread-count', [App\Http\Controllers\ChatController::class, 'getUnreadCount']);
});

// Broadcasting routes
// Broadcast::routes(['middleware' => ['web', 'auth']]);
Route::get('/whoami', function () {
    return auth()->user(); // should return ID 4
});

Route::post('/debug/broadcasting/auth', function (\Illuminate\Http\Request $request) {
    return response()->json([
        'user' => auth()->user(),
        'cookie' => $request->cookie(),
        'session' => session()->all(),
    ]);
});

Broadcast::routes(["middleware" => ["web", "auth"]]);

require __DIR__ . '/auth.php';

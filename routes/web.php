<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AuthRedirectFromWelcome;

Route::get('/', function () {
    return view('welcome');
})->middleware(AuthRedirectFromWelcome::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/chat', [ChatController::class, 'index'])->name('chats.index');
// Route::get('/chat/{id}', [ChatController::class, 'index'])->name('chat.show');

require __DIR__.'/auth.php';

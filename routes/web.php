<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TrainingSessionController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home.index');


//auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Book routes
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');


// Book reading route
Route::get('/books/{id}/read', [BookController::class, 'read'])->name('books.read');


Route::get('/books/{id}/view', [BookController::class, 'view'])->name('book.view');


Route::get('/books/{book}/train', [BookController::class, 'train'])->name('books.train');
// Save training session; require auth in controller or middleware

Route::post('/books/{book}/training-sessions', [TrainingSessionController::class, 'store'])
    ->name('books.training_sessions.store')
    ->middleware('auth');

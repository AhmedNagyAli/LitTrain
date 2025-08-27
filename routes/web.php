<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::get('verify', [AuthController::class, 'showVerify'])->name('auth.verify');
Route::post('verify', [AuthController::class, 'verify']);

// Book routes
Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');


// Book reading route
Route::get('/books/{id}/read', [BookController::class, 'read'])->name('books.read');


Route::get('/books/{id}/view', [BookController::class, 'view'])->name('book.view');



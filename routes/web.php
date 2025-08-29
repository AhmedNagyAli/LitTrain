<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRecordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublishingRequestController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\UserProfileController;
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


    Route::get('/publishing-request/create', [PublishingRequestController::class, 'create'])
    ->name('publishing.request.create')->middleware('auth');
Route::post('/publishing-request', [PublishingRequestController::class, 'store'])
    ->name('publishing.request.store')->middleware('auth');


Route::middleware(['auth'])->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [UserProfileController::class, 'profile'])->name('profile');
                Route::post('/profile/update', [UserProfileController::class, 'updateProfile'])->name('profile.update');
        Route::get('/books', [UserProfileController::class, 'books'])->name('books');
        Route::get('/sessions', [UserProfileController::class, 'sessions'])->name('sessions');
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/books/{book}/records/create', [BookRecordController::class, 'create'])->name('books.records.create');
    Route::post('/books/{book}/records', [BookRecordController::class, 'store'])->name('books.records.store');
});

Route::get('/php-version', function () {
    return [
        'php_version' => PHP_VERSION,
        'ini_file' => php_ini_loaded_file(),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
});

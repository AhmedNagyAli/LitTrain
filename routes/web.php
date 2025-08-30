<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRecordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\PublishingRequestController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\UserProfileController;
use App\Models\Publisher;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home.index');


//auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/forgot-password', [AuthController::class, 'requestForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');



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
        Route::get('/books', [UserProfileController::class, 'books'])->name('books');
        Route::get('/sessions', [UserProfileController::class, 'sessions'])->name('sessions');
    });
});


Route::middleware(['auth'])->group(function () {
    //Route::get('/profile', [UserProfileController::class, 'index'])->name('user.profile');
    Route::post('/profile/name', [UserProfileController::class, 'updateName'])->name('user.profile.update.name');
    Route::post('/profile/email', [UserProfileController::class, 'updateEmail'])->name('user.profile.update.email');
    Route::post('/profile/phone', [UserProfileController::class, 'updatePhone'])->name('user.profile.update.phone');
    Route::post('/profile/bio', [UserProfileController::class, 'updateBio'])->name('user.profile.update.bio');
    Route::post('/profile/dob', [UserProfileController::class, 'updateDob'])->name('user.profile.update.dob');
    Route::post('/profile/language', [UserProfileController::class, 'updateLanguage'])->name('user.profile.update.language');
    Route::post('/profile/avatar', [UserProfileController::class, 'updateAvatar'])->name('user.profile.update.avatar');
});

Route::middleware('auth')->group(function () {
    Route::get('/books/{book}/records/create', [BookRecordController::class, 'create'])->name('books.records.create');
    Route::post('/books/{book}/records', [BookRecordController::class, 'store'])->name('books.records.store');
});

Route::get('/audiobooks', [BookController::class, 'audiobooks'])->name('books.audiobooks');





Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/publishers', [PublisherController::class, 'index'])->name('publishers.index');


Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('authors.show');
Route::get('/publishers/{publisher}', [PublisherController::class, 'show'])->name('publishers.show');

Route::get('/php-version', function () {
    return [
        'php_version' => PHP_VERSION,
        'ini_file' => php_ini_loaded_file(),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
    ];
});

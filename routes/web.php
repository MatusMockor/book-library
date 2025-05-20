<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('authors-dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Author routes
    Route::prefix('authors')->group(function () {
        Route::get('/', [App\Http\Controllers\AuthorController::class, 'index'])->name('authors.index');
        Route::post('/', [App\Http\Controllers\AuthorController::class, 'store'])->name('authors.store');
        Route::get('/{id}', [App\Http\Controllers\AuthorController::class, 'show'])->name('authors.show');
        Route::put('/{id}', [App\Http\Controllers\AuthorController::class, 'update'])->name('authors.update');
        Route::delete('/{id}', [App\Http\Controllers\AuthorController::class, 'destroy'])->name('authors.destroy');
    });

    // Book routes
    Route::prefix('books')->group(function () {
        Route::get('/', [App\Http\Controllers\BookController::class, 'index'])->name('books.index');
        Route::post('/', [App\Http\Controllers\BookController::class, 'store'])->name('books.store');
        Route::get('/{id}', [App\Http\Controllers\BookController::class, 'show'])->name('books.show');
        Route::put('/{id}', [App\Http\Controllers\BookController::class, 'update'])->name('books.update');
        Route::delete('/{id}', [App\Http\Controllers\BookController::class, 'destroy'])->name('books.destroy');
        Route::patch('/{id}/toggle-borrowed', [App\Http\Controllers\BookController::class, 'toggleBorrowedStatus'])->name('books.toggle-borrowed');
    });
});

require __DIR__.'/auth.php';

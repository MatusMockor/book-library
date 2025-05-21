<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', static function () {
    return view('dashboard.master');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Author routes
    Route::prefix('authors')->group(function () {
        Route::get('/', function () {
            return view('dashboard.authors.index');
        })->name('authors.index');
        Route::get('/api', [App\Http\Controllers\AuthorController::class, 'index'])->name('api.authors.index');
        Route::post('/', [App\Http\Controllers\AuthorController::class, 'store'])->name('authors.store');
        Route::get('/{id}', [App\Http\Controllers\AuthorController::class, 'show'])->name('authors.show');
        Route::put('/{id}', [App\Http\Controllers\AuthorController::class, 'update'])->name('authors.update');
        Route::delete('/{id}', [App\Http\Controllers\AuthorController::class, 'destroy'])->name('authors.destroy');
    });

    // Book routes - web interface
    Route::get('/books', static function () {
        return view('dashboard.books.index');
    })->name('books.index');
});

require __DIR__.'/auth.php';

<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('api.books.index');
    Route::post('/', [BookController::class, 'store'])->name('books.store');
    Route::get('/{book}', [BookController::class, 'show'])->name('books.show');
    Route::put('/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::patch('/{book}/toggle-borrowed',
        [BookController::class, 'toggleBorrowedStatus'])->name('books.toggle-borrowed');
})->middleware('auth:sanctum');

Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'index'])->name('api.authors.index');
    Route::post('/', [AuthorController::class, 'store'])->name('authors.store');
    Route::get('/{author}', [AuthorController::class, 'show'])->name('authors.show');
    Route::put('/{author}', [AuthorController::class, 'update'])->name('authors.update');
    Route::delete('/{author}', [AuthorController::class, 'destroy'])->name('authors.destroy');
})->middleware('auth:sanctum');

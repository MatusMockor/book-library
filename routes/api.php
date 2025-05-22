<?php

use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/books')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('api.books.index');
    Route::post('/', [BookController::class, 'store'])->name('books.store');
    Route::get('/{id}', [BookController::class, 'show'])->name('books.show');
    Route::put('/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::patch('/{id}/toggle-borrowed',
        [BookController::class, 'toggleBorrowedStatus'])->name('books.toggle-borrowed');
})->middleware('auth:sanctum');

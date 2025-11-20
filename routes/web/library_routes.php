<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

// ==========================
// LIBRARY MANAGEMENT ROUTES
// ==========================

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/list', [BookController::class, 'list'])->name('books.list');

Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::post('/books/store', [BookController::class, 'store'])->name('books.store');

Route::get('/books/{id}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');

Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

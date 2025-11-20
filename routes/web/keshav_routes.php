<?php

use App\Http\Controllers\KeshavController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Keshav / Users Routes (Protected by Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // 1. Create Form - Show the registration form
    Route::get('/new-user', [KeshavController::class, 'create'])
        ->name('newuser.create');

    // 2. Store New User
    Route::post('/new-user', [KeshavController::class, 'store'])
        ->name('newuser.store');

    // ==================== STANDARD USER RESOURCE ROUTES ====================

    // List All Users + AJAX Data
    Route::get('/users', [KeshavController::class, 'index'])
        ->name('users.index');

    Route::get('/users/list', [KeshavController::class, 'list'])
        ->name('users.list');

    // View Single User
    Route::get('/users/{id}', [KeshavController::class, 'show'])
        ->name('users.show')
        ->where('id', '[0-9]+');

    // Edit Form
    Route::get('/users/{id}/edit', [KeshavController::class, 'edit'])
        ->name('users.edit')
        ->where('id', '[0-9]+');

    // Update User
    Route::put('/users/{id}', [KeshavController::class, 'update'])
        ->name('users.update');

    // Delete User - Ab yeh standard aur clean URL pe hai
    Route::delete('/users/{id}', [KeshavController::class, 'destroy'])
        ->name('users.destroy');

    // Optional: Agar chaaho toh ye bhi rakh sakte ho (extra safety)
    // Route::post('/users/{id}/delete', [KeshavController::class, 'destroy'])->name('users.delete.confirm');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| Employee Performance Module Routes
|--------------------------------------------------------------------------
| This module handles:
| - Listing (AG-Grid)
| - Create
| - Store
| - Show
| - Edit
| - Update
| - Delete
|--------------------------------------------------------------------------
*/

Route::prefix('employees')->group(function () {

    // LIST (AG-Grid View)
    Route::get('/', [EmployeeController::class, 'index'])
        ->name('employees.index');

    // CREATE Form
    Route::get('/create', [EmployeeController::class, 'create'])
        ->name('employees.create');

    // STORE New Record
    Route::post('/store', [EmployeeController::class, 'store'])
        ->name('employees.store');

    // SHOW Employee Details
    Route::get('/{emp_id}', [EmployeeController::class, 'show'])
        ->name('employees.show');

    // EDIT Form
    Route::get('/{emp_id}/edit', [EmployeeController::class, 'edit'])
        ->name('employees.edit');

    // UPDATE Employee
    Route::put('/{emp_id}', [EmployeeController::class, 'update'])
        ->name('employees.update');

    // DELETE Employee
    Route::delete('/{emp_id}', [EmployeeController::class, 'destroy'])
        ->name('employees.destroy');
});

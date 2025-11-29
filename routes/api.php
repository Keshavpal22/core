<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionController;
use App\Models\Employee;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('login', [AuthController::class, 'login']);

// ALL AUTHENTICATED ROUTES (login required)
Route::middleware('auth:api')->group(function () {

    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('change-password', [AuthController::class, 'changePassword']);
    Route::post('update-profile', [AuthController::class, 'updateProfile']);

    // Users
    Route::middleware('can:manage_user')->group(function () {
        Route::get('/users', [UserController::class, 'list']);
        Route::post('/user/create', [UserController::class, 'store']);
        Route::get('/user/{id}', [UserController::class, 'profile']);
        Route::get('/user/delete/{id}', [UserController::class, 'delete']);
        Route::post('/user/change-role/{id}', [UserController::class, 'changeRole']);
    });

    // Roles
    Route::middleware('can:manage_role|manage_user')->group(function () {
        Route::get('/roles', [RolesController::class, 'list']);
        Route::post('/role/create', [RolesController::class, 'store']);
        Route::get('/role/{id}', [RolesController::class, 'show']);
        Route::get('/role/delete/{id}', [RolesController::class, 'delete']);
        Route::post('/role/change-permission/{id}', [RolesController::class, 'changePermissions']);
    });

    // Permissions
    Route::middleware('can:manage_permission|manage_user')->group(function () {
        Route::get('/permissions', [PermissionController::class, 'list']);
        Route::post('/permission/create', [PermissionController::class, 'store']);
        Route::get('/permission/{id}', [PermissionController::class, 'show']);
        Route::get('/permission/delete/{id}', [PermissionController::class, 'delete']);
    });
});

// GRID CONFIG — 100% PUBLIC (NO LOGIN REQUIRED)
Route::get('/grid-config/employees', function () {
    return response()->json([
        "title" => "Employee Performance Dashboard",
        "columns" => [
            ["field" => "emp_id", "headerName" => "ID", "width" => 80, "pinned" => "left"],
            ["field" => "name", "headerName" => "Name", "width" => 180],
            ["field" => "department", "headerName" => "Department", "enableRowGroup" => true, "enablePivot" => true],
            ["field" => "country", "headerName" => "Country", "enableRowGroup" => true, "enablePivot" => true],
            ["field" => "tasks", "headerName" => "Tasks", "aggFunc" => "sum", "enableValue" => true],
            ["field" => "hours", "headerName" => "Hours", "aggFunc" => "sum", "enableValue" => true],
            ["field" => "leaves", "headerName" => "Leaves", "aggFunc" => "sum", "enableValue" => true],
            [
                "field" => "efficiency",
                "headerName" => "Efficiency %",
                "aggFunc" => "avg",
                "enableValue" => true,
                "valueFormatter" => "value ? value.toFixed(1) + '%' : ''"
            ],
            [
                "field" => "attendance",
                "headerName" => "Attendance %",
                "aggFunc" => "avg",
                "enableValue" => true,
                "valueFormatter" => "value ? value.toFixed(1) + '%' : ''"
            ],
            ["field" => "rating", "headerName" => "Rating", "aggFunc" => "avg", "enableValue" => true],
            [
                "headerName" => "Action",
                "width" => 110,
                "pinned" => "right",
                "sortable" => false,
                "filter" => false,
                "cellRenderer" => "ActionRenderer"
            ]
        ],
        "data" => Employee::select('emp_id', 'name', 'department', 'country', 'tasks', 'hours', 'leaves', 'efficiency', 'attendance', 'rating')->get(),
        "gridSettings" => [
            "paginationPageSize" => 20,
            "pivotMode" => false
        ]
    ]);
})->name('api.grid.config.employees');

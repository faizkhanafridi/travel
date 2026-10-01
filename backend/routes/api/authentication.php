<?php

use App\Http\Controllers\Api\Authentication\AuthController;
use App\Http\Controllers\Api\Authentication\PermissionController;
use App\Http\Controllers\Api\Authentication\RoleController;
use Illuminate\Support\Facades\Route;


//  Authentication Module Routes


// ---------- Public ----------
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
    Route::post('set-password', [AuthController::class, 'setPassword']);
    Route::post('resend-setup-link', [AuthController::class, 'resendSetupLink']);
});

// ---------- Protected ----------
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('me',       [AuthController::class, 'me']);
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    // Admin-only,  agent creation
    Route::post('admin/agents', [AuthController::class, 'createAgent'])
        ->middleware('role:platform_admin');

    // Roles
    Route::prefix('roles')->group(function () {
        Route::get('/',                 [RoleController::class, 'index']);
        Route::post('/',                [RoleController::class, 'store']);
        Route::get('{id}',              [RoleController::class, 'show']);
        Route::put('{id}',              [RoleController::class, 'update']);
        Route::patch('{id}',            [RoleController::class, 'update']);
        Route::delete('{id}',           [RoleController::class, 'destroy']);
        Route::post('{id}/permissions', [RoleController::class, 'syncPermissions']);
    });

    // Permissions
    Route::prefix('permissions')->group(function () {
        Route::get('/',       [PermissionController::class, 'index']);
        Route::post('/',      [PermissionController::class, 'store']);
        Route::get('{id}',    [PermissionController::class, 'show']);
        Route::put('{id}',    [PermissionController::class, 'update']);
        Route::patch('{id}',  [PermissionController::class, 'update']);
        Route::delete('{id}', [PermissionController::class, 'destroy']);
    });
});
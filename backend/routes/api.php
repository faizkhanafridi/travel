<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok']));

require __DIR__ . '/api/authentication.php';
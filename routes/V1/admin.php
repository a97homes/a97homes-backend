<?php

use App\Http\Controllers\API\V1\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::apiResource('roles', RoleController::class);

<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserManagementController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('usermanagements', UserManagementController::class)
        ->except(['show'])
        ->names('usermanagement');
});

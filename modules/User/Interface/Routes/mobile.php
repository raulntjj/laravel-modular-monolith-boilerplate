<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\User\Interface\Http\Controllers\MobileUserController;

/*
|--------------------------------------------------------------------------
| User Module Mobile Routes
|--------------------------------------------------------------------------
*/

Route::prefix('users')->group(function () {
    // Listagem com cursor pagination, busca e ordenação
    Route::get('/', [MobileUserController::class, 'index']);
});

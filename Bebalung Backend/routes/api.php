<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes - Depot Sate Be Ba Lung (Scan Meja Gacoan System)
|--------------------------------------------------------------------------
*/

Route::get('/categories', [ApiController::class, 'getCategories']);
Route::get('/menus', [ApiController::class, 'getMenus']);
Route::post('/orders', [ApiController::class, 'createOrder']);
Route::get('/orders/{order_code}', [ApiController::class, 'getOrder']);
Route::post('/orders/{order_code}/pay', [ApiController::class, 'payOrder']);

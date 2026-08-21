<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Depot Sate Be Ba Lung (Scan Meja Gacoan System)
|--------------------------------------------------------------------------
*/

// Alur Pelanggan (Scan Meja)
Route::get('/', [OrderController::class, 'index'])->name('customer.menu');
Route::match(['get', 'post'], '/checkout', [OrderController::class, 'checkout'])->name('customer.checkout');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

// Chatbot Rekomendasi Menu Pintar (AI & DB Connected)
Route::post('/chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');
Route::get('/chatbot/recommendations', [ChatbotController::class, 'getRecommendations'])->name('chatbot.recommendations');

// Rute Pembayaran & Sukses
Route::get('/payment', [OrderController::class, 'latestPayment'])->name('order.payment.latest');
Route::get('/pembayaran', [OrderController::class, 'latestPayment'])->name('order.pembayaran');
Route::get('/order/{order_code}/payment', [OrderController::class, 'payment'])->name('order.payment');
Route::post('/order/{order_code}/payment', [OrderController::class, 'confirmPayment'])->name('order.payment.confirm');

Route::get('/success', [OrderController::class, 'latestSuccess'])->name('order.success.latest');
Route::get('/order/{order_code}/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/order/{order_code}/status', [OrderController::class, 'status'])->name('order.status');

// Panel Kasir / Admin / Dapur
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Fitur Khusus Kasir: Scan Barcode & POS
    Route::get('/scan', [AdminController::class, 'scanIndex'])->name('scan');
    Route::get('/orders/search', [AdminController::class, 'searchOrder'])->name('orders.search');
    Route::post('/orders/{id}/quick-pay', [AdminController::class, 'quickPay'])->name('orders.quick-pay');
    Route::get('/orders/{order_code}/receipt', [AdminController::class, 'receipt'])->name('orders.receipt');

    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // CRUD Menu
    Route::get('/menus', [AdminController::class, 'menusIndex'])->name('menus.index');
    Route::post('/menus', [AdminController::class, 'storeMenu'])->name('menus.store');
    Route::put('/menus/{id}', [AdminController::class, 'updateMenu'])->name('menus.update');
    Route::delete('/menus/{id}', [AdminController::class, 'destroyMenu'])->name('menus.destroy');
    Route::patch('/menus/{id}/toggle', [AdminController::class, 'toggleMenuAvailability'])->name('menus.toggle');
});

<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Depot Sate Be Ba Lung (Scan Meja Gacoan System)
|--------------------------------------------------------------------------
*/

// Autentikasi Rahasia Kasir & Kasir Utama (Admin)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
Route::post('/order/{order_code}/upload-proof', [OrderController::class, 'uploadPaymentProof'])->name('order.payment.upload-proof');

Route::get('/success', [OrderController::class, 'latestSuccess'])->name('order.success.latest');
Route::get('/order/{order_code}/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/order/{order_code}/status', [OrderController::class, 'status'])->name('order.status');

// Panel Rahasia Kasir / Admin / Dapur (Dilindungi Password & Sesi Login)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Fitur Khusus Kasir: Scan Barcode & POS
    Route::get('/scan', [AdminController::class, 'scanIndex'])->name('scan');
    Route::get('/orders/search', [AdminController::class, 'searchOrder'])->name('orders.search');
    Route::post('/orders/{id}/quick-pay', [AdminController::class, 'quickPay'])->name('orders.quick-pay');
    Route::post('/orders/{id}/confirm-cash', [AdminController::class, 'confirmCashPay'])->name('orders.confirm-cash');
    Route::post('/orders/{id}/upload-proof', [AdminController::class, 'uploadPaymentProof'])->name('orders.upload-proof');
    Route::get('/orders/{order_code}/receipt', [AdminController::class, 'receipt'])->name('orders.receipt');

    Route::post('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');
    
    // CRUD Menu
    Route::get('/menus', [AdminController::class, 'menusIndex'])->name('menus.index');
    Route::post('/menus', [AdminController::class, 'storeMenu'])->name('menus.store');
    Route::put('/menus/{id}', [AdminController::class, 'updateMenu'])->name('menus.update');
    Route::delete('/menus/{id}', [AdminController::class, 'destroyMenu'])->name('menus.destroy');
    Route::patch('/menus/{id}/toggle', [AdminController::class, 'toggleMenuAvailability'])->name('menus.toggle');

    // Catatan Aktivitas Pembayaran & Uang Masuk
    Route::get('/activity-logs', [AdminController::class, 'activityLogs'])->name('activity-logs');

    // Kelola & Cetak QR Meja Pelanggan (Meja 1, Meja 2, dst)
    Route::get('/tables', [AdminController::class, 'tablesIndex'])->name('tables.index');
    Route::get('/tables/{table_number}/print', [AdminController::class, 'printSingleTable'])->name('tables.print-single');
    Route::post('/tables/{table_number}/release', [AdminController::class, 'releaseTable'])->name('tables.release');

    // Pengaturan QRIS Pembayaran Toko
    Route::get('/settings/qris', [AdminController::class, 'qrisIndex'])->name('settings.qris');
    Route::post('/settings/qris', [AdminController::class, 'updateQris'])->name('settings.qris.update');

    // Edit Profil Akun, Email & Ganti Password Admin/Kasir
    Route::get('/profile', [AdminController::class, 'profileIndex'])->name('profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Panel Khusus Developer & Master Testing Tools
    Route::get('/developer', [AdminController::class, 'developerIndex'])->name('developer.index');
    Route::post('/developer/clear-orders', [AdminController::class, 'developerClearOrders'])->name('developer.clear-orders');
    Route::post('/developer/delete-order/{id}', [AdminController::class, 'developerDeleteOrder'])->name('developer.delete-order');
    Route::post('/developer/sync-db', [AdminController::class, 'developerSyncDb'])->name('developer.sync-db');
    Route::post('/developer/clear-cache', [AdminController::class, 'developerClearCache'])->name('developer.clear-cache');
    Route::post('/developer/update-settings', [AdminController::class, 'developerUpdateSettings'])->name('developer.update-settings');
});

<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Menu\MenuController;
use App\Http\Controllers\Admin\Ingredient\IngredientController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\Table\TableController;
use App\Http\Controllers\Admin\Reservation\ReservationController as AdminReservationController;

use App\Http\Controllers\Customer\Home\HomeController;
use App\Http\Controllers\Customer\Menu\MenuController as CustomerMenuController;
use App\Http\Controllers\Customer\Table\TableScanController;
use App\Http\Controllers\Customer\Theme\ThemeController;
use App\Http\Controllers\Customer\Profile\ProfileController;
use App\Http\Controllers\Customer\Auth\CustomerAuthController;
use App\Http\Controllers\Customer\Cart\CartController;
use App\Http\Controllers\Customer\Checkout\CheckoutController;
use App\Http\Controllers\Customer\Order\OrderStatusController;
use App\Http\Controllers\Customer\Order\OrderHistoryController;
use App\Http\Controllers\Customer\Order\OrderBillController;
use App\Http\Controllers\Customer\Notification\NotificationController;
use App\Http\Controllers\Customer\Reservation\ReservationController as CustomerReservationController;
use App\Http\Controllers\Customer\Reservation\ReservationHistoryController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// Routes untuk Area Pelanggan (Public)
Route::get('/', HomeController::class)->name('customer.home');
Route::get('/scan/{table}', TableScanController::class)->name('scan.qr');
Route::get('/menu', [CustomerMenuController::class, 'index'])->name('customer.menu');
Route::get('/theme', ThemeController::class)->name('customer.theme');
Route::get('/menu/{slug}', [CustomerMenuController::class, 'show'])->name('customer.menu.show');

// AI Chat Vue Page
Route::get('/ai-chat', function () {
    return inertia('Customer/AiChat/Index');
})->name('customer.aichat');

// AI Routes
Route::get('/api/ai/conversations', [\App\Http\Controllers\Api\AIChatController::class, 'getConversations'])->name('api.ai.conversations');
Route::get('/api/ai/conversations/{id}', [\App\Http\Controllers\Api\AIChatController::class, 'getMessages'])->name('api.ai.messages');
Route::post('/api/ai/ask', [\App\Http\Controllers\Api\AIChatController::class, 'ask'])->name('api.ai.ask');
Route::post('/api/menu/{menu:slug}/ask-ai', [\App\Http\Controllers\Api\MenuAIController::class, 'ask'])->name('api.menu.ask-ai');

// Add to cart tetap bisa tanpa login (session-based)
Route::post('/cart', [CartController::class, 'store'])->name('customer.cart.store');

// Midtrans Webhook — tanpa CSRF, tanpa auth (dipanggil server Midtrans)
Route::post('/api/midtrans/callback', [MidtransWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('midtrans.callback');

// Auth Pelanggan (Guest Only — belum login)
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
    Route::post('/masuk', [CustomerAuthController::class, 'login']);
    Route::get('/daftar', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/daftar', [CustomerAuthController::class, 'register']);
});

// Area Pelanggan yang butuh login
Route::middleware('auth')->group(function () {
    Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('customer.cart');
    Route::patch('/cart/{cartKey}', [CartController::class, 'update'])->name('customer.cart.update');
    Route::delete('/cart/{cartKey}', [CartController::class, 'destroy'])->name('customer.cart.destroy');

    // Reservation Routes
    Route::get('/reservasi', [CustomerReservationController::class, 'index'])->name('customer.reservation.index');
    Route::post('/reservasi/check', [CustomerReservationController::class, 'checkAvailability'])->name('customer.reservation.check');
    Route::post('/reservasi', [CustomerReservationController::class, 'store'])->name('customer.reservation.store');
    Route::post('/reservasi/{id}/reschedule', [CustomerReservationController::class, 'reschedule'])->name('customer.reservation.reschedule');
    Route::get('/riwayat-reservasi', ReservationHistoryController::class)->name('customer.reservation.history');

    // Order & Checkout Routes
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('customer.checkout');
    Route::get('/pesanan/{order}', OrderStatusController::class)->name('customer.order.status');
    Route::get('/pesanan/{order}/bill', OrderBillController::class)->name('customer.order.bill');
    Route::get('/riwayat-pesanan', OrderHistoryController::class)->name('customer.order.history');
    
    Route::post('/leave-table', function (\Illuminate\Http\Request $request) {
        $request->session()->forget('table_number');
        return back();
    })->name('customer.leave_table');
    
    Route::get('/profil', ProfileController::class)->name('customer.profil');
    
    Route::get('/notifications', [NotificationController::class, 'index'])->name('customer.notifications');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('customer.notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('customer.notifications.read');
});

// Auth Admin (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login']);
    
    Route::get('/admin/register', [AuthController::class, 'showRegister'])->name('admin.register');
    Route::post('/admin/register', [AuthController::class, 'register']);
});

// Rute untuk Admin yang sudah login (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/export', [DashboardController::class, 'exportPdf'])->name('admin.dashboard.export');

    Route::get('/admin/theme', function () {
        return inertia('Admin/Theme/Index');
    });

    // Admin Order Management
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders');
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');

    // Admin Reservation Management
    Route::get('/admin/reservations', [AdminReservationController::class, 'index'])->name('admin.reservations');
    Route::patch('/admin/reservations/{reservation}/status', [AdminReservationController::class, 'updateStatus'])->name('admin.reservations.update-status');

    // Admin Table QR
    Route::get('/admin/tables/qr', function () {
        return Inertia::render('Admin/Table/QrCodes');
    })->name('admin.tables.qr');

    Route::resource('/admin/categories', CategoryController::class)->except(['create', 'show', 'edit']);
    Route::resource('/admin/menus', MenuController::class);
    Route::resource('/admin/ingredients', IngredientController::class)->except(['create', 'show', 'edit']);
    Route::resource('/admin/tables', TableController::class)->except(['create', 'show', 'edit']);
});
<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Customer\ReservationController as CustomerReservationController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



// Routes untuk Area Pelanggan (Public)
Route::get('/', [CustomerController::class, 'index'])->name('customer.home');
Route::get('/scan/{table}', [CustomerController::class, 'scanQr'])->name('scan.qr');
Route::get('/menu', [CustomerController::class, 'menu'])->name('customer.menu');
Route::get('/theme', [CustomerController::class, 'theme'])->name('customer.theme');
Route::get('/menu/{slug}', [CustomerController::class, 'show'])->name('customer.menu.show');

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
    Route::get('/riwayat-reservasi', [CustomerReservationController::class, 'history'])->name('customer.reservation.history');

    // Order & Checkout Routes
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('customer.checkout');
    Route::get('/pesanan/{order}', [CheckoutController::class, 'status'])->name('customer.order.status');
    Route::get('/riwayat-pesanan', [CheckoutController::class, 'history'])->name('customer.order.history');
    Route::get('/notifications', [CheckoutController::class, 'notifications'])->name('customer.notifications');
    Route::post('/notifications/{id}/read', [CheckoutController::class, 'markNotificationAsRead'])->name('customer.notifications.read');
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
});
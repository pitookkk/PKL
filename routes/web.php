<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\BundleController as AdminBundleController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PcBuilderController;
use App\Http\Controllers\CommunityBuildController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\CustomerAssetController;
use App\Http\Controllers\WishlistController;

// Midtrans Callback
Route::post('/payments/midtrans-callback', [PaymentCallbackController::class, 'handle'])->name('midtrans.callback');

// --- Frontend Routes ---

// Welcome Page
Route::get('/', [HomeController::class, 'index'])->name('welcome');

Route::get('/tes-n8n', [ProductController::class, 'kirimKeN8n']);

// Public Asset View Route (for QR Codes)
Route::get('/assets/{customerAsset}', [CustomerAssetController::class, 'show'])->name('customer-assets.show');

// PC Builder
Route::get('/pc-builder', [PcBuilderController::class, 'index'])->name('pc-builder.index');
Route::get('/pc-builder/components', [PcBuilderController::class, 'getComponents'])->name('pc-builder.components');
Route::post('/pc-builder/add-all', [PcBuilderController::class, 'addAllToCart'])->name('pc-builder.add-all');

// Community Build Showcase
Route::get('/community-builds', [CommunityBuildController::class, 'index'])->name('community-builds.index');

// Product Bundling
Route::get('/bundles', [BundleController::class, 'index'])->name('bundles.index');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category:slug}', [ProductController::class, 'index'])->name('categories.show');


// --- Authenticated User Routes ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cartItemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout Route
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Review Route
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // "Pitocom Care" User Routes
    Route::get('/my-pitocom-care', [SupportController::class, 'index'])->name('pitocom-care.dashboard');
    Route::resource('support-tickets', SupportController::class)->except(['index']);
    Route::post('/customer-assets', [CustomerAssetController::class, 'store'])->name('customer-assets.store');

    // Shipping Routes (RajaOngkir)
    Route::get('/shipping/provinces', [ShippingController::class, 'provinces'])->name('shipping.provinces');
    Route::get('/shipping/cities/{provinceId}', [ShippingController::class, 'cities'])->name('shipping.cities');
    Route::post('/shipping/cost', [ShippingController::class, 'calculateCost'])->name('shipping.cost');

    // Wishlist Routes
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Community Build Upload
    Route::get('/community-builds/create', [CommunityBuildController::class, 'create'])->name('community-builds.create');
    Route::post('/community-builds', [CommunityBuildController::class, 'store'])->name('community-builds.store');

    // Voucher Application
    Route::post('/vouchers/apply', [VoucherController::class, 'apply'])->name('vouchers.apply');
    Route::delete('/vouchers/remove', [VoucherController::class, 'remove'])->name('vouchers.remove');

    // Invoice
    Route::get('/orders/{order}/invoice', [InvoiceController::class, 'download'])->name('orders.invoice');

    
});


// --- Admin Routes ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Product and Category Management
    Route::resource('products', AdminProductController::class);
    Route::resource('categories', AdminCategoryController::class);

    // Flash Sale Management
    Route::get('/flash-sales', [FlashSaleController::class, 'index'])->name('flash-sales.index');
    Route::post('/flash-sales/{product}', [FlashSaleController::class, 'store'])->name('flash-sales.store');
    Route::delete('/flash-sales/{product}', [FlashSaleController::class, 'destroy'])->name('flash-sales.destroy');

    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    // Review Management
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // "Pitocom Care" Admin CRM Routes
    Route::get('crm/broadcast', [CrmController::class, 'showBroadcastForm'])->name('crm.broadcast.form');
    Route::post('crm/broadcast', [CrmController::class, 'sendBroadcast'])->name('crm.broadcast.send');
    Route::get('crm/customer/{user}', [CrmController::class, 'customer360'])->name('crm.customer360');
    Route::post('crm/customer/{user}/log', [CrmController::class, 'logInteraction'])->name('crm.logInteraction');
    Route::get('crm/tickets-board', [CrmController::class, 'ticketsBoard'])->name('crm.tickets-board');
    Route::put('crm/tickets/{ticket}', [CrmController::class, 'updateTicketStatus'])->name('crm.tickets.update');
    Route::resource('crm', CrmController::class)->parameters(['crm' => 'user']);

    // User Management
    Route::resource('users', AdminUserController::class);

    // Bundle Management
    Route::resource('bundles', AdminBundleController::class);

    // Voucher Management
    Route::resource('vouchers', AdminVoucherController::class);

    // Community Build Moderation
    Route::get('community-builds', [CommunityBuildController::class, 'adminIndex'])->name('community-builds.index');
    Route::post('community-builds/{build}/approve', [CommunityBuildController::class, 'approve'])->name('community-builds.approve');
    Route::post('community-builds/{build}/reject', [CommunityBuildController::class, 'reject'])->name('community-builds.reject');
});

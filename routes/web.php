<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DineInController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Owner\AuthController as OwnerAuthController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Owner\ReportController as OwnerReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============ LOCATION ROUTES (TARUH DI PALING ATAS) ============
Route::prefix('locations')->group(function () {
    Route::get('/provinces', [LocationController::class, 'getProvinces'])->name('locations.provinces');
    Route::get('/regencies/{provinceId}', [LocationController::class, 'getRegencies'])->name('locations.regencies');
    Route::get('/districts/{regencyId}', [LocationController::class, 'getDistricts'])->name('locations.districts');
    Route::get('/villages/{districtId}', [LocationController::class, 'getVillages'])->name('locations.villages');
    Route::get('/shipping', [LocationController::class, 'calculateShipping'])->name('locations.shipping');
});


// ==================== SHOP ROUTES (Customer - Public) ====================
Route::get('/', [ProductController::class, 'home'])->name('shop.index');
Route::get('/products', [ProductController::class, 'index'])->name('shop.products');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// ==================== CART AJAX ROUTES ====================
Route::prefix('cart')->group(function () {
    Route::get('/count', [CartController::class, 'getCount'])->name('cart.count');
    Route::get('/mini', [CartController::class, 'getMiniCart'])->name('cart.mini');
});

// ==================== CHECKOUT ROUTE (Harus Login Customer) ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
});

// ==================== ORDER ROUTES ====================
Route::post('/order/process', [OrderController::class, 'processOrder'])->name('order.process');
Route::get('/order/success', [OrderController::class, 'orderSuccess'])->name('order.success');
Route::get('/order/track', [OrderController::class, 'trackOrder'])->name('order.track');
Route::get('/order/detail/{id}', [OrderController::class, 'customerOrderDetail'])->name('order.detail');
Route::get('/order/check-status', [OrderController::class, 'checkOrderStatus'])->name('order.check-status');
Route::post('/order/confirm', [OrderController::class, 'confirmOrderReceived'])->name('order.confirm');

Route::get('/order/success', [OrderController::class, 'orderSuccess'])->name('order.success');

// ==================== DINE IN ROUTES ====================
Route::post('/dine-in/order', [DineInController::class, 'store'])->name('dinein.store');
Route::get('/product/{id}/dinein', [ProductController::class, 'showDineInForm'])->name('product.dinein');


// ==================== ADMIN DINE IN ROUTES ====================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Dine In Management
    Route::get('/dinein', [DineInController::class, 'adminIndex'])->name('dinein');
    Route::patch('/dinein/{id}/status', [DineInController::class, 'updateStatus'])->name('dinein.status');
    Route::patch('/dinein/{id}/payment', [DineInController::class, 'updatePayment'])->name('dinein.payment');
    Route::post('/dinein/{id}/mark-paid', [DineInController::class, 'markAsPaid'])->name('dinein.mark-paid');
    Route::delete('/dinein/{id}', [DineInController::class, 'destroy'])->name('dinein.destroy');
    
    // Dine In Notifications
    Route::get('/dinein/check-new', [DineInController::class, 'checkNewOrders'])->name('dinein.check-new');
    Route::post('/dinein/mark-viewed', [DineInController::class, 'markOrderAsViewed'])->name('dinein.mark-viewed');
});

// ==================== ADMIN REPORT ROUTES ====================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [AdminReportController::class, 'create'])->name('reports.create');
    Route::post('/reports/generate', [AdminReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/{id}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{id}/approve', [AdminReportController::class, 'approve'])->name('reports.approve');
    Route::delete('/reports/{id}', [AdminReportController::class, 'destroy'])->name('reports.destroy');
    Route::get('/reports/{id}/export', [AdminReportController::class, 'export'])->name('reports.export');
    Route::post('/reports/{id}/mark-read', [AdminReportController::class, 'markAsRead'])->name('reports.mark-read');
});




// ==================== ADMIN AUTH ROUTES ====================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==================== ADMIN ROUTES (Harus Login & Role Admin) ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Product Management
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Update Stock
    Route::patch('/products/{id}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');

    // Order Management (Delivery)
    Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
    
    // Delivery Status Update
    Route::patch('/orders/{id}/delivery', [OrderController::class, 'updateDeliveryStatus'])->name('orders.delivery');
    
    // Order Notifications
    Route::get('/orders/check-new', [OrderController::class, 'checkNewOrders'])->name('orders.check-new');
    Route::post('/orders/mark-viewed', [OrderController::class, 'markOrderAsViewed'])->name('orders.mark-viewed');

    // Dine In Admin Routes
    Route::get('/dinein', [DineInController::class, 'adminIndex'])->name('dinein');
    Route::patch('/dinein/{id}/status', [DineInController::class, 'updateStatus'])->name('dinein.status');
    Route::patch('/dinein/{id}/payment', [DineInController::class, 'updatePayment'])->name('dinein.payment');
    Route::delete('/dinein/{id}', [DineInController::class, 'destroy'])->name('dinein.destroy');
    
    // Dine In Notifications
    Route::get('/dinein/check-new', [DineInController::class, 'checkNewOrders'])->name('dinein.check-new');
    Route::post('/dinein/mark-viewed', [DineInController::class, 'markOrderAsViewed'])->name('dinein.mark-viewed');
    
    // ==================== ADMIN CHAT ROUTES (DIPERBAIKI) ====================
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'adminIndex'])->name('index');
        Route::get('/{userId}', [ChatController::class, 'adminShow'])->name('show');
        Route::post('/reply', [ChatController::class, 'adminReply'])->name('reply');
        Route::get('/new-messages', [ChatController::class, 'getNewMessages'])->name('new');
        Route::get('/unread/count', [ChatController::class, 'getUnreadCount'])->name('unread');
    });
    
    // Admin Review Management
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
});

// ==================== CUSTOMER ROUTES (Harus Login Customer) ====================
Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
    Route::get('/customer/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/customer/profile', [CustomerController::class, 'profile'])->name('customer.profile');
    Route::post('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    Route::put('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update.put');
    Route::post('/customer/password/update', [CustomerController::class, 'updatePassword'])->name('customer.password.update');
    
    // ============ CUSTOMER CHAT ROUTES ============
    Route::prefix('customer/chat')->name('customer.chat.')->group(function () {
        Route::get('/', [ChatController::class, 'customerIndex'])->name('index');
        Route::post('/send', [ChatController::class, 'customerSend'])->name('send');
        Route::get('/new', [ChatController::class, 'getNewMessages'])->name('new');
        Route::get('/unread/count', [ChatController::class, 'getUnreadCount'])->name('unread');
    });
    
    // Customer Reviews
    Route::get('/customer/reviews', [ReviewController::class, 'myReviews'])->name('customer.reviews');
    Route::get('/product/{product}/review', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/review/{review}/edit', [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/{review}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');
});

// ==================== PROFILE ROUTES (Breeze) ====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== DEBUG ROUTES ====================
Route::get('/check-stock/{id}', function($id) {
    $product = App\Models\Product::find($id);
    if ($product) {
        return response()->json([
            'success' => true,
            'id' => $product->id,
            'name' => $product->name,
            'stock' => $product->stock
        ]);
    }
    return response()->json(['success' => false, 'message' => 'Product not found'], 404);
});

// ==================== OFFLINE ROUTE ====================
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// ==================== ORDER DELETE ROUTE (PERBAIKAN) ====================
Route::delete('/admin/orders/{order}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

// ==================== LOCATION ROUTES ====================
Route::get('/locations', [App\Http\Controllers\BranchController::class, 'index'])->name('locations.index');
Route::get('/locations/{slug}', [App\Http\Controllers\BranchController::class, 'show'])->name('locations.show');
Route::get('/api/branches/nearby', [App\Http\Controllers\BranchController::class, 'getNearbyBranches'])->name('api.branches.nearby');

// ==================== DISCOUNT ROUTES ====================
Route::post('/discount/apply', [App\Http\Controllers\DiscountController::class, 'applyDiscount'])->name('discount.apply');
Route::delete('/discount/remove', [App\Http\Controllers\DiscountController::class, 'removeDiscount'])->name('discount.remove');
Route::get('/discounts/active', [App\Http\Controllers\DiscountController::class, 'getActiveDiscounts'])->name('discounts.active');
Route::get('/cart/get-with-discount', [CartController::class, 'getCartWithDiscount'])->name('cart.get-with-discount');

// ==================== TENTANG KAMI ROUTE ====================
Route::get('/tentang-kami', [App\Http\Controllers\AboutController::class, 'index'])->name('about.index');


// ==================== OWNER AUTH ROUTES ====================
Route::prefix('owner')->name('owner.')->group(function () {
    Route::get('/login', [OwnerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [OwnerAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [OwnerAuthController::class, 'logout'])->name('logout');
});

// ==================== OWNER DASHBOARD ROUTES ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/export', [DashboardController::class, 'exportReport'])->name('export');
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/products', [DashboardController::class, 'products'])->name('products');
    Route::get('/dinein', [DashboardController::class, 'dinein'])->name('dinein');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
});

// ==================== OWNER DASHBOARD ROUTES (LENGKAP) ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Export Laporan
    Route::get('/export', [DashboardController::class, 'exportReport'])->name('export');
    
    // Manajemen Pesanan
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [DashboardController::class, 'orderDetail'])->name('order.detail');
    
    // Manajemen Produk
    Route::get('/products', [DashboardController::class, 'products'])->name('products');
    Route::get('/products/create', [DashboardController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [DashboardController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{id}', [DashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [DashboardController::class, 'deleteProduct'])->name('products.delete');
    
    // Manajemen Stok
    Route::get('/stock', [DashboardController::class, 'stock'])->name('stock');
    Route::post('/stock/add', [DashboardController::class, 'addStock'])->name('stock.add');
    
    // Manajemen Karyawan
    Route::get('/staff', [DashboardController::class, 'staff'])->name('staff');
    Route::post('/staff', [DashboardController::class, 'storeStaff'])->name('staff.store');
    Route::put('/staff/{id}', [DashboardController::class, 'updateStaff'])->name('staff.update');
    Route::delete('/staff/{id}', [DashboardController::class, 'deleteStaff'])->name('staff.delete');
    
    // Manajemen Promo
    Route::get('/promos', [DashboardController::class, 'promos'])->name('promos');
    Route::post('/promos', [DashboardController::class, 'storePromo'])->name('promos.store');
    Route::delete('/promos/{id}', [DashboardController::class, 'deletePromo'])->name('promos.delete');
    
    // Laporan Keuangan
    Route::get('/financial', [DashboardController::class, 'financial'])->name('financial');
    
    // Dine In
    Route::get('/dinein', [DashboardController::class, 'dinein'])->name('dinein');
    
    // Laporan Lainnya
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
});

// ==================== OWNER ROUTES (EXPORT) ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Owner\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/export', [App\Http\Controllers\Owner\DashboardController::class, 'exportReport'])->name('export');
});

// ==================== OWNER STAFF ROUTES ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Staff Management
    Route::get('/staff', [DashboardController::class, 'staff'])->name('staff');
    Route::post('/staff', [DashboardController::class, 'storeStaff'])->name('staff.store');
    Route::put('/staff/{id}', [DashboardController::class, 'updateStaff'])->name('staff.update');
    Route::delete('/staff/{id}', [DashboardController::class, 'deleteStaff'])->name('staff.delete');
});

// ==================== OWNER SALARY ROUTES ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Manajemen Gaji
    Route::get('/salaries', [DashboardController::class, 'salaries'])->name('salaries');
    Route::post('/salaries', [DashboardController::class, 'storeSalary'])->name('salaries.store');
    Route::put('/salaries/{id}/status', [DashboardController::class, 'updateSalaryStatus'])->name('salaries.update-status');
    Route::delete('/salaries/{id}', [DashboardController::class, 'deleteSalary'])->name('salaries.delete');
    Route::get('/salaries/export', [DashboardController::class, 'exportSalaryReport'])->name('salaries.export');
});

// ==================== OWNER ORDER ROUTES ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Semua Pesanan (Delivery + Dine In)
    Route::get('/orders', [DashboardController::class, 'orders'])->name('orders');
    
    // Detail Pesanan (bisa Delivery atau Dine In)
    Route::get('/orders/{id}', [DashboardController::class, 'orderDetail'])->name('order.detail');
});

// ==================== OWNER RAW MATERIALS ROUTES ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Bahan Baku
    Route::get('/raw-materials', [DashboardController::class, 'rawMaterials'])->name('raw-materials');
    Route::post('/raw-materials', [DashboardController::class, 'storeRawMaterial'])->name('raw-materials.store');
    Route::put('/raw-materials/{id}', [DashboardController::class, 'updateRawMaterial'])->name('raw-materials.update');
    Route::delete('/raw-materials/{id}', [DashboardController::class, 'deleteRawMaterial'])->name('raw-materials.delete');
    
    // Purchase Orders
    Route::get('/purchase-orders', [DashboardController::class, 'purchaseOrders'])->name('purchase-orders');
    Route::post('/purchase-orders', [DashboardController::class, 'storePurchaseOrder'])->name('purchase-orders.store');
    Route::put('/purchase-orders/{id}/status', [DashboardController::class, 'updatePurchaseOrderStatus'])->name('purchase-orders.status');
    Route::delete('/purchase-orders/{id}', [DashboardController::class, 'deletePurchaseOrder'])->name('purchase-orders.delete');
});

// ==================== OWNER RAW MATERIALS ROUTES (EDIT) ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Bahan Baku
    Route::get('/raw-materials', [DashboardController::class, 'rawMaterials'])->name('raw-materials');
    Route::get('/raw-materials/{id}/edit', [DashboardController::class, 'getRawMaterial'])->name('raw-materials.edit');
    Route::post('/raw-materials', [DashboardController::class, 'storeRawMaterial'])->name('raw-materials.store');
    Route::put('/raw-materials/{id}', [DashboardController::class, 'updateRawMaterial'])->name('raw-materials.update');
    Route::delete('/raw-materials/{id}', [DashboardController::class, 'deleteRawMaterial'])->name('raw-materials.delete');
});

// ==================== OWNER REPORT ROUTES (DIPERBAIKI) ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Laporan untuk Owner
    Route::get('/reports', [OwnerReportController::class, 'index'])->name('reports');
    Route::get('/reports/{id}', [OwnerReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/{id}/approve', [OwnerReportController::class, 'approve'])->name('reports.approve'); // ← Tambahkan ini
    
    // Notifikasi (menggunakan AdminReportController)
    Route::get('/reports/unread-count', [AdminReportController::class, 'getUnreadCount'])->name('reports.unread-count');
    Route::get('/reports/unread', [AdminReportController::class, 'getUnreadReports'])->name('reports.unread');
    Route::post('/reports/{id}/mark-read', [AdminReportController::class, 'markAsRead'])->name('reports.mark-read');
});

// ==================== OWNER PROMO ROUTES ====================
Route::prefix('owner')->middleware(['auth', 'owner'])->name('owner.')->group(function () {
    // Promo Management
    Route::get('/promos', [DashboardController::class, 'promos'])->name('promos');
    Route::get('/promos/{id}/data', [DashboardController::class, 'getPromo'])->name('promos.get');
    Route::put('/promos/{id}', [DashboardController::class, 'updatePromo'])->name('promos.update');
    Route::delete('/promos/{id}', [DashboardController::class, 'deletePromo'])->name('promos.delete');
    Route::post('/promos', [DashboardController::class, 'storePromo'])->name('promos.store');
});

// ==================== CUSTOMER SERVICE ROUTES ====================
Route::get('/customer-service', [App\Http\Controllers\CustomerServiceController::class, 'index'])->name('customer.service');
Route::post('/customer-service/send', [App\Http\Controllers\CustomerServiceController::class, 'sendMessage'])->name('customer.service.send');
Route::get('/customer-service/new', [App\Http\Controllers\CustomerServiceController::class, 'getNewMessages'])->name('customer.service.new');
Route::get('/customer-service/contact', [App\Http\Controllers\CustomerServiceController::class, 'getContactInfo'])->name('customer.service.contact');

// Premium Quality
Route::get('/premium-quality', [App\Http\Controllers\ProductController::class, 'premiumQuality'])->name('premium.quality');

// ==================== FREE SHIPPING ROUTES ====================
Route::get('/free-shipping', [App\Http\Controllers\FreeShippingController::class, 'index'])->name('free.shipping');
Route::get('/free-shipping/check', [App\Http\Controllers\FreeShippingController::class, 'checkStatus'])->name('free.shipping.check');
Route::post('/free-shipping/apply', [App\Http\Controllers\FreeShippingController::class, 'applyFreeShipping'])->name('free.shipping.apply');
Route::get('/free-shipping/detail', [App\Http\Controllers\FreeShippingController::class, 'getDetail'])->name('free.shipping.detail');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

// ==================== BREEZE AUTH ROUTES ====================
require __DIR__.'/auth.php';
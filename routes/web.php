<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\DealController;
use App\Http\Controllers\Owner\ExpenseController;
use App\Http\Controllers\Owner\ExpenseCategoryController;
use App\Http\Controllers\Owner\ManualSaleController;
use App\Http\Controllers\Owner\ItemController as OwnerItemController;
use App\Http\Controllers\Owner\OrderController as OwnerOrderController;
use App\Http\Controllers\Owner\CategoryController as OwnerCategoryController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureCustomer;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/locale/{locale}', [HomeController::class, 'switchLocale'])->name('locale.switch');
Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [HomeController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/cart/update', [HomeController::class, 'updateCart'])->name('cart.update');

Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
Route::get('/checkout/thank-you/{order}', [CheckoutController::class, 'thankyou'])->name('checkout.thankyou');
Route::get('/order-success/{order}', [CheckoutController::class, 'thankyou'])->name('order.success');
Route::get('/complaints', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:owner,admin,super_admin'])->name('dashboard');

    // Unified profile for all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Owner routes (only owner role)
    Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner,admin,super_admin'])->group(function () {
        Route::get('/manage-owners', [\App\Http\Controllers\Owner\OwnerManagementController::class, 'index'])->name('manage_owners.index');
        Route::post('/manage-owners', [\App\Http\Controllers\Owner\OwnerManagementController::class, 'store'])->name('manage_owners.store');
        Route::delete('/manage-owners/{id}', [\App\Http\Controllers\Owner\OwnerManagementController::class, 'destroy'])->name('manage_owners.destroy');

        Route::get('/orders', [OwnerOrderController::class, 'index'])->name('orders.index');
        Route::post('/orders/{order}/status', [OwnerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/quick-sale', [ManualSaleController::class, 'store'])->name('quick-sale.store');
        Route::put('/quick-sale/{sale}', [ManualSaleController::class, 'update'])->name('quick-sale.update');
        Route::delete('/quick-sale/{sale}', [ManualSaleController::class, 'destroy'])->name('quick-sale.destroy');
        Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
        Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
        Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->name('expenses.update');
        Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
        Route::post('/expense-categories', [ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
        Route::put('/expense-categories/{id}', [ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
        Route::delete('/expense-categories/{id}', [ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');

        Route::resource('items', OwnerItemController::class)->names('items');
        Route::resource('deals', DealController::class)->names('deals');
        Route::resource('categories', OwnerCategoryController::class)->only(['index', 'store', 'update', 'destroy'])->names('categories');
        Route::post('/items/{item}/toggle-stock', [OwnerItemController::class, 'toggleStock'])->name('items.toggleStock');
    });

    // Admin / Super Admin routes
    Route::prefix('admin')->name('admin.')->middleware(EnsureAdmin::class)->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/reset-dashboard', [DashboardController::class, 'resetData'])->name('reset-dashboard');
    });

    // Customer routes
    Route::prefix('')->middleware(EnsureCustomer::class)->group(function () {
        Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('customer.orders.index');
        Route::get('/my-orders/{order}', [CustomerOrderController::class, 'show'])->name('customer.orders.show');
        Route::get('/my-orders/{order}/status', [CustomerOrderController::class, 'status'])->name('customer.orders.status');
    });
});

require __DIR__.'/auth.php';

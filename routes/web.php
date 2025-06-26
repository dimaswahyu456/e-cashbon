<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CashbonController;
use App\Http\Controllers\CashbonDetailController;
use App\Http\Controllers\ApprovedController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index']);
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth', [AuthController::class, 'auth']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/setting', [AuthController::class, 'setting']);
Route::post('/edituser', [AuthController::class, 'edituser']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/settings', [SettingsController::class, 'showSettingsForm'])->name('settings.form');
Route::post('/settings', [SettingsController::class, 'saveSettings'])->name('settings.save');
Route::get('/convert-and-store-timestamp', [SettingsController::class, ' convertAndStoreTimestamp'])->name('settings.convert');

Route::get('user', [UserController::class, 'index'])->name('user.list');
Route::get('user/show/{id}', [UserController::class, 'show'])->name('user.show');
Route::get('user/add', [UserController::class, 'create'])->name('user.create');
Route::post('user/store', [UserController::class, 'store'])->name('user.add');
Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::get('user/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');


Route::get('warehouse', [WarehouseController::class, 'index'])->name('warehouse.list');
Route::get('warehouse/show/{id}', [WarehouseController::class, 'show'])->name('warehouse.show');
Route::get('warehouse/add', [WarehouseController::class, 'create'])->name('warehouse.create');
Route::post('warehouse/store', [WarehouseController::class, 'store'])->name('warehouse.add');
Route::get('warehouse/edit/{id}', [WarehouseController::class, 'edit'])->name('warehouse.edit');
Route::post('warehouse/update/{id}', [WarehouseController::class, 'update'])->name('warehouse.update');
Route::get('warehouse/delete/{id}', [WarehouseController::class, 'destroy'])->name('warehouse.destroy');

Route::get('supplier', [VendorController::class, 'index'])->name('supplier.list');
Route::get('supplier/show/{id}', [VendorController::class, 'show'])->name('supplier.show');

Route::get('cashbon', [CashbonController::class, 'index'])->name('cashbon.list');
Route::get('cashbon/show/{id}', [CashbonController::class, 'show'])->name('cashbon.show');
Route::get('done', [CashbonController::class, 'listDone'])->name('done.list');
Route::get('done/show/{id}', [CashbonController::class, 'showDone'])->name('done.show');
Route::get('/print-lpj/{doc_id}', [CashbonController::class, 'printLpjReport'])->name('lpj.print');

Route::get('detail', [CashbonDetailController::class, 'index'])->name('detail.list');
Route::get('detail/show/{id}', [CashbonDetailController::class, 'show'])->name('detail.show');
Route::get('detail/add', [CashbonDetailController::class, 'create'])->name('detail.create');
Route::post('detail/store', [CashbonDetailController::class, 'store'])->name('detail.add');
Route::get('detail/edit/{id}', [CashbonDetailController::class, 'edit'])->name('detail.edit');
Route::post('detail/update/{id}', [CashbonDetailController::class, 'update'])->name('detail.update');
Route::get('detail/delete/{id}', [CashbonDetailController::class, 'destroy'])->name('detail.destroy');
Route::post('detail/delete-image', [CashbonDetailController::class, 'deleteImage'])->name('detail.deleteImage');
Route::post('cashbon/send_response', [CashbonDetailController::class, 'sendResponse'])->name('cashbon.send_response');

Route::get('approved', [ApprovedController::class, 'index'])->name('approved.list');
Route::get('approved/show/{id}', [ApprovedController::class, 'show'])->name('approved.show');
Route::get('approved/add', [ApprovedController::class, 'create'])->name('approved.create');
Route::post('approved/store', [ApprovedController::class, 'store'])->name('approved.add');
Route::get('approved/edit/{id}', [ApprovedController::class, 'edit'])->name('approved.edit');
Route::post('approved/update/{id}', [ApprovedController::class, 'update'])->name('approved.update');
Route::get('approved/delete/{id}', [ApprovedController::class, 'destroy'])->name('approved.destroy');

Route::get('acc', [ApprovedController::class, 'cashbonAcc'])->name('acc.list');
Route::get('acc/show/{id}', [ApprovedController::class, 'showAcc'])->name('acc.show');

//Sidebar
Route::get('layouts/sidebar', [ApprovedController::class, 'notifcount'])->name('notif');
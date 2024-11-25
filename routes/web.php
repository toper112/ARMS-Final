<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('roles', AdminRoleController::class)->names('roles');
    Route::post('/roles/{role}/permissions', [AdminController::class, 'givePermission'])->name('roles.permissions');
    Route::resource('permissions', AdminPermissionController::class)->names('permissions');
    Route::post('/permissions/{permission}/roles', [AdminPermissionController::class, 'assignRole'])->name('permissions.roles');


    //users routing
    Route::resource('users', UserController::class)->names('users');
    Route::get('/users/{user}/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::post('/users/{user}/roles', [UserController::class, 'assignRole'])->name('users.roles');
    Route::delete('/users/{user}/roles/{role}', [UserController::class, 'removeRole'])->name('users.roles.remove');
    Route::post('/users/{user}/permissions', [UserController::class, 'givePermission'])->name('users.permissions');
    Route::delete('/users/{user}/permissions/{permission}', [UserController::class, 'revokePermission'])->name('users.permissions.revoke');

    //Scanners routing
    Route::get('/scanner', [ScannerController::class, 'index'])->name('scanner.index');
    Route::post('/scanner/process', [ScannerController::class, 'process'])->name('scanner.process');

    // Corrected routes for exporting and importing users
    Route::get('/users/export', [UserController::class, 'exportUsers'])->name('users.export');
    Route::post('/users/import', [UserController::class, 'importUsers'])->name('users.import');
});

Route::middleware(['auth', 'verified'])->group(function (){
    //events routing
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::resource('events', EventController::class)->names('events');

    //suggestions
    Route::get('/suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');
    Route::resource('suggestions', SuggestionController::class)->names('suggestions');


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

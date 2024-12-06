<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AttendanceRecordController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [AdminController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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
    Route::get('/scanner/{id}', [ScannerController::class, 'index'])->name('scanner.index');
    Route::post('/scanner', [ScannerController::class, 'store'])->name('scanner.store');

    // Corrected routes for exporting and importing users
    // Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import');
    Route::get('/admin/users/export', [UserController::class, 'export'])->name('users.export');

    // Route to update the remarks
    Route::patch('/events/{id}/update-remarks', [EventController::class, 'updateRemarks'])->name('events.updateRemarks');


    //resetpassword
    // Route::patch('admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::patch('admin/users/{user}/reset-password', [UserController::class, 'resetPassword'])
    ->name('users.reset-password');











    //attendance para sa admin
    Route::get('/attendance', [AttendanceRecordController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/{user}/paid', [AttendanceRecordController::class, 'paid'])->name('attendance.paid');
    Route::resource('attendance', AttendanceRecordController::class)->names('attendance');

    //suggestions
    Route::get('/suggestions', [SuggestionController::class, 'index'])->name('suggestions.index');
    Route::get('/suggestions/{suggestion}', [SuggestionController::class, 'show'])->name('suggestions.show');
    // Route::resource('suggestions', SuggestionController::class)->names('suggestions');

});

// Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
//     // Other admin routes...
//     Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
// });


//officer and  admin roles
Route::middleware(['auth', 'role:admin|officer'])->prefix('admin_officer')->name('admin_officer.')->group(function () {
        //Scanners routing
    Route::get('/scanner/{id}', [ScannerController::class, 'index'])->name('scanner.index');
    Route::post('/scanner', [ScannerController::class, 'store'])->name('scanner.store');

});


Route::middleware(['auth', 'verified'])->group(function (){
    //events routing
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::resource('events', EventController::class)->names('events');

    //store
    Route::post('/suggestions', [SuggestionController::class, 'store'])->name('suggestions.store');
    // Route::resource('suggestions', SuggestionController::class)->names('suggestions');

    //attendance
    Route::get('/attendance', [AttendanceRecordController::class, 'index'])->name('attendance.index');
    Route::resource('attendance', AttendanceRecordController::class)->names('attendance');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

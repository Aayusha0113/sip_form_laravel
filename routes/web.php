<?php

use App\Http\Controllers\ApplicationFormController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public
Route::get('/', fn () => redirect()->route('form.index'));
Route::get('/form', [ApplicationFormController::class, 'index'])->name('form.index');
Route::post('/form/submit', [ApplicationFormController::class, 'submit'])->name('form.submit');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected: admin or user (shared SIP listing)
Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/{sip}', [DashboardController::class, 'show'])->whereNumber('sip')->name('dashboard.show');
});


// Admin-only dashboard & user management
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');
    Route::match(['get', 'post'], '/client-apps', [DashboardController::class, 'clientApps'])->name('client_apps');
   
// AJAX delete company (admin)
Route::post('/companies/delete', [DashboardController::class, 'deleteCompany'])->name('companies.delete');
    // Admin activities page
    Route::get('/activities', [DashboardController::class, 'activities'])->name('activities');
});

//for user button in dashboard
//Route::get('/users', [DashboardController::class, 'user'])->name('users.users_listing');
// User dashboard with cards view
//  Route::get('/user', [DashboardController::class, 'user'])->name('user');
// Route::get('/user/{id}/edit_user', [DashboardController::class, 'edit'])->name('edit_user');

// User listing (cards view)
 Route::get('/dashboard/user', [DashboardController::class, 'user'])->name('dashboard.user')->middleware('auth');

// Edit user form
 Route::get('/users/{id}/edit', [DashboardController::class, 'edit'])->name('users.edit');

// Update user (when Save Changes is clicked)
Route::put('/users/{id}', [DashboardController::class, 'update'])->name('users.update');

// Delete user (when Delete User is clicked)
Route::delete('/users/{id}', [DashboardController::class, 'destroy'])->name('users.destroy');


// User-only dashboard with role-based permissions
Route::middleware(['auth', 'role:user'])->get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');


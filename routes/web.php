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
Route::middleware(['auth', 'role:user'])->match(['get', 'post'], '/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

// User update client applications
Route::middleware(['auth', 'role:user'])->match(['get', 'post'], 'user/client-apps', [DashboardController::class, 'updateclientApps'])->name('user.update_client_apps');

// User-only view client applications
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/view-clients', [DashboardController::class, 'viewClientApps'])->name('user.view_client_apps');

    // View single application
    Route::get('/user/view-client/{id}', [DashboardController::class, 'viewApplication'])->name('applications.view');

    // Estimate for an application
    Route::get('/user/estimate-client/{id}', [DashboardController::class, 'estimateApplication'])->name('applications.estimate');

    // Letter for an application
    Route::get('/user/letter-client/{id}', [DashboardController::class, 'letterApplication'])->name('applications.letter');
});
// for upload docs button in dashboard  
Route::post('/dashboard/upload-docs', [DashboardController::class, 'uploadDocs'])
    ->name('dashboard.upload_docs')
    ->middleware(['auth']);


    // View SIP docs
// Route::get('/sip/view-only', function () {
//     return view('sip.view_only');
// })->name('sip.view_only')->middleware('auth');

// Upload docs
// Route::get('/dashboard/upload-docs', [DashboardController::class, 'uploadDocs'])
//     ->name('dashboard.upload_docs')
//     ->middleware('auth');

// Admin / update client apps
// Route::get('/admin/client-apps', [AdminController::class, 'clientApps'])
//     ->name('admin.client_apps')
//     ->middleware(['auth', 'role:admin']);

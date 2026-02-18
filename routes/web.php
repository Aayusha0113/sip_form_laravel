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

// Admin-only routes (legacy)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/activities', [DashboardController::class, 'activities'])->name('admin.activities');
    Route::match(['get', 'post'], '/admin/client-apps', [DashboardController::class, 'clientApps'])->name('admin.client_apps');

});

// User listing (cards view)
 Route::get('/dashboard/user', [DashboardController::class, 'user'])->name('dashboard.user')->middleware('auth');
// Edit user form
 Route::get('/users/{id}/edit', [DashboardController::class, 'edit'])->name('users.edit');
// Update user (when Save Changes is clicked)
Route::put('/users/{id}', [DashboardController::class, 'update'])->name('users.update');
// Delete user (when Delete User is clicked)
Route::delete('/users/{id}', [DashboardController::class, 'destroy'])->name('users.destroy');



// Admin-only dashboard & user// Admin-only routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/activities', [DashboardController::class, 'activities'])->name('activities');
    
    // client applications management routes(SIP Approve)
    // Route::get('/client-apps', [DashboardController::class, 'clientApps'])->name('client_apps');
    // Admin document routes

        Route::match(['get', 'post'], '/view-documents', [DashboardController::class, 'viewDocuments'])->name('admin.view_documents');
        
         // View single application
        Route::get('/view-client/{id}', [DashboardController::class, 'viewApplication'])->name('applications.view');
    
        // Estimate for an application
        Route::get('/estimate-client/{id}', [DashboardController::class, 'estimateApplication'])->name('applications.estimate');
    
        // Letter for an application
        Route::get('/letter-client/{id}', [DashboardController::class, 'letterApplication'])->name('applications.letter');
    
    
    // User management routes
    Route::get('/users', [DashboardController::class, 'user'])->name('users');
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit', [DashboardController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [DashboardController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [DashboardController::class, 'destroy'])->name('users.destroy');
    
    // Company management routes
    Route::post('/companies/delete', [DashboardController::class, 'deleteCompany'])->name('companies.delete');
    });
    
// SIP Docs (shared SIP listing)
    Route::middleware(['auth', 'role:admin,user'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/dashboard/show/{sip}', [DashboardController::class, 'show'])
        ->name('dashboard.show');
    
        });

// Import file (New SIP Line) routes (accessible by both admin and user)
Route::middleware(['auth'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // Dashboard home
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Import routes
    Route::get('/import', [DashboardController::class, 'importForm'])->name('import.form'); // form page
    Route::post('/import', [DashboardController::class, 'importSubmit'])->name('import.submit'); // submit form
    Route::put('/import', [DashboardController::class, 'importSubmit'])->name('import.submit.put'); // optional, if you use PUT
});




//for Users
// User-only dashboard with role-based permissions
Route::middleware(['auth', 'role:user'])->match(['get', 'post'], '/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

// Admin and User permission-based routes
Route::middleware(['auth' , 'role:user'])->prefix('user')->name('user.')->group(function () {
    
    Route::get('/view-sip-docs', [DashboardController::class, 'viewSipDocs'])->name('view_sip_docs');
    Route::match(['get', 'post'], '/upload-docs', [DashboardController::class, 'uploadDocs'])->name('upload_docs');
    Route::get('/update-sip-docs', [DashboardController::class, 'updateSipDocs'])->name('update_sip_docs');
    Route::get('/view-client-apps', [DashboardController::class, 'viewClientApps'])->name('view_client_apps');
    Route::match(['get', 'post'], '/update-client-apps', [DashboardController::class, 'updateClientApps'])->name('update_client_apps');
    Route::match(['get', 'post'], '/view-documents', [DashboardController::class, 'viewDocuments'])->name('view_documents');
    // Route::get('/import', [DashboardController::class, 'importForm'])->name('import');
    // Route::post('/import', [DashboardController::class, 'importSubmit'])->name('import.submit');
});



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



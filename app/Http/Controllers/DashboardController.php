<?php

namespace App\Http\Controllers;


use App\Models\Application;
use App\Models\DashboardCompany;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;



class DashboardController extends Controller
{
    public function index()
    {
        $companies = DashboardCompany::orderByRaw("
            CAST(
                CASE
                    WHEN UPPER(LEFT(sip_number, 3)) = 'SIP' THEN SUBSTRING(sip_number, 4)
                    ELSE sip_number
                END AS UNSIGNED
            ) ASC
        ")->get();

        $totalCompanies = DashboardCompany::distinct('company_name')->count('company_name');
        $totalSIPs = DashboardCompany::distinct('sip_number')->count('sip_number');
        $totalFiles = DashboardCompany::count();

        return view('dashboard.index', compact('companies', 'totalCompanies', 'totalSIPs', 'totalFiles'));
    }

  public function show(string $sip)
{
    $sipNormalized = $this->normalizeSipForLookup($sip);

    $company = DashboardCompany::whereRaw("
        REPLACE(
            CASE
                WHEN UPPER(LEFT(sip_number, 3)) = 'SIP' THEN SUBSTRING(sip_number, 4)
                ELSE sip_number
            END,
            ' ', ''
        ) = ?
    ", [$sipNormalized])->firstOrFail();

    $documents = Storage::disk('public')->files("sip_docs/{$company->sip_number}");

    return view('dashboard.show', compact('company', 'documents'));
}


    // Admin dashboard view
    public function admin()
    {
        $companies = DashboardCompany::orderByRaw("
            CAST(
                CASE
                    WHEN UPPER(LEFT(sip_number, 3)) = 'SIP' THEN SUBSTRING(sip_number, 4)
                    ELSE sip_number
                END AS UNSIGNED
            ) ASC
        ")->get();

        $totalCompanies = DashboardCompany::distinct('company_name')->count('company_name');
        $totalSIPs = DashboardCompany::distinct('sip_number')->count('sip_number');
        $totalFiles = DashboardCompany::count();

        return view('dashboard.admin_index', compact('companies', 'totalCompanies', 'totalSIPs', 'totalFiles'));
    }

    // User dashboard view


    // User dashboard view with role-based permissions
    public function userDashboard(Request $request)
    {
        $user = Auth::user();
        
         // Decode permissions JSON to array
    $userPermissions = $user->permissions ? json_decode($user->permissions, true) : [];
        
        // Log user dashboard access
        UserActivity::create([
            'user_id' => $user->id,
            'activity' => 'Accessed user dashboard',
        ]);

        // Handle add user functionality
        if ($request->isMethod('post') && in_array('manage_users', $userPermissions) && $request->has('add_user')) {
            $data = $request->validate([
                'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
                'password' => ['required', 'string', 'min:6'],
                'role' => ['required', Rule::in(['admin', 'user'])],
                'permissions' => ['nullable', 'array'],
            ]);

            $newUser = User::create([
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'permissions' => !empty($data['permissions']) ? json_encode($data['permissions']) : null,
            ]);

            // Log activity: created user
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Created user: ' . $newUser->username,
            ]);

            return redirect()->route('user.dashboard')->with('success', 'User added successfully!');
        }

        // Fetch data based on permissions
        $activities = [];
        $allUsers = [];
        $companies = [];
        $applications = [];
        
        if (in_array('dashboard_activities', $userPermissions)) {
            $activities = UserActivity::with('user')
                ->orderBy('activity_time', 'desc')
                ->get();
        }
        
        if (in_array('manage_users', $userPermissions)) {
            $allUsers = User::orderBy('id', 'desc')->get();
        }

        if (in_array('view_sip_docs', $userPermissions)) {
            $companies = DashboardCompany::orderByRaw("
                CAST(
                    CASE
                        WHEN UPPER(LEFT(sip_number, 3)) = 'SIP' THEN SUBSTRING(sip_number, 4)
                        ELSE sip_number
                    END AS UNSIGNED
                ) ASC
            ")->get();
        }

        if (in_array('view_client_apps', $userPermissions)) {
            $applications = Application::orderBy('id', 'desc')->get();
        }

        // Full permissions list for checkbox rendering in Blade
        $allPermissions = [
            'dashboard_activities',
            'upload_docs',
            'update_sip_docs',
            'view_client_apps',
            'manage_users',
            'update_client_apps',
        ];

        return view('dashboard.user_dashboard', compact('userPermissions', 'activities', 'allUsers', 'allPermissions'));
    }

// Show all client applications
public function viewClientApps()
{
    $user = Auth::user();

    UserActivity::create([
        'user_id' => $user->id,
        'activity' => 'Viewed client applications',
    ]);

    $applications = Application::orderBy('id', 'desc')->get();

    return view('dashboard.view_client', compact('applications'));
}

// View single application details
public function viewApplication($id)
{
    $user = Auth::user();
    $application = Application::findOrFail($id);

    UserActivity::create([
        'user_id' => $user->id,
        'activity' => "Viewed application ID {$id}",
    ]);

    return view('dashboard.application_view', compact('application'));
}

// Show estimate for a single application
public function estimateApplication($id)
{
    $user = Auth::user();
    $application = Application::findOrFail($id);

    UserActivity::create([
        'user_id' => $user->id,
        'activity' => "Viewed estimate for application ID {$id}",
    ]);

    return view('dashboard.application_estimate', compact('application'));
}

// Show letter for a single application
public function letterApplication($id)
{
    $user = Auth::user();
    $application = Application::findOrFail($id);

    UserActivity::create([
        'user_id' => $user->id,
        'activity' => "Viewed letter for application ID {$id}",
    ]);

    return view('dashboard.application_letter', compact('application'));
}


 // Upload Documents
   public function uploadDocs(Request $request)
{
    $user = Auth::user();

    // Decode permissions JSON to array
    $userPermissions = $user->permissions ? json_decode($user->permissions, true) : [];

    // Check permission
    if (!in_array('upload_docs', $userPermissions)) {
        abort(403, 'Unauthorized access.');
    }

    // Handle POST requests for company creation and document upload
    if ($request->isMethod('post')) {
        // Handle document upload
        if ($request->hasFile('documents')) {
            $request->validate([
                'documents.*' => 'file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'sip_number' => 'required|string',
            ]);

            $sipNumber = $request->sip_number;
            $documents = $request->file('documents');
            
            foreach ($documents as $document) {
                $path = $document->store("sip_docs/{$sipNumber}", 'public');
                
                // Log document upload
                UserActivity::create([
                    'user_id' => $user->id,
                    'activity' => "Uploaded document: {$document->getClientOriginalName()} for SIP: {$sipNumber}",
                ]);
            }
            
            return response('Documents uploaded successfully!');
        }
        
        // Handle company creation/update
        $request->validate([
            'sip_number' => 'required|string',
            'service_dn' => 'nullable|string',
            'customer_name' => 'required|string',
            'sip_type' => 'nullable|string',
            'customer_type' => 'nullable|string',
            'name_of_proprietor' => 'nullable|string',
            'company_reg_no' => 'nullable|string',
            'reg_date' => 'nullable|date',
            'pan_no' => 'nullable|string',
            'province_perm' => 'nullable|string',
            'district_perm' => 'nullable|string',
            'municipality_perm' => 'nullable|string',
            'ward_perm' => 'nullable|string',
            'tole_perm' => 'nullable|string',
            'province_install' => 'nullable|string',
            'district_install' => 'nullable|string',
            'municipality_install' => 'nullable|string',
            'ward_install' => 'nullable|string',
            'tole_install' => 'nullable|string',
            'landline' => 'nullable|string',
            'mobile' => 'nullable|string',
            'email' => 'nullable|email',
            'website' => 'nullable|string',
            'objectives' => 'nullable|string',
            'purpose' => 'nullable|string',
            'sessions' => 'nullable|integer|min:0',
            'authorized_signature' => 'nullable|string',
            'signature_name' => 'nullable|string',
            'position' => 'nullable|string',
            'signature_date' => 'nullable|date',
            'seal' => 'nullable|string',
        ]);

        // Check if company exists
        $existingCompany = DashboardCompany::where('sip_number', $request->sip_number)->first();
        
        if ($existingCompany) {
            // Update existing company
            $existingCompany->update($request->all());
            
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => "Updated company: {$request->customer_name} (SIP: {$request->sip_number})",
            ]);
            
            return response('Company updated successfully!');
        } else {
            // Create new company
            DashboardCompany::create($request->all());
            
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => "Added new company: {$request->customer_name} (SIP: {$request->sip_number})",
            ]);
            
            return response('Company saved successfully!');
        }
    }

    // Log access for GET requests
    UserActivity::create([
        'user_id' => $user->id,
        'activity' => 'Accessed upload documents',
    ]);

    // If you want to edit a company, default null
    $editCompany = null;

    return view('dashboard.upload_docs', compact('editCompany'));
}




    public function user()
    {
        $users = User::all(); // Get all users from database
        $section = 'users'; // Set section for conditional display

        return view('dashboard.user', compact('users', 'section'));
    }


    // Handle admin add-user form
    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['admin', 'user'])],

            'permissions' => ['nullable', 'array'],
        ]);

        $user = User::create([
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
            'permissions' => !empty($data['permissions']) ? json_encode($data['permissions']) : null,
        ]);

        // Log activity: created user
        UserActivity::create([
            'user_id'  => Auth::id(),
            'activity' => 'Created user: ' . $user->username,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully.');
    }



    public function users_listing()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }



    // Admin activities page (logs when visited)
    public function activities()
    {
        $user = Auth::user();
        // Log that the admin viewed the activities page
        UserActivity::create([
            'user_id'  => $user->id,
            'activity' => 'Viewed activities',
        ]);

        // Fetch recent activities (latest first)
        $activities = UserActivity::with('user')->orderBy('activity_time', 'desc')->paginate(50);

        return view('dashboard.activities', compact('activities'));
    }

    public function clientApps(Request $request)
    {
        $user = Auth::user();
        // Log page view only on GET
        if ($request->isMethod('get') && $user) {
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Opened Admin Applications Dashboard',
            ]);
        }

        // Handle status update (per-row)
        if ($request->isMethod('post') && $request->has('update_status')) {
            $id = $request->input('id');
            $newStatus = $request->input('status');
            $app = Application::find($id);
            if ($app) {
                $app->status = $newStatus;
                $app->save();

                if ($user) {
                    UserActivity::create([
                        'user_id' => $user->id,
                        'activity' => "Updated status of application ID {$id} to {$newStatus}",
                    ]);
                }
                return redirect()->route('admin.client_apps')->with('success', 'Status updated.');
            }
            return redirect()->route('admin.client_apps')->with('error', 'Application not found.');
        }

        // Handle delete selected (bulk)
        if ($request->isMethod('post') && $request->has('delete_selected')) {
            $ids = $request->input('selected', []);
            if (!empty($ids)) {
                Application::destroy($ids);
                if ($user) {
                    UserActivity::create([
                        'user_id' => $user->id,
                        'activity' => 'Deleted applications with IDs: ' . implode(', ', $ids),
                    ]);
                }
                return redirect()->route('admin.client_apps')->with('success', 'Selected applications deleted.');
            }
            return redirect()->route('admin.client_apps')->with('error', 'No applications selected.');
        }

        // Fetch apps
        $applications = Application::orderBy('id', 'desc')->get();

        return view('dashboard.client_apps', compact('applications'));
    }


    public function deleteCompany(Request $request): JsonResponse
    {
        $user = Auth::user();
        $sip = $request->input('sip', '');

        // normalize: remove leading "sip" and spaces
        $sipNormalized = trim(preg_replace('/^sip\s*/i', '', (string)$sip));
        $sipNormalized = str_replace(' ', '', $sipNormalized);

        if ($sipNormalized === '') {
            return response()->json(['success' => false, 'error' => 'Invalid SIP'], 400);
        }

        // Find company by normalized sip (same lookup used elsewhere)
        $company = DashboardCompany::whereRaw("
        REPLACE(
            CASE
                WHEN UPPER(LEFT(sip_number, 3)) = 'SIP' THEN SUBSTRING(sip_number, 4)
                ELSE sip_number
            END,
            ' ', ''
        ) = ?
    ", [$sipNormalized])->first();

        if (! $company) {
            return response()->json(['success' => false, 'error' => 'Company not found'], 404);
        }

        $company->delete();

        if ($user) {
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Deleted company with SIP: ' . $sip,
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $allPermissions = ['view_sip_docs', 'upload_docs', 'update_sip_docs', 'view_client_apps', 'view_logs', 'manage_users', 'update_client_apps'];

        return view('dashboard.edit_user', compact('user', 'allPermissions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($id)],
            'role'     => ['required', Rule::in(['admin', 'user'])],
            'permissions' => ['nullable', 'array'],
        ]);

        $user->username = $data['username'];
        $user->role = $data['role'];
        $user->permissions = !empty($data['permissions']) ? json_encode($data['permissions']) : null;

        if ($request->filled('password')) {
            $request->validate(['password' => ['string', 'min:6']]);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Log activity: updated user
        UserActivity::create([
            'user_id'  => Auth::id(),
            'activity' => 'Updated user: ' . $user->username,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'User updated successfully.');
    }

    // View SIP Documents
    // View SIP Documents (USER VIEW)
public function viewSipDocs()
{
    $user = Auth::user();

    UserActivity::create([
        'user_id' => $user->id,
        'activity' => 'Viewed SIP documents',
    ]);

    $companies = DashboardCompany::orderByRaw("
        CAST(
            CASE
                WHEN UPPER(LEFT(sip_number, 3)) = 'SIP' THEN SUBSTRING(sip_number, 4)
                ELSE sip_number
            END AS UNSIGNED
        ) ASC
    ")->get();

    // ADD THESE (IMPORTANT)
    $totalCompanies = DashboardCompany::distinct('company_name')->count('company_name');
    $totalSIPs = DashboardCompany::distinct('sip_number')->count('sip_number');
    $totalFiles = DashboardCompany::count();

    return view('dashboard.view_sip_docs', compact(
        'companies',
        'totalCompanies',
        'totalSIPs',
        'totalFiles'
    ));
}


    

    // Update SIP Documents
    public function updateSipDocs()
    {
        $user = Auth::user();
        
        UserActivity::create([
            'user_id' => $user->id,
            'activity' => 'Accessed update SIP documents',
        ]);

        $companies = DashboardCompany::orderByRaw("
            CAST(
                CASE
                    WHEN UPPER(LEFT(sip_number, 3)) = 'SIP' THEN SUBSTRING(sip_number, 4)
                    ELSE sip_number
                END AS UNSIGNED
            ) ASC
        ")->get();

        return view('dashboard.update_sip_docs', compact('companies'));
    }

     private function normalizeSipForLookup($sip)
    {
        $sip = trim($sip ?? '');
        if ($sip === '') return '';
        $sip = preg_replace('/^sip\s*/i', '', $sip);
        return str_replace(' ', '', $sip);
    }

    // Update Client Applications
    public function updateClientApps(Request $request)
{
    $user = Auth::user();

    UserActivity::create([
        'user_id' => $user->id,
        'activity' => 'Accessed update client applications',
    ]);

    // Update status
    if ($request->has('update_status')) {
        $app = Application::find($request->id);

        if ($app) {
            $app->status = $request->status;
            $app->save();

            UserActivity::create([
                'user_id' => $user->id,
                'activity' => "Updated status of application ID {$app->id}",
            ]);

            return back()->with('success', 'Status updated successfully.');
        }
    }

    // Delete selected
    if ($request->has('delete_selected')) {
        $ids = $request->input('selected', []);

        if (!empty($ids)) {
            Application::destroy($ids);

            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Deleted applications: ' . implode(', ', $ids),
            ]);

            return back()->with('success', 'Applications deleted.');
        }
    }

    $applications = Application::orderBy('id', 'desc')->get();
    return view('dashboard.update_client_apps', compact('applications'));
}




}




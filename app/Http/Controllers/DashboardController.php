<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

use Illuminate\Validation\Rule;

use App\Models\User;

use App\Models\UserActivity;

use App\Models\DashboardCompany;

use App\Models\Application;

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



        // Debug: Log the first few companies to see what's being loaded

        \Log::info('Dashboard companies loaded:', [

            'total_count' => $companies->count(),

            'first_company' => $companies->first() ? [

                'id' => $companies->first()->id,

                'sip_number' => $companies->first()->sip_number,

                'customer_name' => $companies->first()->customer_name,

                'updated_at' => $companies->first()->updated_at

            ] : null,

            'company_497' => $companies->where('id', 497)->first() ? [

                'id' => $companies->where('id', 497)->first()->id,

                'sip_number' => $companies->where('id', 497)->first()->sip_number,

                'customer_name' => $companies->where('id', 497)->first()->customer_name,

                'updated_at' => $companies->where('id', 497)->first()->updated_at

            ] : 'not found',

            'company_500' => $companies->where('id', 500)->first() ? [

                'id' => $companies->where('id', 500)->first()->id,

                'sip_number' => $companies->where('id', 500)->first()->sip_number,

                'customer_name' => $companies->where('id', 500)->first()->customer_name,

                'updated_at' => $companies->where('id', 500)->first()->updated_at

            ] : 'not found',

            'company_501' => $companies->where('id', 501)->first() ? [

                'id' => $companies->where('id', 501)->first()->id,

                'sip_number' => $companies->where('id', 501)->first()->sip_number,

                'customer_name' => $companies->where('id', 501)->first()->customer_name,

                'updated_at' => $companies->where('id', 501)->first()->updated_at

            ] : 'not found',

            'last_company' => $companies->last() ? [

                'id' => $companies->last()->id,

                'sip_number' => $companies->last()->sip_number,

                'customer_name' => $companies->last()->customer_name,

                'updated_at' => $companies->last()->updated_at

            ] : null

        ]);



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

        $validated = $request->validate([

            'sip_number' => 'required|string|max:50',

            'service_dn' => 'nullable|string|max:100',

            'sip_type' => 'nullable|string',

            'customer_name' => 'required|string|max:255',

            'customer_type' => 'nullable|string|max:100',

            'proprietor_name' => 'nullable|string|max:100',

            'company_reg_no' => 'nullable|string|max:50',

            'reg_date' => 'nullable|date',

            'pan_no' => 'nullable|string|max:50',

            'province_perm' => 'nullable|string|max:100',

            'district_perm' => 'nullable|string|max:100',

            'municipality_perm' => 'nullable|string|max:100',

            'ward_perm' => 'nullable|string|max:20',

            'tole_perm' => 'nullable|string|max:100',

            'province_install' => 'nullable|string|max:100',

            'district_install' => 'nullable|string|max:100',

            'municipality_install' => 'nullable|string|max:100',

            'ward_install' => 'nullable|string|max:20',

            'tole_install' => 'nullable|string|max:100',

            'landline' => 'nullable|string|max:255',

            'mobile' => 'nullable|string|max:50',

            'email' => 'nullable|email|max:255',

            'website' => 'nullable|string|max:255',

            'objectives' => 'nullable|string',

            'purpose' => 'nullable|string',

            'sessions' => 'nullable|integer|min:0',

            'authorized_signature' => 'nullable|string|max:100',

            'signature_name' => 'nullable|string|max:100',

            'position' => 'nullable|string|max:100',

            'signature_date' => 'nullable|date',

            'seal' => 'nullable|string|max:100',

        ]);



        // Check if company exists

        $sipNormalized = $this->normalizeSipForLookup($request->sip_number);

        $existingCompany = DashboardCompany::where('sip_number', $sipNormalized)

            ->orWhere('sip_number', 'SIP' . $sipNormalized)

            ->orWhere('sip_number', 'SIP ' . $sipNormalized)

            ->first();

        

        if ($existingCompany) {

            // Update existing company

            $existingCompany->update($validated);

            

            UserActivity::create([

                'user_id' => $user->id,

                'activity' => "Updated company: {$request->customer_name} (SIP: {$request->sip_number})",

            ]);

            

            return response('Company updated successfully!', 200);

        } else {

            // Create new company

            DashboardCompany::create($validated);

            

            UserActivity::create([

                'user_id' => $user->id,

                'activity' => "Added new company: {$request->customer_name} (SIP: {$request->sip_number})",

            ]);

            

            return response('Company saved successfully!', 200);

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



// View Documents for specific SIP

public function viewDocuments(Request $request)

{

    $user = Auth::user();

    

    // Decode permissions JSON to array

    $userPermissions = $user->permissions ? json_decode($user->permissions, true) : [];

    

  // Check permission - allow admin users or users with view_sip_docs permission
if ($user->role !== 'admin' && !in_array('view_sip_docs', $userPermissions)) {
    abort(403, 'Unauthorized access.');
}
    

    // Get SIP number from request

    $sip = $request->get('sip');

    if (!$sip) {

        return redirect()->back()->with('error', 'No SIP selected.');

    }

    

    // Normalize SIP number for lookup

    $sipNormalized = $this->normalizeSipForLookup($sip);

    

    // Find company

    $company = DashboardCompany::where('sip_number', $sipNormalized)

        ->orWhere('sip_number', 'SIP' . $sipNormalized)

        ->orWhere('sip_number', 'SIP ' . $sipNormalized)

        ->first();

    

    if (!$company) {

        return redirect()->back()->with('error', 'Company not found for SIP: ' . $sip);

    }

    

    // Log access

    UserActivity::create([

        'user_id' => $user->id,

        'activity' => "Viewed documents for SIP: {$company->sip_number}",

    ]);

    

    // Get documents from database (scanned_docs table)

    $documents = DB::table('uploaded_files')

        ->where('sip_number', $sipNormalized)

        ->orWhere('sip_number', 'SIP' . $sipNormalized)

        ->orWhere('sip_number', 'SIP ' . $sipNormalized)

        ->get();

    

    // Get documents from database only

    $allDocuments = [];

    foreach ($documents as $doc) {

        $allDocuments[] = [

            'file_name' => $doc->file_name,

            'file_path' => $doc->file_path,

            'source' => 'database'

        ];

    }

    $companyName = $company->company_name ?? $company->sip_number;

    

    return view('dashboard.view_documents', compact('company', 'allDocuments', 'companyName'));

}









// Import form display

public function importForm(Request $request)

{

    $user = Auth::user();

    

    // Admins have access to all functionality

    if ($user->role === 'admin') {

        // Log access

        UserActivity::create([

            'user_id' => $user->id,

            'activity' => 'Accessed import form',

        ]);

        

        // Get SIP from URL for edit mode

        $editCompany = null;

        $sip = $request->get('sip');

        

        if ($sip) {

            $sipNormalized = $this->normalizeSipForLookup($sip);

            $editCompany = DashboardCompany::where('sip_number', $sipNormalized)

                ->orWhere('sip_number', 'SIP' . $sipNormalized)

                ->orWhere('sip_number', 'SIP ' . $sipNormalized)

                ->first();

        }

        

        return view('dashboard.import', compact('editCompany'));

    }

    

    // For users, check permissions

    $userPermissions = $user->permissions ? json_decode($user->permissions, true) : [];

    

    // Check permission

    if (!in_array('view_sip_docs', $userPermissions)) {

        abort(403, 'Unauthorized access.');

    }

    

    // Log access

    UserActivity::create([

        'user_id' => $user->id,

        'activity' => 'Accessed import form',

    ]);

    

    // Get SIP from URL for edit mode

    $editCompany = null;

    $sip = $request->get('sip');

    

    if ($sip) {

        $sipNormalized = $this->normalizeSipForLookup($sip);

        $editCompany = DashboardCompany::where('sip_number', $sipNormalized)

            ->first();

    }

    

    return view('dashboard.import', compact('userPermissions', 'editCompany'));

}



// Import form submission

public function importSubmit(Request $request)

{

    $user = Auth::user();



    // Check user permissions

    if ($user->role !== 'admin') {

        $userPermissions = $user->permissions ? json_decode($user->permissions, true) : [];

        if (!in_array('view_sip_docs', $userPermissions)) {

            abort(403, 'Unauthorized access.');

        }

    }



    // Validate request

    $validated = $request->validate([

        'sip_number' => 'required|string|max:50',

        'service_dn' => 'nullable|string|max:100',

        'sip_type' => 'nullable|string',

        'customer_name' => 'required|string|max:255',

        'customer_type' => 'nullable|string|max:100',

        'name_of_proprietor' => 'nullable|string|max:100',

        'company_reg_no' => 'nullable|string|max:50',

        'reg_date' => 'nullable|date',

        'pan_no' => 'nullable|string|max:50',

        'province_perm' => 'nullable|string|max:100',

        'district_perm' => 'nullable|string|max:100',

        'municipality_perm' => 'nullable|string|max:100',

        'ward_perm' => 'nullable|string|max:20',

        'tole_perm' => 'nullable|string|max:100',

        'province_install' => 'nullable|string|max:100',

        'district_install' => 'nullable|string|max:100',

        'municipality_install' => 'nullable|string|max:100',

        'ward_install' => 'nullable|string|max:20',

        'tole_install' => 'nullable|string|max:100',

        'landline' => 'nullable|string|max:255',

        'mobile' => 'nullable|string|max:50',

        'email' => 'nullable|email|max:255',

        'website' => 'nullable|string|max:255',

        'objectives' => 'nullable|string',

        'purpose' => 'nullable|string',

        'sessions' => 'nullable|integer|min:0',

        'authorized_signature' => 'nullable|string|max:100',

        'signature_name' => 'nullable|string|max:100',

        'position' => 'nullable|string|max:100',

        'signature_date' => 'nullable|date',

        'seal' => 'nullable|string|max:100',

        'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx|max:10240',

    ]);



    try {

        // Normalize SIP number

        $sipNormalized = $this->normalizeSipForLookup($validated['sip_number']);



        // Find existing company by SIP number (check multiple formats)

        $company = DashboardCompany::where('sip_number', $sipNormalized)

            ->orWhere('sip_number', 'SIP' . $sipNormalized)

            ->orWhere('sip_number', 'SIP ' . $sipNormalized)

            ->first();

        // Map old PHP field names to new Laravel field names
        if ($company && isset($validated['service_dn'])) {
            $company->DN = $validated['service_dn']; // Map service_dn to DN field
        }

        // Filter out null/empty fields to avoid overwriting
        $updateData = array_filter($validated, function ($value) {
            return !is_null($value) && $value !== '';
        });

        // Map form field names to database field names
        $mappedData = [];
        foreach ($updateData as $key => $value) {
            switch ($key) {
                case 'service_dn':
                    $mappedData['DN'] = $value;
                    break;
                case 'customer_name':
                    $mappedData['company_name'] = $value;
                    break;
                case 'name_of_proprietor':
                    $mappedData['proprietor_name'] = $value;
                    break;
                default:
                    $mappedData[$key] = $value;
                    break;
            }
        }

        if ($company) {
            // Update only non-null fields
            $company->update($mappedData);
            $activityText = 'Updated company';
            // Debug: Log what was updated
            \Log::info('Company updated:', [
                'company_id' => $company->id,
                'update_data' => $mappedData,
                'updated_fields' => array_keys($mappedData)
            ]);
        } else {
            // Create new company
            $company = DashboardCompany::create($mappedData);
            $activityText = 'Created new company';
            // Debug: Log what was created
            \Log::info('Company created:', [
                'company_id' => $company->id,
                'create_data' => $mappedData,
                'created_fields' => array_keys($mappedData)
            ]);
        }

        // Handle document uploads
        $uploadedDocuments = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {

                if ($file->isValid()) {

                    // Create SIP-specific directory if it doesn't exist

                    $sipDir = "sip_docs/{$sipNormalized}";

                    $filePath = $file->store($sipDir, 'public');

                    DB::table('uploaded_files')->insert([

                        'sip_number' => $sipNormalized,

                        'file_name' => $file->getClientOriginalName(),

                        'file_path' => $filePath,

                        'uploaded_at' => now(),

                    ]);
                    
                    // Add to uploaded documents array for response
                    $uploadedDocuments[] = [
                        'name' => $file->getClientOriginalName(),
                        'url' => asset($filePath)
                    ];
                }

            }
        }



        // Log activity

        UserActivity::create([

            'user_id' => $user->id,

            'activity' => $activityText . ': ' . $validated['sip_number'],

        ]);



        // Return JSON response for AJAX requests

        if ($request->header('X-Requested-With') === 'XMLHttpRequest' || $request->wantsJson()) {

            return response()->json([

                'success' => true,

                'message' => $company->wasRecentlyCreated ? 'Company created successfully!' : 'Company updated successfully!',

                'redirect' => route('dashboard.index'),

                'documents' => $uploadedDocuments

            ]);

        }

        return redirect()->route('dashboard.index')

            ->with('success', $company->wasRecentlyCreated ? 'Company created successfully!' : 'Company updated successfully!');



    } catch (\Exception $e) {

        return redirect()->back()->with('error', 'Error saving company: ' . $e->getMessage());

    }

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








<?php

namespace App\Http\Controllers;


use App\Models\Application;
use App\Models\DashboardCompany;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        return view('dashboard.show', compact('company'));
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

        // Get user permissions
        // Convert JSON permissions string to array
        $userPermissions = $user->permissions ? json_decode($user->permissions, true) : [];

        // Log dashboard access
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

            // Log activity
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Created user: ' . $newUser->username,
            ]);

            return redirect()->route('user.dashboard')->with('success', 'User added successfully!');
        }

        // Fetch data based on permissions
        $activities = [];
        $allUsers = [];

        if (in_array('dashboard_activities', $userPermissions)) {
            $activities = UserActivity::with('user')
                ->orderBy('activity_time', 'desc')
                ->get();
        }

        if (in_array('manage_users', $userPermissions)) {
            $allUsers = User::orderBy('id', 'desc')->get();
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

    private function normalizeSipForLookup($sip)
    {
        $sip = trim($sip ?? '');
        if ($sip === '') return '';
        $sip = preg_replace('/^sip\s*/i', '', $sip);
        return str_replace(' ', '', $sip);
    }
}

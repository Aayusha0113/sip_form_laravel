<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UserActivity;
use App\Models\DashboardCompany;

class DashboardController extends Controller
{
    /**
     * Display import form for adding/editing companies
     */
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
                // Normalize SIP for lookup
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
            // Normalize SIP for lookup
            $sipNormalized = $this->normalizeSipForLookup($sip);
            $editCompany = DashboardCompany::where('sip_number', $sipNormalized)
                ->orWhere('sip_number', 'SIP' . $sipNormalized)
                ->orWhere('sip_number', 'SIP ' . $sipNormalized)
                ->first();
        }
        
        return view('dashboard.import', compact('editCompany'));
    }

    /**
     * Handle import form submission (create/update company + document uploads)
     */
    public function importSubmit(Request $request)
    {
        $user = Auth::user();
        
        // Admins have access to all functionality
        if ($user->role !== 'admin') {
            // For users, check permissions
            $userPermissions = $user->permissions ? json_decode($user->permissions, true) : [];
            
            // Check permission
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
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx|max:10240', // 10MB per file
        ]);
        
        try {
            // Create or update company
            $sipNormalized = $this->normalizeSipForLookup($validated['sip_number']);
            
            // Check if this is an update operation
            $isUpdate = $request->isMethod('PUT') || $request->has('sip_number');
            
            if ($isUpdate) {
                // Update existing company
                $company = DashboardCompany::where('sip_number', $sipNormalized)->first();
                if ($company) {
                    $company->update($validated);
                } else {
                    // Fallback to create if not found
                    $company = DashboardCompany::create($validated);
                }
            } else {
                // Create new company
                $company = DashboardCompany::create($validated);
            }
            
            // Handle document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $filePath = $file->store('sip_docs', 'public');
                        
                        // Store in uploaded_files table
                        DB::table('uploaded_files')->insert([
                            'sip_number' => $sipNormalized,
                            'file_name' => $fileName,
                            'file_path' => 'sip_docs/' . $fileName,
                            'uploaded_at' => now(),
                        ]);
                    }
                }
            }
            
            // Log activity
            $activity = $isUpdate ? 'Updated company' : 'Created new company';
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => $activity . ': ' . $validated['sip_number'],
            ]);
            
            return redirect()->route('dashboard.index')
                ->with('success', $isUpdate ? 'Company updated successfully!' : 'Company created successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error saving company: ' . $e->getMessage());
        }
    }

    /**
     * Normalize SIP number for database lookup
     */
    private function normalizeSipForLookup($sip)
    {
        $sip = trim($sip ?? '');
        if ($sip === '') return '';
        $sip = preg_replace('/^sip\s*/i', '', $sip);
        return str_replace(' ', '', $sip);
    }
}

@extends('layouts.app')

@section('content')
@include('dashboard.admin_sidebar_partial')

<!-- Import Form Container -->
<div class="import-form-container">
    <h2>Import SIP Documents</h2>
    
    @if(request()->has('sip'))
        <div class="edit-mode-notice">
            <i class="fas fa-edit"></i>
            <strong>Edit Mode:</strong> Editing SIP {{ request('sip') }}
            <a href="{{ route('dashboard.import') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-plus"></i> Import New SIP
            </a>
        </div>
    @endif

    <form id="companyForm" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="edit_mode" value="{{ request()->has('sip') ? '1' : '0' }}">
        
        <!-- SIP Number + Service/DN No -->
        <div class="form-section">
            <h3>SIP Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="sip_number">SIP Number: <span class="required">*</span></label>
                    <input type="text" name="sip_number" id="sip_number" placeholder="SIP001"
                           value="{{ request('sip') }}" {{ request()->has('sip') ? 'readonly' : '' }} required>
                </div>
                <div class="form-group">
                    <label for="service_dn">Service/DN No:</label>
                    <input type="text" name="service_dn" id="service_dn" placeholder="DN001"
                           value="{{ old('service_dn') ?? $editCompany->service_dn ?? '' }}">
                </div>
            </div>
        </div>

        <!-- SIP PBX / SIP Trunk Type -->
        <div class="form-section">
            <h3>SIP PBX / SIP Trunk Type</h3>
            <div class="form-group">
                <select name="sip_type" class="form-control">
                    <option value="">-- Select a Type --</option>
                    <option value="Type I: Single DN with Multiple Sessions, Without Hunting" {{ (old('sip_type') ?? $editCompany->sip_type ?? '') == 'Type I: Single DN with Multiple Sessions, Without Hunting' ? 'selected' : '' }}>Type I: Single DN with Multiple Sessions, Without Hunting</option>
                    <option value="Type II: Multiple DNs with Single Session (each session for single DN), with/without Hunting" {{ (old('sip_type') ?? $editCompany->sip_type ?? '') == 'Type II: Multiple DNs with Single Session (each session for single DN), with/without Hunting' ? 'selected' : '' }}>Type II: Multiple DNs with Single Session (each session for single DN), with/without Hunting</option>
                    <option value="Type III: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within single premises" {{ (old('sip_type') ?? $editCompany->sip_type ?? '') == 'Type III: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within single premises' ? 'selected' : '' }}>Type III: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within single premises</option>
                    <option value="Type IV: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within multiple premises" {{ (old('sip_type') ?? $editCompany->sip_type ?? '') == 'Type IV: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within multiple premises' ? 'selected' : '' }}>Type IV: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within multiple premises</option>
                    <option value="Type V: Single DN with Multiple Sessions connected to Data Center / Cloud PBX" {{ (old('sip_type') ?? $editCompany->sip_type ?? '') == 'Type V: Single DN with Multiple Sessions connected to Data Center / Cloud PBX' ? 'selected' : '' }}>Type V: Single DN with Multiple Sessions connected to Data Center / Cloud PBX</option>
                    <option value="Type VI: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within single premises" {{ (old('sip_type') ?? $editCompany->sip_type ?? '') == 'Type VI: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within single premises' ? 'selected' : '' }}>Type VI: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within single premises</option>
                    <option value="Type VII: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within multiple premises" {{ (old('sip_type') ?? $editCompany->sip_type ?? '') == 'Type VII: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within multiple premises' ? 'selected' : '' }}>Type VII: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within multiple premises</option>
                </select>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="form-section">
            <h3>Customer Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Customer Name: <span class="required">*</span></label>
                    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') ?? $editCompany->customer_name ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Customer Type:</label>
                    <input type="text" name="customer_type" class="form-control" value="{{ old('customer_type') ?? $editCompany->customer_type ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Name of Proprietor / Director:</label>
                    <input type="text" name="proprietor_name" class="form-control" value="{{ old('proprietor_name') ?? $editCompany->proprietor_name ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Company Registration No.:</label>
                    <input type="text" name="company_reg_no" class="form-control" value="{{ old('company_reg_no') ?? $editCompany->company_reg_no ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Registration Date:</label>
                    <input type="date" name="reg_date" class="form-control" value="{{ old('reg_date') ?? $editCompany->reg_date ?? '' }}">
                </div>
                <div class="form-group">
                    <label>PAN/VAT No.:</label>
                    <input type="text" name="pan_no" class="form-control" value="{{ old('pan_no') ?? $editCompany->pan_no ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Permanent Address -->
        <div class="form-section">
            <h3>Permanent Address</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Province:</label>
                    <input type="text" name="province_perm" id="province_perm" class="form-control" value="{{ old('province_perm') ?? $editCompany->province_perm ?? '' }}">
                </div>
                <div class="form-group">
                    <label>District:</label>
                    <input type="text" name="district_perm" id="district_perm" class="form-control" value="{{ old('district_perm') ?? $editCompany->district_perm ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Municipality:</label>
                    <input type="text" name="municipality_perm" id="municipality_perm" class="form-control" value="{{ old('municipality_perm') ?? $editCompany->municipality_perm ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Ward:</label>
                    <input type="text" name="ward_perm" id="ward_perm" class="form-control" value="{{ old('ward_perm') ?? $editCompany->ward_perm ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tole:</label>
                    <input type="text" name="tole_perm" id="tole_perm" class="form-control" value="{{ old('tole_perm') ?? $editCompany->tole_perm ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Installation Address -->
        <div class="form-section">
            <h3>Installation Address</h3>
            <div class="form-group">
                <input type="checkbox" id="sameAsPermanent" onchange="copyPermanentToInstallation()">
                <label for="sameAsPermanent">Same as Permanent Address</label>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Province:</label>
                    <input type="text" name="province_install" class="form-control" value="{{ old('province_install') ?? $editCompany->province_install ?? '' }}">
                </div>
                <div class="form-group">
                    <label>District:</label>
                    <input type="text" name="district_install" class="form-control" value="{{ old('district_install') ?? $editCompany->district_install ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Municipality:</label>
                    <input type="text" name="municipality_install" class="form-control" value="{{ old('municipality_install') ?? $editCompany->municipality_install ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Ward:</label>
                    <input type="text" name="ward_install" class="form-control" value="{{ old('ward_install') ?? $editCompany->ward_install ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Tole:</label>
                    <input type="text" name="tole_install" class="form-control" value="{{ old('tole_install') ?? $editCompany->tole_install ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="form-section">
            <h3>Contact Details</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Landline:</label>
                    <input type="text" name="landline" class="form-control" value="{{ old('landline') ?? $editCompany->landline ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Mobile:</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') ?? $editCompany->mobile ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') ?? $editCompany->email ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Website:</label>
                    <input type="text" name="website" class="form-control" value="{{ old('website') ?? $editCompany->website ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="form-section">
            <h3>Business Information</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Objectives of Company:</label>
                    <textarea name="objectives" class="form-control" rows="3">{{ old('objectives') ?? $editCompany->objectives ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Purpose of SIP PBX / SIP Trunk:</label>
                    <textarea name="purpose" class="form-control" rows="3">{{ old('purpose') ?? $editCompany->purpose ?? '' }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>No. of Sessions:</label>
                    <input type="number" name="sessions" class="form-control" min="0" value="{{ old('sessions') ?? $editCompany->sessions ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Authorization -->
        <div class="form-section">
            <h3>Authorization</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Authorized Signature:</label>
                    <input type="text" name="authorized_signature" class="form-control" value="{{ old('authorized_signature') ?? $editCompany->authorized_signature ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Signature Name:</label>
                    <input type="text" name="signature_name" class="form-control" value="{{ old('signature_name') ?? $editCompany->signature_name ?? '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Position:</label>
                    <input type="text" name="position" class="form-control" value="{{ old('position') ?? $editCompany->position ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Signature Date:</label>
                    <input type="date" name="signature_date" class="form-control" value="{{ old('signature_date') ?? $editCompany->signature_date ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Seal:</label>
                    <input type="text" name="seal" class="form-control" value="{{ old('seal') ?? $editCompany->seal ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Upload Scanned Documents -->
        <div class="form-section">
            <h3>Upload Scanned Documents</h3>
            <div class="form-group">
                <label>Select Documents:</label>
                <input type="file" name="documents[]" class="form-control" multiple>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                {{ request()->has('sip') ? 'Update Company' : 'Add Company' }}
            </button>
            <button type="button" class="btn btn-secondary" onclick="history.back()">
                <i class="fas fa-arrow-left"></i> Go Back
            </button>
        </div>
    </form>
</div>

<style>
.import-form-container {
    max-width: 1000px;
    margin: 20px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.1);
}

.edit-mode-notice {
    background: #e3f2fd;
    border: 1px solid #c3dafe;
    border-radius: 6px;
    padding: 15px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.form-section {
    margin-bottom: 25px;
}

.form-section h3 {
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 8px;
}

.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.form-group {
    flex: 1;
    min-width: 200px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #495057;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.required {
    color: #dc3545;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
}
</style>

<script>
// Copy Permanent Address to Installation Address
function copyPermanentToInstallation() {
    const checkbox = document.getElementById('sameAsPermanent');
    const installFields = document.querySelectorAll('[name$="_install"]');
    
    if (checkbox.checked) {
        // Copy permanent address values to installation address fields
        document.querySelector('[name="province_install"]').value = document.querySelector('[name="province_perm"]').value;
        document.querySelector('[name="district_install"]').value = document.querySelector('[name="district_perm"]').value;
        document.querySelector('[name="municipality_install"]').value = document.querySelector('[name="municipality_perm"]').value;
        document.querySelector('[name="ward_install"]').value = document.querySelector('[name="ward_perm"]').value;
        document.querySelector('[name="tole_install"]').value = document.querySelector('[name="tole_perm"]').value;
        
        // Make installation address fields readonly
        installFields.forEach(field => {
            field.readOnly = true;
            field.style.backgroundColor = '#f8f9fa';
        });
    } else {
        // Enable installation address fields
        installFields.forEach(field => {
            field.readOnly = false;
            field.style.backgroundColor = '';
        });
    }
}
</script>
@endsection

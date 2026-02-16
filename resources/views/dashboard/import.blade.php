@extends('layouts.app')

@section('content')


<!-- Import Form Container -->
<div class="import-form-container">
    <h2>Import SIP Documents</h2>
    
    @if(request()->has('sip'))
        <div class="edit-mode-notice">
            <i class="fas fa-edit"></i>
            <strong>Edit Mode:</strong> Editing SIP {{ request('sip') }}
            <a href="{{ route('dashboard.import.form') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-plus"></i> Import New SIP
            </a>
        </div>
    @endif

    <form id="companyForm" method="POST" action="{{ route('dashboard.import.submit') }}" enctype="multipart/form-data">
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
                <input type="file" name="documents[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx">
                <small class="text-muted">You can upload multiple documents at once (PDF, JPG, PNG, GIF, DOC, DOCX - Max 10MB per file)</small>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn btn-primary" onclick="{{ request()->has('sip') ? 'updateCompany()' : 'addCompany()' }}">
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
/* Popup Modal Styles */
.popup-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

.popup-content {
    background: white;
    padding: 30px 40px;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    text-align: center;
    max-width: 400px;
    animation: popupFadeIn 0.3s ease;
}

@keyframes popupFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.popup-content h3 {
    margin: 0 0 15px 0;
    color: #28a745;
    font-size: 20px;
    border: none;
    padding: 0;
}

.popup-content p {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 14px;
}

.popup-content button {
    background: #003366;
    color: white;
    padding: 10px 30px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    margin: 0;
}

.popup-content button:hover {
    background: #0055aa;
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

function addCompany(){
    const form = document.getElementById('companyForm');
    const sipInput = document.getElementById('sip_number');
    const serviceInput = document.getElementById('service_dn');
    const sipNumber = sipInput.value.trim();
    const serviceDN = serviceInput.value.trim();

    if (!sipNumber) {
        alert('Please enter SIP number before adding company.');
        return;
    }

    // Ensure installation address is synced if checkbox is checked
    ensureInstallationAddressSynced();

    const formData = new FormData(form);
    formData.append('sip_number', sipNumber);
    formData.append('service_dn', serviceDN);
    formData.append('_token', '{{ csrf_token() }}');

    fetch('{{ route("dashboard.import.submit") }}',{method:'POST',body:formData})
    .then(async res=>{
        const data = await res.text();
        if (!res.ok) throw new Error(data || 'Failed to save company');
        showSuccessModal('Company saved successfully!');
        // Reset form inputs but keep SIP/DN for document uploads
        form.reset();
        sipInput.value = sipNumber;
        serviceInput.value = serviceDN;
        // Reset checkbox if it was checked
        const checkbox = document.getElementById('sameAsPermanent');
        if (checkbox) {
            checkbox.checked = false;
            copyPermanentToInstallation();
        }
    })
    .catch(err=>alert('Error: '+err.message));
}

function ensureInstallationAddressSynced() {
    const checkbox = document.getElementById('sameAsPermanent');
    if (checkbox && checkbox.checked) {
        // Copy permanent address values to installation address fields
        document.querySelector('[name="province_install"]').value = document.querySelector('[name="province_perm"]').value;
        document.querySelector('[name="district_install"]').value = document.querySelector('[name="district_perm"]').value;
        document.querySelector('[name="municipality_install"]').value = document.querySelector('[name="municipality_perm"]').value;
        document.querySelector('[name="ward_install"]').value = document.querySelector('[name="ward_perm"]').value;
        document.querySelector('[name="tole_install"]').value = document.querySelector('[name="tole_perm"]').value;
    }
}

function updateCompany(){
    console.log('updateCompany function called');
    const form = document.getElementById('companyForm');
    const sipInput = document.getElementById('sip_number');
    const serviceInput = document.getElementById('service_dn');
    const sipNumber = sipInput.value.trim();
    const serviceDN = serviceInput.value.trim();

    console.log('SIP Number:', sipNumber);
    console.log('Service DN:', serviceDN);

    if (!sipNumber) {
        alert('Missing SIP number.');
        return;
    }

    // Ensure installation address is synced if checkbox is checked
    ensureInstallationAddressSynced();

    const formData = new FormData(form);
    formData.append('sip_number', sipNumber);
    formData.append('service_dn', serviceDN);
    formData.append('_token', '{{ csrf_token() }}');

    console.log('Sending PUT request to update company...');
    const url = '{{ route("dashboard.import.submit.put") }}'; // Use the correct PUT route name
    console.log('Generated URL:', url);
    console.log('Form data:', formData);

    // Prevent any form submission interference
    event.preventDefault();
    event.stopPropagation();

    // Try using XMLHttpRequest instead of fetch
    const xhr = new XMLHttpRequest();
    xhr.open('PUT', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('Accept', 'text/plain');
    xhr.onreadystatechange = function() {
        console.log('XHR readyState:', xhr.readyState);
        console.log('XHR status:', xhr.status);
        if (xhr.readyState === 4) {
            console.log('Response status:', xhr.status);
            const data = xhr.responseText;
            console.log('Response data:', data);
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(data);
                    if (response.success) {
                        showSuccessModal(response.message);
                        // Redirect to dashboard after successful update
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000);
                    } else {
                        throw new Error(response.message || 'Update failed');
                    }
                } catch (e) {
                    // Fallback for non-JSON responses
                    if (data.includes('successfully')) {
                        showSuccessModal('Company updated successfully!');
                        setTimeout(() => {
                            window.location.href = '{{ route("dashboard.index") }}';
                        }, 2000);
                    } else {
                        throw new Error(data || 'Failed to update company');
                    }
                }
            } else {
                throw new Error(data || 'Failed to update company');
            }
        }
    };
    xhr.onerror = function(err) {
        console.error('XHR Error:', err);
        alert('Error: ' + err.message);
    };
    xhr.send(formData);
}

function uploadDocuments(){
    const form=document.getElementById('companyForm');
    const formData=new FormData(form);
    
    // Get SIP number from input field, or from URL parameter as fallback
    let sipNumber = document.getElementById('sip_number').value;
    if (!sipNumber) {
        // Try to get from URL
        const urlParams = new URLSearchParams(window.location.search);
        sipNumber = urlParams.get('sip') || '';
    }
    
    const serviceDN = document.getElementById('service_dn').value;
    
    // Check if files are selected
    const fileInput = form.querySelector('input[type="file"]');
    if (!fileInput.files || fileInput.files.length === 0) {
        alert('Please select at least one file to upload.');
        return;
    }
    
    // Check total file size (client-side guard)
    const MAX_UPLOAD_BYTES = 500 * 1024 * 1024; // 500MB client-side limit (matches PHP upload_max_filesize)
    const totalSize = Array.from(fileInput.files).reduce((sum, file) => sum + file.size, 0);
    if (totalSize > MAX_UPLOAD_BYTES) {
        alert('Selected files exceed the 500 MB limit. Please upload fewer or smaller files.');
        return;
    }
    
    // Check if SIP number is provided
    if (!sipNumber) {
        alert('Please enter a SIP number or ensure it is in the URL.');
        return;
    }
    
    formData.append('sip_number', sipNumber);
    formData.append('service_dn', serviceDN);
    formData.append('_token', '{{ csrf_token() }}');

    // Use the Laravel upload handler
    fetch('{{ route("dashboard.import.submit") }}',{method:'POST',body:formData})
    .then(res=>res.text())
    .then(data=>{
        const normalized = (data || '').trim().toLowerCase();
        if (normalized.includes('success') || res.ok) {
            showSuccessModal('Document uploaded successfully!');
            form.reset();
            // Keep SIP number filled so user can add company afterward
            document.getElementById('sip_number').value = sipNumber;
        } else {
            alert('Error: ' + data);
        }
    }).catch(err=>alert('Error uploading documents: '+err));
}

function showSuccessModal(message) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('successModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'successModal';
        modal.className = 'popup-modal';
        modal.style.display = 'flex';
        modal.innerHTML = `
            <div class="popup-content">
                <h3>Success!</h3>
                <p>${message}</p>
                <button onclick="closeSuccessModal()">OK</button>
            </div>
        `;
        document.body.appendChild(modal);
    } else {
        modal.querySelector('p').textContent = message;
        modal.style.display = 'flex';
    }
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.style.display = 'none';
    }
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Import SIP Documents')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    font-family: Arial, sans-serif;
    background:#f4f6f8;
    padding:10px 15px;
    color:#003366;
}

h3 {
    margin-top:10px;
    margin-bottom:12px;
    border-bottom:1px solid #e0e3e7;
    padding-bottom:4px;
    font-size:17px;
    font-weight:600;
}

form,
.form-section {
    background:#fff;
    padding:16px 20px;
    border-radius:10px;
    max-width:980px;
    margin:8px auto;
    box-shadow:0 4px 14px rgba(0,0,0,0.06);
}

input,
select,
textarea {
    padding:6px 8px;
    margin:4px 0 10px 0;
    border-radius:5px;
    border:1px solid #ced4da;
    font-size:13px;
}

input[type=text],
input[type=number],
input[type=date],
input[type=email],
input[type=file] {
    width:100%;
    box-sizing:border-box;
}

textarea {
    width:100%;
    min-height:60px;
    resize:vertical;
}

button {
    padding:8px 14px;
    background:#003366;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-size:14px;
    margin-top:2px;
}

button:hover {
    background:#0055aa;
}

label {
    font-weight:bold;
    margin-top:4px;
    margin-bottom:2px;
    display:block;
    font-size:13px;
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

.section-row {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:6px;
}

.section-row > div {
    flex:1;
    min-width:220px;
}

.bottom-actions {
    max-width:980px;
    margin:10px auto 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:8px;
}

.bottom-actions button {
    min-width:170px;
}

.bottom-actions button.secondary {
    background: #f3f4f6;
    color: #003366;
    border: 1px solid #d1d5db;
}

.bottom-actions button.secondary:hover {
    background: #e5e7eb;
    border-color: #9ca3af;
}

/* Checkbox styling for same address */
.same-address-checkbox {
    margin: 10px 0;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 5px;
    border: 1px solid #e0e3e7;
}

.same-address-checkbox label {
    display: inline;
    font-weight: normal;
    margin: 0;
    cursor: pointer;
    font-size: 13px;
}

.same-address-checkbox input[type="checkbox"] {
    width: auto;
    margin: 0 8px 0 0;
    cursor: pointer;
}
</style>
@endpush

@section('content')


<!-- Success Popup Modals -->
<div id="successModal" class="popup-modal">
    <div class="popup-content">
        <h3>✅ Success!</h3>
        <p id="successModalMessage"></p>
        <button onclick="closeSuccessModal()">OK</button>
    </div>
</div>

<!-- SIP Number + Service/DN No -->
<div class="form-section">
    <div class="section-row">
        <div>
            <label for="sip_number">SIP Number: <span style="color:red;">*</span></label>
            <input type="text" name="sip_number" id="sip_number" placeholder="SIP001" required>
        </div>
        <div>
            <label for="service_dn">Service/DN No:</label>
            <input type="text" name="service_dn" id="service_dn" placeholder="DN001">
        </div>
    </div>
</div>

<!-- Main Form -->
<form id="companyForm">
    <input type="hidden" id="edit_mode" value="0">
    <!-- SIP PBX / SIP Trunk Type -->
    <div class="form-section">
        <h3>SIP PBX / SIP Trunk Type</h3>
        <select name="sip_type">
            <option value="">-- Select a Type --</option>
            <option value="Type I: Single DN with Multiple Sessions, Without Hunting">Type I: Single DN with Multiple Sessions, Without Hunting</option>
            <option value="Type II: Multiple DNs with Single Session (each session for single DN), with/without Hunting">Type II: Multiple DNs with Single Session (each session for single DN), with/without Hunting</option>
            <option value="Type III: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within single premises">Type III: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within single premises</option>
            <option value="Type IV: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within multiple premises">Type IV: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within multiple premises</option>
            <option value="Type V: Single DN with Multiple Sessions connected to Data Center / Cloud PBX">Type V: Single DN with Multiple Sessions connected to Data Center / Cloud PBX</option>
            <option value="Type VI: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within single premises">Type VI: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within single premises</option>
            <option value="Type VII: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within multiple premises">Type VII: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within multiple premises</option>
        </select>
    </div>

    <!-- Customer Information -->
    <div class="form-section">
        <h3>Customer Information</h3>
        <div class="section-row">
            <div>
                <label>Customer Name: <span style="color:red;">*</span></label>
                <input type="text" name="customer_name" required>
            </div>
        </div>
        <div class="section-row">
            <div>
                <label>Customer Type:</label>
                <input type="text" name="customer_type">
            </div>
            <div>
                <label>Name of Proprietor / Director:</label>
                <input type="text" name="name_of_proprietor">
            </div>
        </div>
        <div class="section-row">
            <div>
                <label>Company Registration No.:</label>
                <input type="text" name="company_reg_no">
            </div>
            <div>
                <label>Registration Date:</label>
                <input type="date" name="reg_date">
            </div>
        </div>
        <div class="section-row">
            <div>
                <label>PAN/VAT No.:</label>
                <input type="text" name="pan_no">
            </div>
        </div>
    </div>

    <!-- Permanent Address -->
    <div class="form-section">
        <h3>Permanent Address</h3>
        <div class="section-row">
            <div><label>Province:</label><input type="text" name="province_perm" id="province_perm" oninput="syncToInstallationIfChecked('province')"></div>
            <div><label>District:</label><input type="text" name="district_perm" id="district_perm" oninput="syncToInstallationIfChecked('district')"></div>
            <div><label>Municipality:</label><input type="text" name="municipality_perm" id="municipality_perm" oninput="syncToInstallationIfChecked('municipality')"></div>
        </div>
        <div class="section-row">
            <div><label>Ward:</label><input type="text" name="ward_perm" id="ward_perm" oninput="syncToInstallationIfChecked('ward')"></div>
            <div><label>Tole:</label><input type="text" name="tole_perm" id="tole_perm" oninput="syncToInstallationIfChecked('tole')"></div>
        </div>
    </div>

    <!-- Installation Address -->
    <div class="form-section">
        <h3>Installation Address</h3>
        <div class="same-address-checkbox">
            <input type="checkbox" id="sameAsPermanent" onchange="copyPermanentToInstallation()">
            <label for="sameAsPermanent">Same as Permanent Address</label>
        </div>
        <div class="section-row">
            <div><label>Province:</label><input type="text" name="province_install"></div>
            <div><label>District:</label><input type="text" name="district_install"></div>
            <div><label>Municipality:</label><input type="text" name="municipality_install"></div>
        </div>
        <div class="section-row">
            <div><label>Ward:</label><input type="text" name="ward_install"></div>
            <div><label>Tole:</label><input type="text" name="tole_install"></div>
        </div>
    </div>

    <!-- Contact Details -->
    <div class="form-section">
        <h3>Contact Details</h3>
        <div class="section-row">
            <div><label>Landline:</label><input type="text" name="landline"></div>
            <div><label>Mobile:</label><input type="text" name="mobile"></div>
        </div>
        <div class="section-row">
            <div><label>Email:</label><input type="email" name="email"></div>
            <div><label>Website:</label><input type="text" name="website"></div>
        </div>
    </div>

    <!-- Business Information -->
    <div class="form-section">
        <h3>Business Information</h3>
        <div class="section-row">
            <div><label>Objectives of the Company:</label><textarea name="objectives"></textarea></div>
        </div>
        <div class="section-row">
            <div><label>Purpose of SIP PBX / SIP Trunk:</label><textarea name="purpose"></textarea></div>
        </div>
        <div class="section-row">
            <div>
                <label>No. of Sessions:</label>
                <input type="number" name="sessions" min="0">
            </div>
        </div>
    </div>

    <!-- Authorization -->
    <div class="form-section">
        <h3>Authorization</h3>
        <div class="section-row">
            <div><label>Authorized Signature:</label><input type="text" name="authorized_signature"></div>
            <div><label>Signature Name:</label><input type="text" name="signature_name"></div>
        </div>
        <div class="section-row">
            <div><label>Position:</label><input type="text" name="position"></div>
            <div><label>Signature Date:</label><input type="date" name="signature_date"></div>
            <div><label>Seal:</label><input type="text" name="seal"></div>
        </div>
    </div>
</form>

<!-- Upload Scanned Documents -->
<div class="form-section">
    <h3>Upload Scanned Documents</h3>
    <form id="documentForm" enctype="multipart/form-data">
        @csrf
        <label>Select Documents:</label>
        <input type="file" name="documents[]" multiple>
        <button type="button" onclick="uploadDocuments()">Add Documents</button>
    </form>
</div>

<!-- Bottom Actions -->
<div class="bottom-actions">
    <button type="button" onclick="handleCompanySubmit()">
        Add Company
    </button>
    <button type="button" class="secondary" onclick="history.back()">Go Back</button>
</div>


<meta name="csrf-token" content="{{ csrf_token() }}">

<script>

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


// Popup Modal Functions
function showSuccessModal(message) {
    document.getElementById('successModalMessage').textContent = message;
    document.getElementById('successModal').style.display = 'flex';
}

function closeSuccessModal() {
    document.getElementById('successModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('successModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuccessModal();
    }
});

// Copy Permanent Address to Installation Address
function copyPermanentToInstallation() {
    const checkbox = document.getElementById('sameAsPermanent');
    if (checkbox.checked) {
        // Copy all permanent address fields to installation address fields
        document.querySelector('input[name="province_install"]').value = document.querySelector('input[name="province_perm"]').value;
        document.querySelector('input[name="district_install"]').value = document.querySelector('input[name="district_perm"]').value;
        document.querySelector('input[name="municipality_install"]').value = document.querySelector('input[name="municipality_perm"]').value;
        document.querySelector('input[name="ward_install"]').value = document.querySelector('input[name="ward_perm"]').value;
        document.querySelector('input[name="tole_install"]').value = document.querySelector('input[name="tole_perm"]').value;
        
        // Make installation address fields readonly when checked (readonly fields are still submitted)
        const installFields = document.querySelectorAll('input[name^="province_install"], input[name^="district_install"], input[name^="municipality_install"], input[name^="ward_install"], input[name^="tole_install"]');
        installFields.forEach(field => {
            field.readOnly = true;
            field.style.backgroundColor = '#f3f4f6';
        });
    } else {
        // Enable installation address fields when unchecked
        const installFields = document.querySelectorAll('input[name^="province_install"], input[name^="district_install"], input[name^="municipality_install"], input[name^="ward_install"], input[name^="tole_install"]');
        installFields.forEach(field => {
            field.readOnly = false;
            field.style.backgroundColor = '';
        });
    }
}

// Ensure installation address is synced before form submission
function ensureInstallationAddressSynced() {
    const checkbox = document.getElementById('sameAsPermanent');
    if (checkbox && checkbox.checked) {
        // Copy permanent address values to installation address fields before submission
        document.querySelector('input[name="province_install"]').value = document.querySelector('input[name="province_perm"]').value;
        document.querySelector('input[name="district_install"]').value = document.querySelector('input[name="district_perm"]').value;
        document.querySelector('input[name="municipality_install"]').value = document.querySelector('input[name="municipality_perm"]').value;
        document.querySelector('input[name="ward_install"]').value = document.querySelector('input[name="ward_perm"]').value;
        document.querySelector('input[name="tole_install"]').value = document.querySelector('input[name="tole_perm"]').value;
    }
}

// Sync individual field when permanent address changes (if checkbox is checked)
function syncToInstallationIfChecked(fieldType) {
    const checkbox = document.getElementById('sameAsPermanent');
    if (checkbox && checkbox.checked) {
        const permValue = document.getElementById(fieldType + '_perm').value;
        const installField = document.querySelector('input[name="' + fieldType + '_install"]');
        if (installField) {
            installField.value = permValue;
        }
    }
}

function handleCompanySubmit(){
    const isEdit = document.getElementById('edit_mode').value === '1';
    if (isEdit) {
        updateCompany();
    } else {
        addCompany();
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
    formData.append('_token', csrfToken);

    fetch('{{ route("dashboard.upload_docs") }}',{method:'POST',body:formData})
    .then(async res=>{
        const data = await res.text();
        if (!res.ok) throw new Error(data || 'Failed to save company');
        showSuccessModal('Company saved successfully!');
        // Reset form inputs but keep SIP/DN for document uploads
        form.reset();
        sipInput.value = sipNumber;
        serviceInput.value = serviceDN;
        // Reset checkbox if it was checked
        document.getElementById('sameAsPermanent').checked = false;
        copyPermanentToInstallation();
    })
    .catch(err=>alert('Error: '+err.message));
}

function updateCompany(){
    const form = document.getElementById('companyForm');
    const sipInput = document.getElementById('sip_number');
    const serviceInput = document.getElementById('service_dn');
    const sipNumber = sipInput.value.trim();
    const serviceDN = serviceInput.value.trim();

    if (!sipNumber) {
        alert('Missing SIP number.');
        return;
    }

    // Ensure installation address is synced if checkbox is checked
    ensureInstallationAddressSynced();

    const formData = new FormData(form);
    formData.append('sip_number', sipNumber);
    formData.append('service_dn', serviceDN);
    formData.append('_token', csrfToken);

    fetch('{{ route("dashboard.upload_docs") }}',{method:'POST',body:formData})
    .then(async res=>{
        const data = await res.text();
        if (!res.ok) throw new Error(data || 'Failed to update company');
        showSuccessModal('Company updated successfully!');
    })
    .catch(err=>alert('Error: '+err.message));
}

function uploadDocuments(){
    const form=document.getElementById('documentForm');
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
    formData.append('_token', csrfToken);

    // Use the existing upload handler
    fetch('{{ route("dashboard.upload_docs") }}',{method:'POST',body:formData})
    .then(res=>res.text())
    .then(data=>{
        const normalized = (data || '').trim().toLowerCase();
        if (normalized.includes('success')) {
            showSuccessModal('Document uploaded successfully!');
            form.reset();
            // Keep SIP number filled so user can add company afterward
            document.getElementById('sip_number').value = sipNumber;
        } else {
            alert('Error: ' + data);
        }
    }).catch(err=>alert('Error uploading documents: '+err));
}
</script>

@endsection

@extends('layouts.app')

@section('title', 'Nepal Telecom SIP PBX / SIP Trunk Application')

@section('content')
<form action="{{ route('form.submit') }}" method="POST" id="sipForm">
    @csrf
    <h2>Nepal Telecom SIP PBX / SIP Trunk Application</h2>

    <div class="section">
        <h3>SIP PBX / SIP Trunk Types</h3>
        <select name="sip_type" required>
            <option value="">-- Select a Type --</option>
            <option value="Type I">Type I: Single DN with Multiple Sessions, Without Hunting</option>
            <option value="Type II">Type II: Multiple DNs with Single Session (each session for single DN), with/without Hunting</option>
            <option value="Type III">Type III: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within single premises</option>
            <option value="Type IV">Type IV: Multiple DNs with Multiple Sessions (parent/child DN), with/without Hunting within multiple premises</option>
            <option value="Type V">Type V: Single DN with Multiple Sessions connected to Data Center / Cloud PBX</option>
            <option value="Type VI">Type VI: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within single premises</option>
            <option value="Type VII">Type VII: Multiple DNs (Min 100 DN / 1 DID block) with Multiple Sessions (Min 60 Sessions), within multiple premises</option>
        </select>
    </div>

    <div class="section">
        <h3>Customer Information</h3>
        <div class="two-column">
            <div><label>Customer Name: *</label><input type="text" name="customer_name" required></div>
            <div><label>Customer Type:</label><input type="text" name="customer_type"></div>
            <div><label>Name of Proprietor / Director:</label><input type="text" name="name_of_proprietor"></div>
            <div><label>Company Registration No.:</label><input type="text" name="company_reg_no"></div>
            <div><label>Registration Date:</label><input type="date" name="reg_date"></div>
            <div><label>PAN/VAT No.:</label><input type="text" name="pan_no"></div>
        </div>
    </div>

    <div class="section">
        <h3>Permanent Address</h3>
        <div class="two-column">
            <div><label>Province:</label><input type="text" name="province_perm"></div>
            <div><label>District:</label><input type="text" name="district_perm"></div>
            <div><label>Municipality:</label><input type="text" name="municipality_perm"></div>
            <div><label>Ward:</label><input type="text" name="ward_perm"></div>
            <div><label>Tole:</label><input type="text" name="tole_perm"></div>
        </div>
    </div>

    <div class="section">
        <h3>Installation Address</h3>
        <div class="two-column">
            <div><label>Province:</label><input type="text" name="province_install"></div>
            <div><label>District:</label><input type="text" name="district_install"></div>
            <div><label>Municipality:</label><input type="text" name="municipality_install"></div>
            <div><label>Ward:</label><input type="text" name="ward_install"></div>
            <div><label>Tole:</label><input type="text" name="tole_install"></div>
        </div>
    </div>

    <div class="section">
        <h3>Contact Details</h3>
        <div class="two-column">
            <div><label>Landline:</label><input type="text" name="landline"></div>
            <div><label>Mobile:</label><input type="text" name="mobile"></div>
            <div><label>Email:</label><input type="email" name="email"></div>
            <div><label>Website:</label><input type="text" name="website"></div>
        </div>
    </div>

    <div class="section">
        <h3>Business Information</h3>
        <label>SIP Number: *</label>
        <input type="text" name="sip_number" placeholder="e.g. SIP100" required style="width: 48%;">
        <label>Service/DN No:</label>
        <input type="text" name="service_dn" style="width: 48%;">
        <label>Objectives of the Company:</label>
        <textarea name="objectives"></textarea>
        <label>Purpose of SIP PBX / SIP Trunk:</label>
        <textarea name="purpose"></textarea>
        <div class="two-column">
            <div><label>No. of Sessions:</label><input type="number" name="sessions"></div>
            <div><label>No. of DN/DID:</label><input type="number" name="did"></div>
        </div>
    </div>

    <div class="section">
        <h3>Authorization</h3>
        <div class="two-column">
            <div><label>Authorized Signature:</label><input type="text" name="authorized_signature"></div>
            <div><label>Signature Name:</label><input type="text" name="signature_name"></div>
            <div><label>Position:</label><input type="text" name="position"></div>
            <div><label>Signature Date:</label><input type="date" name="signature_date"></div>
            <div><label>Seal:</label><input type="text" name="seal"></div>
        </div>
    </div>

    <button type="submit">Submit</button>
</form>

<p style="margin-top: 20px;"><a href="{{ route('login') }}">Login Page</a></p>

@push('styles')
<style>
body { padding: 20px; }
form {
    background-color: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 900px;
    margin: 0 auto 20px;
}
h2, h3 { color: #2c3e50; margin-bottom: 10px; }
h2 { text-align: center; margin-bottom: 30px; font-size: 28px; font-weight: 700; }
.section {
    border: 1px solid #dcdcdc;
    padding: 15px 20px;
    border-radius: 6px;
    margin-bottom: 20px;
    background-color: #f9fafb;
}
.section h3 { margin-top: 0; margin-bottom: 15px; font-size: 18px; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
label { display: block; font-weight: 600; margin-bottom: 5px; margin-top: 10px; }
input, select, textarea {
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
input[type="text"], input[type="email"], input[type="date"], input[type="number"] { width: 100%; }
textarea { width: 98%; resize: vertical; }
.two-column { display: flex; justify-content: space-between; flex-wrap: wrap; }
.two-column > div { flex: 0 0 48%; }
.two-column input, .two-column select { width: 100%; }
button {
    display: block;
    width: 100%;
    background-color: #072b42;
    color: #fff;
    border: none;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}
button:hover { background-color: #0e3149; }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('sipForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = e.target;
    var fd = new FormData(form);
    fetch(form.action, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/plain' } })
        .then(function(r) { return r.text().then(function(t) { return { ok: r.ok, text: t }; }); })
        .then(function(o) {
            if (o.ok) { alert('Application submitted successfully.'); form.reset(); }
            else alert('Error: ' + o.text);
        })
        .catch(function(err) { alert('Error: ' + err.message); });
});
</script>
@endpush
@endsection

<!-- @extends('layouts.app')

@section('title', 'Details - ' . ($company->company_name ?? ''))

@push('styles')
<style>
body { font-family: Arial, sans-serif; background: #eef2f6; }
.container { max-width: 1000px; margin: auto; background: #fff; padding: 25px 35px; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
h2 { text-align: center; color: #0a3d62; margin-bottom: 25px; border-bottom: 2px solid #0a3d62; padding-bottom: 5px; }
.section { margin-bottom: 20px; }
.section h3 { background: #0a3d62; color: #fff; padding: 6px 10px; border-radius: 5px; margin: 0 0 10px 0; font-size: 16px; }
.field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px 20px; }
.field { display: flex; gap: 5px; align-items: center; margin-bottom: 8px; }
.field label { font-weight: bold; width: 180px; color: #222; }
.field span { flex: 1; color: #333; }
.button-bar { text-align: center; margin-top: 25px; }
.button-bar a { text-decoration: none; color: white; background: #0a3d62; padding: 10px 18px; border-radius: 5px; margin: 0 8px; cursor: pointer; }
.button-bar a:hover { background: #062c49; color: white; }
.back-link { display: inline-block; margin-bottom: 15px; color: #072b42; }
</style>
@endpush

@section('content')
<a href="{{ route('dashboard.index') }}" class="back-link">← Back to Dashboard</a>

<div class="container">
    <h2>Details - {{ $company->company_name }}</h2>

    <div class="section">
        <h3>SIP & Customer Information</h3>
        <div class="field-grid">
            <div class="field"><label>SIP Type:</label><span>{{ $company->sip_type ?? '' }}</span></div>
            <div class="field"><label>Company Name:</label><span>{{ $company->company_name ?? '' }}</span></div>
            <div class="field"><label>Customer Type:</label><span>{{ $company->customer_type ?? '' }}</span></div>
            <div class="field"><label>Proprietor / Director:</label><span>{{ $company->proprietor_name ?? '' }}</span></div>
        </div>
    </div>

    <div class="section">
        <h3>Contact & Business Info</h3>
        <div class="field-grid">
            <div class="field"><label>Landline:</label><span>{{ $company->landline ?? '' }}</span></div>
            <div class="field"><label>Mobile:</label><span>{{ $company->mobile ?? '' }}</span></div>
            <div class="field"><label>Email:</label><span>{{ $company->email ?? '' }}</span></div>
            <div class="field"><label>Website:</label><span>{{ $company->website ?? '' }}</span></div>
            <div class="field"><label>No. of Sessions:</label><span>{{ $company->sessions ?? '' }}</span></div>
            <div class="field"><label>Objectives:</label><span>{{ $company->objectives ?? '' }}</span></div>
            <div class="field"><label>Purpose:</label><span>{{ $company->purpose ?? '' }}</span></div>
        </div>
    </div>

    <div class="section">
        <h3>Address</h3>
        <div class="field">
            <label>Permanent Address:</label>
            <span>{{ $company->address_perm ?? implode(', ', array_filter([$company->perm_province, $company->perm_district, $company->perm_municipality, $company->perm_ward ? 'Ward ' . $company->perm_ward : null, $company->perm_tole])) }}</span>
        </div>
        <div class="field">
            <label>Installation Address:</label>
            <span>{{ $company->address_install ?? implode(', ', array_filter([$company->inst_province, $company->inst_district, $company->inst_municipality, $company->inst_ward ? 'Ward ' . $company->inst_ward : null, $company->inst_tole])) }}</span>
        </div>
    </div>

    <div class="section">
        <h3>Company Details & Authorization</h3>
        <div class="field-grid">
            <div class="field"><label>Company Reg. No:</label><span>{{ $company->company_reg_no ?? '' }}</span></div>
            <div class="field"><label>Registration Date:</label><span>{{ $company->reg_date ? $company->reg_date->format('Y-m-d') : '' }}</span></div>
            <div class="field"><label>PAN / VAT No:</label><span>{{ $company->pan_no ?? '' }}</span></div>
            <div class="field"><label>Signature Name:</label><span>{{ $company->signature_name ?? '' }}</span></div>
            <div class="field"><label>Position:</label><span>{{ $company->position ?? '' }}</span></div>
            <div class="field"><label>Signature Date:</label><span>{{ $company->signature_date ? $company->signature_date->format('Y-m-d') : '' }}</span></div>
            <div class="field"><label>Seal:</label><span>{{ $company->seal ?? '' }}</span></div>
        </div>
    </div>

    <div class="button-bar">
        <a href="{{ route('dashboard.index') }}">Go Back</a>
    </div>
</div>
@endsection -->

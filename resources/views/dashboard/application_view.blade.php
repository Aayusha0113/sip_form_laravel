@extends('layouts.app')
@section('title', 'View Application')
@section('content')
<style>
    body {
        font-family: 'Arial', sans-serif;
        color: #333;
    }

    h2 {
        color: #0b4063;
        margin-bottom: 25px;
    }

    .section {
        background: #f9f9f9;
        padding: 20px 25px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 25px;
    }

    .section h3 {
        border-bottom: 2px solid #0b4063;
        padding-bottom: 5px;
        margin-bottom: 15px;
        color: #0b4063;
    }

    .field {
        display: flex;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .field label {
        font-weight: bold;
        width: 220px;
        color: #0b4063;
    }

    .field span {
        flex: 1;
    }

    a.btn {
        text-decoration: none;
        display: inline-block;
        background-color: #0b4063;
        color: #fff;
        padding: 10px 18px;
        border-radius: 6px;
        transition: background 0.3s;
    }

    a.btn:hover {
        background-color: #095082;
    }

    @media (max-width: 768px) {
        .field label {
            width: 100%;
            margin-bottom: 5px;
        }
    }
</style>

<h2>Application Details - {{ $application->customer_name }}</h2>

<div class="section">
    <h3>Customer Information</h3>
    <div class="field"><label>Customer Name:</label> <span>{{ $application->customer_name }}</span></div>
    <div class="field"><label>Customer Type:</label> <span>{{ $application->customer_type }}</span></div>
    <div class="field"><label>Proprietor / Director:</label> <span>{{ $application->name_of_proprietor }}</span></div>
    <div class="field"><label>Company Reg. No:</label> <span>{{ $application->company_reg_no }}</span></div>
    <div class="field"><label>Registration Date:</label> <span>{{ $application->reg_date }}</span></div>
    <div class="field"><label>PAN / VAT No:</label> <span>{{ $application->pan_no }}</span></div>
</div>

<div class="section">
    <h3>Address</h3>
    <div class="field"><label>Permanent Address:</label>
        <span>{{ $application->perm_province }}, {{ $application->perm_district }}, {{ $application->perm_municipality }}, Ward {{ $application->perm_ward }}, {{ $application->perm_tole }}</span>
    </div>
    <div class="field"><label>Installation Address:</label>
        <span>{{ $application->inst_province }}, {{ $application->inst_district }}, {{ $application->inst_municipality }}, Ward {{ $application->inst_ward }}, {{ $application->inst_tole }}</span>
    </div>
</div>

<div class="section">
    <h3>Contact Details</h3>
    <div class="field"><label>Landline:</label> <span>{{ $application->landline }}</span></div>
    <div class="field"><label>Mobile:</label> <span>{{ $application->mobile }}</span></div>
    <div class="field"><label>Email:</label> <span>{{ $application->email }}</span></div>
    <div class="field"><label>Website:</label> <span>{{ $application->website }}</span></div>
</div>

<div class="section">
    <h3>Business Info</h3>
    <div class="field"><label>SIP Type:</label> <span>{{ $application->sip_type }}</span></div>
    <div class="field"><label>No. of Sessions:</label> <span>{{ $application->sessions }}</span></div>
    <div class="field"><label>No. of DN/DID:</label> <span>{{ $application->did }}</span></div>
    <div class="field"><label>Objectives:</label> <span>{{ $application->objectives }}</span></div>
    <div class="field"><label>Purpose:</label> <span>{{ $application->purpose }}</span></div>
</div>

<div class="section">
    <h3>Authorization</h3>
    <div class="field"><label>Authorized Signature:</label> <span>{{ $application->authorized_signature }}</span></div>
    <div class="field"><label>Signature Name:</label> <span>{{ $application->signature_name }}</span></div>
    <div class="field"><label>Position:</label> <span>{{ $application->position }}</span></div>
    <div class="field"><label>Signature Date:</label> <span>{{ $application->signature_date }}</span></div>
    <div class="field"><label>Seal:</label> <span>{{ $application->seal }}</span></div>
</div>

<a href="{{ route('admin.client_apps') }}" class="btn btn-secondary">Back</a>

@endsection

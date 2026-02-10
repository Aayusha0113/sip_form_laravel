@extends('layouts.app') {{-- Or your main layout --}}

@section('title', 'Nepal Telecom Client Applications')

@section('content')
<header style="text-align:center; margin-bottom:20px;">
    <img src="{{ asset('logo.jpg') }}" alt="Nepal Telecom Logo" style="width:80px; height:auto;">
    <h2 style="color:#003366; font-weight:600;">Nepal Telecom Client Applications</h2>
</header>

<main style="max-width:1200px; margin:auto;">
<table style="width:100%; border-collapse: collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 4px 12px rgba(0,0,0,0.05); font-size:14px;">
    <thead>
        <tr style="background-color:#003366; color:#fff;">
            <th>ID</th>
            <th>Customer Name</th>
            <th>Type</th>
            <th>Sessions</th>
            <th>DN/DID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $app)
        <tr style="text-align:center;">
            <td>{{ $app->id }}</td>
            <td>{{ $app->customer_name }}</td>
            <td>{{ $app->sip_type }}</td>
            <td>{{ $app->sessions }}</td>
            <td>{{ $app->did }}</td>
            <td>{{ ucfirst($app->status) }}</td>
            <td>
                <a class="action-btn" href="{{ route('applications.view', $app->id) }}">View</a>
                <a class="action-btn" href="{{ route('applications.estimate', $app->id) }}">Estimate</a>
                <a class="action-btn" href="{{ route('applications.letter', $app->id) }}">Letter</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Floating Go Back Button -->
<a href="{{ url()->previous() }}" class="floating-btn" style="position:fixed; bottom:20px; right:20px; padding:12px 18px; border-radius:10px; background-color:#010f1cff; color:#fff; font-weight:600; text-decoration:none; box-shadow:0 4px 12px rgba(0,0,0,0.15);">
    <i class="fa fa-arrow-left me-2"></i> Go Back
</a>
</main>

<style>
.action-btn {
    padding: 6px 12px;
    border-radius:5px;
    font-size:13px;
    font-weight:600;
    margin:2px;
    border:1px solid #08386dff;
    cursor:pointer;
    transition:0.2s;
    background-color: #ebe05fdd; /* NTC yellow */
    color: #08386dff;
    text-decoration: none;
}
.action-btn:hover { background-color: #e6d94cdd; }
tr:nth-child(even) { background: #f8f9fa; }
tr:hover td { background: #e6f0fa; }
</style>
@endsection

@extends('layouts.app')

@section('title', 'View Client Applications')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f6fa;
        color: #333;
        margin: 0;
    }

    .admin-topbar {
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        padding: 14px 25px;
        margin-left: 250px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 500;
    }

    .admin-topbar .topbar-left h6 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #08386d;
        line-height: 1;
        display: block;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logout-btn {
        background: #fee2e2;
        color: #b91c1c;
        padding: 6px 12px;
        border-radius: 8px;
        text-decoration: none;
        cursor: pointer;
        border: none;
        font-weight: 500;
    }

    .logout-btn:hover {
        background: #fecaca;
    }

    .admin-main {
        margin-left: 250px;
        padding: 30px;
        min-height: calc(100vh - 110px);
        background: #f1f5f9;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .apps-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .apps-table th,
    .apps-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .apps-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .apps-table tr:hover {
        background-color: #f5f5f5;
    }

    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        margin-right: 5px;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-pending {
        background-color: #ffc107;
        color: #212529;
    }

    .status-approved {
        background-color: #28a745;
        color: white;
    }

    .status-rejected {
        background-color: #dc3545;
        color: white;
    }
</style>
@endpush

@section('content')

@include('dashboard.admin_sidebar_partial')

<div class="admin-topbar">
    <div class="topbar-left">
        <h6>View Client Applications</h6>
        <small>Nepal Telecom</small>
    </div>

    <div class="topbar-right">
        <span>
            <i class="fas fa-user-circle"></i>
            {{ Auth::user()->username ?? Auth::user()->name }}
        </span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div class="admin-main">
    <div class="content-card">
        <h3 class="fw-bold mb-4">Client Applications</h3>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="apps-table" id="applicationsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>SIP Number</th>
                        <th>Status</th>
                        <th>Submitted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->company_name ?? 'N/A' }}</td>
                            <td>{{ $application->contact_person ?? 'N/A' }}</td>
                            <td>{{ $application->phone ?? 'N/A' }}</td>
                            <td>{{ $application->email ?? 'N/A' }}</td>
                            <td>{{ $application->sip_number ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $application->status ?? 'pending' }}">
                                    {{ ucfirst($application->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>{{ $application->created_at ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('applications.view', $application->id) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($applications->isEmpty())
            <div class="text-center mt-4">
                <p class="text-muted">No client applications found.</p>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#applicationsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']]
    });
});
</script>

@endsection

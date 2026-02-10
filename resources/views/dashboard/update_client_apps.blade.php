@extends('layouts.app')

@section('title', 'Client Applications')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>


/* Topbar - ensure visible and above sidebar */
.admin-topbar {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    height: 64px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 22px;
    border-bottom: 1px solid #e6eef6;
    box-shadow: 0 2px 8px rgba(3,24,54,0.04);
    z-index: 1150;
}
.admin-topbar .topbar-left h6 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #08386d;
    line-height: 1;
    display: block;
}
.admin-topbar .topbar-left small { display:block; color:#6b7280; font-size:12px; }



/* Ensure admin-main leaves room for fixed topbar */
.admin-main {
    margin-left: 250px;
    padding: 110px 30px 40px;
    min-height: calc(100vh - 110px);
    background: #f1f5f9;
}

/* Card */
.content-card {
    background: #fff;
    border-radius: 12px;
    padding: 18px;
    box-shadow: 0 8px 20px rgba(3,24,54,0.05);
    overflow: visible;
}

/* Table */
.table-responsive { width: 100%; overflow-x: auto; border-radius: 8px; }
table { width:100%; border-collapse: collapse; font-size:14px; min-width: 800px; }
th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #eef2f6; vertical-align: middle; }
th { background: linear-gradient(90deg,#073a63,#003366); color: #fff; text-transform: uppercase; font-size:12px; }
tr:nth-child(even) td { background: #fbfdff; }
td.center { text-align:center; }

/* Action buttons */
.action-btn { padding:6px 10px; border-radius:6px; border:1px solid #08386d; background:#ebe05f; color:#08386d; font-weight:600; text-decoration:none; display:inline-block; margin-right:6px; }
.action-btn:hover { opacity:0.95; }
.small-btn { padding:6px 8px; font-size:13px; }

/* Floating back button */
.floating-btn {
    position: fixed; bottom: 20px; right: 20px; z-index:1300;
    padding:12px 16px; border-radius:50px; background:#08386d; color:#fff; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:8px;
    box-shadow: 0 8px 20px rgba(3,24,54,0.15);
}

/* Alerts */
.alert-success { padding:12px; background:#e6ffef; color:#1b7a4b; border-radius:8px; margin-bottom:12px; }
.alert-error { padding:12px; background:#fff1f2; color:#a61b2f; border-radius:8px; margin-bottom:12px; }

/* Responsive */
@media (max-width: 900px) {
    .admin-sidebar { width: 200px; }
    .admin-topbar { left: 200px; }
    .admin-main { margin-left: 200px; padding-top: 96px; }
    table { min-width: 700px; }
}
@media (max-width: 640px) {
    .admin-sidebar { display:none; }
    .admin-topbar { left: 0; }
    .admin-main { margin-left: 0; padding-top: 100px; }
    table { min-width: 0; font-size:13px; }
    th, td { padding: 10px; }
    .floating-btn { right: 12px; bottom: 12px; }
}
</style>
@endpush

@section('content')

<div class="admin-topbar" role="banner">
    <div class="topbar-left">
        <h6>Client Applications</h6>
        <small>Manage incoming client applications</small>
    </div>
    <div class="topbar-right">
        <span style="display:flex;align-items:center;gap:8px;"><i class="fas fa-user-circle" style="color:#08386d;font-size:18px"></i> {{ Auth::user()->username ?? Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </div>
</div>

<div class="admin-main">
    <div class="content-card">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <form id="deleteForm" method="POST" action="{{ route('admin.client_apps') }}">
            @csrf
            <input type="hidden" name="delete_selected" value="1">

            <div class="table-responsive">
                <table aria-describedby="client-apps-table">
                    <thead>
                        <tr>
                            <th class="center">Select</th>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Type</th>
                            <th class="center">Sessions</th>
                            <th>DN/DID</th>
                            <th class="center">Status</th>
                            <th class="center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                        <tr>
                            <td class="center"><input form="deleteForm" type="checkbox" name="selected[]" value="{{ $app->id }}"></td>
                            <td>{{ $app->id }}</td>
                            <td style="text-align:left;">{{ $app->customer_name }}</td>
                            <td>{{ $app->sip_type }}</td>
                            <td class="center">{{ $app->sessions }}</td>
                            <td>{{ $app->did }}</td>
                            <td class="center">{{ ucfirst($app->status ?? 'pending') }}</td>
                            <td class="center">
                                <form method="POST" action="{{ route('admin.client_apps') }}" style="display:inline;">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $app->id }}">
                                    <select name="status" style="padding:6px;border-radius:6px;border:1px solid #e4eef8;">
                                        <option value="pending" {{ ($app->status ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ ($app->status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ ($app->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <button type="submit" name="update_status" class="action-btn small-btn">Update</button>
                                </form>

                                <a href="#" class="action-btn small-btn">View</a>
                                <a href="#" class="action-btn small-btn">Estimate</a>
                                <a href="#" class="action-btn small-btn">Letter</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align:center; color:#6b7280;">No applications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top:12px; text-align:left;">
                <button type="submit" onclick="return confirm('Are you sure you want to delete selected applications?');" class="action-btn">Delete Selected</button>
            </div>
        </form>
    </div>
</div>

<a href="{{ route('user.dashboard') }}" class="floating-btn" title="Back to Dashboard"><i class="fa fa-arrow-left"></i></a>

@endsection
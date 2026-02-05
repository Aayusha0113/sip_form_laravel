@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* =========================
   RESET & GLOBAL
========================= */
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f1f5f9;
        margin: 0;
        padding: 0;
    }

    /* =========================
   SIDEBAR
========================= */
    .admin-sidebar {
        width: 250px;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 20px;
        background: linear-gradient(180deg, #003a8f, #0056b3);
        border-right: 1px solid #e5e7eb;
        color: #ffffff;
        overflow-y: auto;
        z-index: 1000;
    }

    .admin-sidebar .logo-container {
        text-align: center;
        padding: 20px 10px;
    }

    .admin-sidebar .logo {
        width: 90px;
        height: auto;
    }

    .admin-sidebar h5 {
        margin-top: 10px;
        margin-bottom: 0;
        font-weight: 700;
        font-size: 16px;
    }

    .admin-sidebar nav a {
        display: block;
        padding: 12px 20px;
        color: #e5e7eb;
        text-decoration: none;
        border-radius: 8px;
        margin: 4px 10px;
        transition: all 0.2s ease;
    }

    .admin-sidebar nav a:hover,
    .admin-sidebar nav a.active {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff;
    }

    .admin-sidebar nav a i {
        margin-right: 8px;
        width: 18px;
    }

    /* =========================
   TOPBAR
========================= */
  /* TOPBAR: fixed to the right of the 250px sidebar and full-width to the screen edge */
.admin-topbar {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    height: 64px;
    background: #ffffff;
    border-bottom: 1px solid #e5e7eb;
    padding: 14px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1150;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Ensure topbar title is visible and aligned */
.topbar-left h6 {
    margin: 0;
    font-weight: 600;
    color: #111827;
    line-height: 1;
}
.topbar-left small {
    color: #6b7280;
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
        font-weight: 500;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .logout-btn:hover {
        background: #fecaca;
        color: #b91c1c;
    }

    /* =========================
   MAIN CONTENT
========================= */
    .admin-main {
        position: fixed;
         left: 200px;
        padding: 80px;
        min-height: 100vh;
    }
         /* .admin-main {
        position: fixed;
        top: 0px;
        left: 180px;
        right: 0;
        margin-left: 20px;
        padding: 80px;
        min-height: 100vh;
    } */

    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .admin-header h3 {
        margin: 0;
        font-weight: 700;
        color: #111827;
    }

    /* =========================
   CONTENT CARD
========================= */
    .content-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 25px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .content-card h4 {
        margin-top: 0;
        margin-bottom: 15px;
        font-weight: 700;
        color: #111827;
    }

    /* =========================
   FORMS
========================= */
    .content-card input,
    .content-card select {
        width: 100%;
        padding: 12px;
        margin-bottom: 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #f9fafb;
        font-size: 14px;
    }

    .content-card button {
        background: #0071bc;
        color: #ffffff;
        padding: 12px 18px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .content-card button:hover {
        background: #005a93;
    }

    /* =========================
   PERMISSIONS
========================= */
    .permissions-section {
        margin-top: 15px;
        padding: 12px;
        background: #f9fafb;
        border-radius: 8px;
    }

    .permissions-section p {
        margin: 0 0 10px 0;
        font-weight: 600;
        color: #111827;
    }

    .permissions-section label {
        display: inline-block;
        margin-right: 15px;
        margin-bottom: 8px;
        color: #374151;
        ;
    }

    /* =========================
   STATS
========================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .stat-card {
        background: linear-gradient(135deg, #003366, #004c97);
        color: #ffffff;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        font-weight: 600;
    }

    .stat-card div:first-child {
        font-size: 28px;
        margin-bottom: 5px;
    }

    .stat-card small {
        font-size: 12px;
        opacity: 0.9;
    }

    /* =========================
   ALERTS
========================= */
    .alert-success {
        background: #e6ffed;
        color: #065f46;
        padding: 12px 14px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #10b981;
    }

    .alert-error {
        background: #fff1f2;
        color: #7f1d1d;
        padding: 12px 14px;
        border-radius: 8px;
        margin-bottom: 15px;
        border-left: 4px solid #ef4444;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 18px;
    }

    /* =========================
   RESPONSIVE
========================= */
    @media (max-width: 768px) {
        .admin-sidebar {
            width: 200px;
        }

        .admin-topbar,
        .admin-main {
           
            margin-left: 200px;
        }

        .admin-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .topbar-right {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-sidebar">
    <div class="logo-container">
        @if(file_exists(public_path('logo.jpg')))
        <img src="{{ asset('logo.jpg') }}" alt="NTC Logo" class="logo">
        @endif
        <h5>NTC Admin</h5>
    </div>
<nav>
    <a href="{{ route('admin.dashboard') }}"
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="fa fa-user-plus me-2"></i> Add User
    </a>

    <a href="{{ route('dashboard.user') }}"
       class="{{ request()->routeIs('dashboard.user') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Users
    </a>

    <a href="{{ route('admin.activities') }}"
       class="{{ request()->routeIs('admin.activities') ? 'active' : '' }}">
        <i class="fas fa-list"></i> Activities
    </a>

    <a href="{{ route('dashboard.index') }}"
       class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i> SIP Docs
    </a>

    <a href="{{ route('admin.client_apps') }}"
       class="{{ request()->routeIs('admin.client_apps') ? 'active' : '' }}">
        <i class="fas fa-briefcase"></i> Client Apps
    </a>
</nav>

</div>

<div class="admin-topbar">
    <div class="topbar-left">
        <h6>Admin Dashboard</h6>
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
    <div class="admin-header">
        <h3>Welcome, {{ Auth::user()->username ?? Auth::user()->name }}</h3>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert-error">
        <ul>
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="content-card">
        <h4>Add New User</h4>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <input type="text" name="username" placeholder="Enter username" value="{{ old('username') }}" required>
            <input type="password" name="password" placeholder="Enter password" required>

            <input type="email" name="email" placeholder="Email (optional)" value="{{ old('email') }}">
            <select name="role" required>
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <div class="permissions-section">
                <p>Permissions:</p>
                <label><input type="checkbox" name="permissions[]" value="view_logs"> View Logs</label>
                <label><input type="checkbox" name="permissions[]" value="upload_docs"> Upload Docs</label>
                <label><input type="checkbox" name="permissions[]" value="manage_users"> Manage Users</label>
                <label><input type="checkbox" name="permissions[]" value="view_sip_docs"> View SIP Docs</label>
                <label><input type="checkbox" name="permissions[]" value="update_sip_docs"> Update SIP Docs</label>
                <label><input type="checkbox" name="permissions[]" value="update_client_apps"> Update Client Apps</label>
                <label><input type="checkbox" name="permissions[]" value="view_client_apps"> View Client Apps</label>
            </div>


            <div style="text-align:right; margin-top:15px;">
                <button type="submit">Add User</button>
            </div>
        </form>
    </div>
</div>
</div>

@endsection
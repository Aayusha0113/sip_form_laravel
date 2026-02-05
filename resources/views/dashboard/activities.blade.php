@extends('layouts.app')

@section('title', 'User Activities')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* Reuse admin styles */
* { box-sizing: border-box; }
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f1f5f9;
    margin: 0;
    padding: 0;
}

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
}

.admin-sidebar h5 {
    margin-top: 10px;
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
    min-height: 100vh;
}

.content-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.content-card h4 {
    margin: 0 0 15px 0;
    font-weight: 700;
}

/* Pagination Styles */
.pagination {
    display: flex !important;
    gap: 8px !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 15px 0 0 0 !important;
    justify-content: flex-end !important;
}
.pagination li { display: inline-block !important; }
.pagination li a,
.pagination li span {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 6px 10px !important;
    min-width: 36px !important;
    height: 34px !important;
    border-radius: 6px !important;
    border: 1px solid #e5e7eb !important;
    background: #fff !important;
    color: #0b2940 !important;
    font-size: 13px !important;
}
.pagination li a:hover { background: #f1f5f9 !important; }
.pagination li.active span {
    background: #0071bc !important;
    color: #fff !important;
    border-color: #0071bc !important;
}
.pagination li.disabled span {
    color: #9aa3ad !important;
    background: #fff !important;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
table th, table td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
    text-align: left;
}
table th {
    background: #003366;
    color: #fff;
    font-weight: 600;
}
table tbody tr:nth-child(even) {
    background: #f9fafb;
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

    <a href="{{ route('dashboard.index') }}"
       class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
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
        <h6>Activities Log</h6>
        <small>User Activity Tracking</small>
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
        <h4>User Activities</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Activity</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $act)
                    <tr>
                        <td>{{ $act->id }}</td>
                        <td>{{ $act->user->username ?? $act->user->name ?? '—' }}</td>
                        <td>{{ $act->activity }}</td>
                        <td>{{ optional($act->activity_time)->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#6b7280;">No activities yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:15px;">
            <div class="pagination" aria-label="Pagination">
    @if($activities->onFirstPage())
        <span class="disabled" aria-disabled="true">Previous</span>
    @else
        <a href="{{ $activities->previousPageUrl() }}" rel="prev">‹</a>
    @endif

    @foreach(range(1, $activities->lastPage()) as $page)
        @if($page == $activities->currentPage())
            <span class="active">{{ $page }}</span>
        @else
            <a href="{{ $activities->url($page) }}">{{ $page }}</a>
        @endif
    @endforeach

    @if($activities->hasMorePages())
        <a href="{{ $activities->nextPageUrl() }}" rel="next">›</a>
    @else
        <span class="disabled" aria-disabled="true">Next</span>
    @endif
</div>
        </div>
    </div>
</div>
@endsection
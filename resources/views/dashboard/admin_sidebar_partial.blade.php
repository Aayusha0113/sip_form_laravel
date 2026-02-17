<style>
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

.topbar-left h6 {
    margin-bottom: 2px;
    font-weight: 600;
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

</style>
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

    <a href="{{ route('dashboard.import.form') }}"
       class="{{ request()->routeIs('dashboard.import.form') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i> New SIP Line
    </a>

    <a href="{{ route('admin.client_apps') }}"
       class="{{ request()->routeIs('admin.client_apps') ? 'active' : '' }}">
        <i class="fas fa-briefcase"></i> New SIP Approve
    </a>
</nav>

</div>
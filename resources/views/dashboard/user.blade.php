@extends('layouts.app')

@section('title', 'Users Management')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

    /* it will start the main content living margin at left for the side bar and padding to leave space for the fixed topbar */
    .admin-main {
        margin-left: 250px;
        padding: 30px;
        min-height: calc(100vh - 110px);
        background: #f1f5f9;
    }


    .user-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .user-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    .user-card h5 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .permission-badge {
        display: inline-block;
        background: #e3f2fd;
        color: #1976d2;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 11px;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .action-btn {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-right: 8px;
        border: none;
        cursor: pointer;
    }

    .action-btn.edit {
        background: #007bff;
        color: white;
    }

    .action-btn.edit:hover {
        background: #0056b3;
    }

    .action-btn.delete {
        background: #dc3545;
        color: white;
    }

    .action-btn.delete:hover {
        background: #bd2130;
    }

    .content-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('content')

@include('dashboard.admin_sidebar_partial')


<div class="admin-topbar">
    <div class="topbar-left">
        <h6>User Management</h6>
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
    <h3 class="fw-bold mb-4">
        Welcome, {{ Auth::user()->username ?? Auth::user()->name }}
    </h3>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10">
        <!-- Users Section -->
        @if($section == 'users')
        <div class="content-card section mt-3">
            <h4 class="fw-bold mb-3">Users</h4>
            <div class="user-cards">
                @foreach($users as $user)
                <div class="user-card">
                    <h5>{{ $user->username }}</h5>
                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                    <div style="margin-top:6px;">
                        @if(!empty($user->permissions))
                        @php
                        $perms = explode(',', $user->permissions);
                        @endphp
                        @foreach($perms as $p)
                        <span class="permission-badge">{{ ucwords(str_replace('_', ' ', $p)) }}</span>
                        @endforeach
                        @endif
                    </div>
                    <div style="margin-top:12px;">
                        <a href="{{ route('users.edit', $user->id) }}" class="action-btn edit">Edit</a>

                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline;" onsubmit="return confirmDelete('{{ $user->username }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
</div>
</div>


<script>
    function confirmDelete(username) {
        return confirm('Are you sure you want to delete ' + username + '?');
    }
</script>
@endsection
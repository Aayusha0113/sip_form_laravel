@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            @include('dashboard.admin_sidebar_partial')
        </div>
        
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
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
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
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
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
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}
</style>

<script>
function confirmDelete(username) {
    return confirm('Are you sure you want to delete ' + username + '?');
}
</script>
@endsection
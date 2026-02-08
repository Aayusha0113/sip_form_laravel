@extends('layouts.app')

@section('title', 'User Dashboard')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<style>
    body { background: #f4f5f7; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 12px; padding: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .card-header { display: flex; align-items: center; margin-bottom: 20px; }
    .logo { width: 80px; margin-right: 15px; }
    .header-text h3 { margin:0; font-size:1.5rem; }
    .header-text h5 { margin:0; font-size:1rem; color:#555; }

    .action-btns { display: flex; flex-direction: column; align-items: center; margin-top: 20px; }
    .action-btns button {
        margin: 5px 0;
        width: 260px;
        background-color: #4a90e2;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 6px;
        cursor: pointer;
    }
    .action-btns button:hover { background-color: #3b78c0; }
    .section-btn.active { background-color: #375a7f; }

    .section { margin-top: 20px; display: none; padding-top: 15px; border-top: 1px solid #ddd; }

    .user-cards { display: flex; flex-wrap: wrap; gap: 15px; }
    .user-card { width: 220px; border: 1px solid #ddd; border-radius: 8px; padding: 12px; }
    .badge-role { background-color: #555; color: white; }
    .badge-perm { background-color: #e0e0e0; color: #555; }
    .btn-edit { background-color: #ffc107; color: #000; border: none; }
    .btn-edit:hover { background-color: #e0a800; }
</style>
@endpush

@section('content')
<div class="container mt-5">
    <div class="card">

        <!-- Header -->
        <div class="card-header">
            <img src="{{ asset('logo.jpg') }}" alt="Logo" class="logo">
            <div class="header-text">
                <h3>Welcome, {{ auth()->user()->username }}</h3>
                <h5>NTC User Dashboard</h5>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Action Buttons -->
        <div class="action-btns">
            @foreach($userPermissions as $permission)
                <button class="section-btn" data-target="{{ $permission }}">
                    {{ ucfirst(str_replace('_', ' ', $permission)) }}
                </button>
            @endforeach
        </div>

        <!-- USER ACTIVITIES -->
        @if(in_array('dashboard_activities', $userPermissions))
        <div id="dashboard_activities" class="section">
            <h4 class="fw-bold mb-3">User Activities</h4>
            <table class="table table-striped" id="activitiesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Activity</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $activity)
                        <tr>
                            <td>{{ $activity->id }}</td>
                            <td>{{ $activity->user->username }}</td>
                            <td>{{ $activity->activity }}</td>
                            <td>{{ $activity->activity_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- UPLOAD DOCS -->
        @if(in_array('upload_docs', $userPermissions))
        <div id="upload_docs" class="section">
            <p>Upload Documents section coming soon...</p>
        </div>
        @endif

        <!-- UPDATE SIP DOCS -->
        @if(in_array('update_sip_docs', $userPermissions))
        <div id="update_sip_docs" class="section">
            <p>Update SIP Documents section coming soon...</p>
        </div>
        @endif

        <!-- VIEW CLIENT APPS -->
        @if(in_array('view_client_apps', $userPermissions))
        <div id="view_client_apps" class="section">
            <a href="{{ asset('View_client.php') }}" class="btn btn-primary">
                Go to Client Apps
            </a>
        </div>
        @endif

        <!-- MANAGE USERS -->
        @if(in_array('manage_users', $userPermissions))
        <div id="manage_users" class="section">
            <h4 class="fw-bold mb-3">Manage Users</h4>

            <!-- Add User -->
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="col">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <select name="role" class="form-control mb-2">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>

                <label>Permissions:</label><br>
                @foreach($allPermissions as $perm)
                    <label class="mx-2">
                        <input type="checkbox" name="permissions[]" value="{{ $perm }}">
                        {{ $perm }}
                    </label>
                @endforeach

                <br><br>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>

            <hr>

            <!-- User Cards -->
            <div class="user-cards">
                @foreach($allUsers as $user)
                    <div class="user-card">
                        <h6>{{ $user->username }}</h6>
                        <span class="badge badge-role">{{ ucfirst($user->role) }}</span><br><br>

                        Permissions:<br>
                        @foreach($user->permissions ?? [] as $perm)
                            <span class="badge badge-perm">{{ $perm }}</span>
                        @endforeach

                        <br><br>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-edit">
                            Edit
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    let tableInitialized = false;

    $('.section-btn').on('click', function () {
        const target = $(this).data('target');

        $('.section-btn').removeClass('active');
        $(this).addClass('active');

        $('.section').not('#' + target).slideUp();
        $('#' + target).slideToggle(function () {
            if (target === 'dashboard_activities' && !tableInitialized) {
                $('#activitiesTable').DataTable();
                tableInitialized = true;
            }
        });
    });
});
</script>
@endsection

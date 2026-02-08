@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card p-4">
        <div class="card-header">
            <i class="fa fa-user-edit me-2"></i>Edit User: {{ $user->username }}
        </div>
        <div class="card-body">
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')
                
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control mb-3" 
                       value="{{ old('username', $user->username) }}" required>
                
                <label class="form-label">New Password (leave blank to keep current)</label>
                <input type="password" id="passwordField" name="password" 
                       class="form-control mb-2" placeholder="Enter new password">
                
                <div class="showpass-box mb-3">
                    <span>Type a new password if you want to change it</span>
                    <span>
                        <input type="checkbox" id="showPasswordToggle"> Show
                    </span>
                </div>
                
                <label class="form-label">Role</label>
                <select name="role" class="form-select mb-3" required>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                
                <label class="form-label">Permissions:</label>
                <div class="permissions mb-4">
                    @php
                        $user_permissions = is_array($user->permissions) ? $user->permissions : explode(',', $user->permissions);
                    @endphp
                    
                    @foreach($allPermissions as $perm)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" 
                                   name="permissions[]" value="{{ $perm }}"
                                   {{ in_array($perm, $user_permissions) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                {{ ucwords(str_replace('_', ' ', $perm)) }}
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 d-flex">
                    <button type="submit" class="btn btn-success flex-grow-1 me-2">
                        <i class="fa fa-save me-2"></i> Save Changes
                    </button>
                     
                    <button type="button" class="btn btn-danger" id="deleteBtn">
                        <i class="fa fa-trash me-2"></i> Delete User
                    </button>
                </div>
            </form>
            
            <!-- Separate form for delete -->
            <form id="deleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

<!-- Back Button -->
<a href="{{ route('dashboard.user') }}" class="btn btn-secondary floating-btn">
    <i class="fa fa-arrow-left me-2"></i> Go Back
</a>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Show/hide password
const passField = document.getElementById("passwordField");
const toggle = document.getElementById("showPasswordToggle");
toggle.addEventListener('change', () => {
    passField.type = toggle.checked ? 'text' : 'password';
});

// Delete confirmation
document.getElementById('deleteBtn').addEventListener('click', function(){
    Swal.fire({
        title: 'Are you sure?',
        text: "This user will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if(result.isConfirmed){
            document.getElementById('deleteForm').submit();
        }
    })
});
</script>

<style>
/* Add your CSS styles here */
.floating-btn {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    padding: 12px 18px;
    font-weight: 600;
    border-radius: 10px;
    background-color: #030f1aff;
    color: #fff;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}
.floating-btn:hover {
    background-color: #101010ff;
    color: #fff;
}
.showpass-box {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    color: #6b7280;
}
</style>
@endsection
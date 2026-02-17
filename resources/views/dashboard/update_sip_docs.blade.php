@extends('layouts.app')

@section('title', 'Update SIP Documents')

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
        padding: 0px;
        margin-left: 0px;
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

    .sip-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .sip-table th,
    .sip-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .sip-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
    }

    .sip-table tr:hover {
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

    .btn-warning {
        background: #ffc107;
        color: #212529;
    }

    .btn-warning:hover {
        background: #e0a800;
    }

    .btn-danger {
        background: #dc3545;
        color: white;
    }

    .btn-danger:hover {
        background: #c82333;
    }

    .search-box {
        margin-bottom: 20px;
    }

    .search-box input {
        width: 300px;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')



<div class="admin-topbar">
    <div class="topbar-left">
        <h6>Update SIP Documents</h6>
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
        <h3 class="fw-bold mb-4">Update SIP Documents</h3>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by SIP number or company name..." onkeyup="filterTable()">
        </div>

        <div class="table-responsive">
            <table class="sip-table" id="sipTable">
                <thead>
                    <tr>
                        <th>SIP Number</th>
                        <th>Company Name</th>
                        <th>Contact Person</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $company)
                        <tr>
                            <td>{{ $company->sip_number }}</td>
                            <td>{{ $company->company_name }}</td>
                            <td>{{ $company->contact_person ?? 'N/A' }}</td>
                            <td>{{ $company->phone ?? 'N/A' }}</td>
                            <td>{{ $company->email ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('dashboard.show', $company->sip_number) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('dashboard.import.form', $company->sip_number) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> Edit
                                </a>
                                
                                <button class="btn btn-danger" onclick="deleteCompany('{{ $company->sip_number }}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($companies->isEmpty())
            <div class="text-center mt-4">
                <p class="text-muted">No SIP documents found.</p>
            </div>
        @endif
    </div>
</div>

<script>
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('sipTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            if (cellText.toLowerCase().includes(filter)) {
                found = true;
                break;
            }
        }

        rows[i].style.display = found ? '' : 'none';
    }
}

function editCompany(sipNumber) {
    // Placeholder for edit functionality
    alert('Edit functionality for SIP ' + sipNumber + ' will be implemented.');
}

function deleteCompany(sipNumber) {
    if (confirm('Are you sure you want to delete SIP ' + sipNumber + '?')) {
        // Submit delete request via AJAX
        fetch('/companies/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ sip: sipNumber })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the company.');
        });
    }
}
</script>

@endsection

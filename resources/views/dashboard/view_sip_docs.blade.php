@extends('layouts.app')

@section('title', 'NTC SIP Documents Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f6fa;
        color: #333;
        margin: 0;
    }




    .admin-topbar {
        position: fixed;
        top: 0;
        left: 250px;
        right: 0;
        height: 56px;
        /* smaller */
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
        border-bottom: 1px solid #e5e7eb;
        z-index: 1100;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-topbar img {
        width: 36px;
        /* smaller logo */
        height: auto;
    }

    .topbar-title {
        font-size: 15px;
        font-weight: 700;
        color: #08386d;
        white-space: nowrap;
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
        padding: 110px 30px 40px;
        min-height: calc(100vh - 110px);
        background: #f1f5f9;
    }

    /* Stats Section */
    .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-box {
        background: white;
        padding: 24px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #2c5aa0;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #2c5aa0;
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 13px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Controls Section */
    .controls-section {
        background: white;
        padding: 24px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
    }

    .controls-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a3a52;
        margin: 0 0 20px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #2c5aa0;
        padding-bottom: 12px;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 700;
        color: #1a3a52;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    select,
    input[type="text"] {
        padding: 10px 12px;
        border-radius: 4px;
        border: 1px solid #ddd;
        color: #333;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.3s ease;
        background-color: white;
    }

    select:hover,
    input[type="text"]:hover {
        border-color: #bbb;
    }

    select:focus,
    input[type="text"]:focus {
        outline: none;
        border-color: #2c5aa0;
        box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }

    /* Table Section */
    .table-section {
        background: white;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 100px;
    }

    .table-wrapper {
        overflow-x: auto;
        position: relative;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    thead {
        background-color: #1a3a52;
        color: white;
    }

    th {
        padding: 14px 12px;
        text-align: center;
        font-weight: 700;
        letter-spacing: 0.3px;
        font-size: 11px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    td {
        padding: 14px 12px;
        text-align: center;
        border-bottom: 1px solid #f0f0f0;
    }

    tbody tr {
        transition: all 0.3s ease;
    }

    tbody tr:hover {
        background-color: #f9f9f9;
        cursor: pointer;
    }

    tbody tr.selected {
        background-color: #fff3e0;
        box-shadow: inset 0 0 0 2px #2c5aa0;
    }

    /* Action Cell */
    .action-cell {
        display: flex;
        justify-content: center;
        gap: 6px;
    }

    .preview-btn {
        padding: 7px 14px;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        background-color: #2c5aa0;
        color: white;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
    }

    .preview-btn:hover {
        background-color: #1a3a52;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(44, 90, 160, 0.3);
    }

    /* Action Popover */
    .action-popover {
        position: fixed;
        display: none;
        flex-wrap: nowrap;
        gap: 8px;
        padding: 12px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 30;
        animation: slideIn 0.2s ease;
        min-width: 230px;
    }

    .action-popover button {
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .action-edit {
        background-color: #2c5aa0;
        color: white;
    }

    .action-edit:hover {
        background-color: #1a3a52;
        transform: translateY(-1px);
    }

    .action-delete {
        background-color: #d32f2f;
        color: white;
    }

    .action-delete:hover {
        background-color: #b71c1c;
        transform: translateY(-1px);
    }

    .action-cancel {
        background-color: #999;
        color: white;
    }

    .action-cancel:hover {
        background-color: #777;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 40;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: white;
        padding: 30px;
        border-radius: 6px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        max-width: 420px;
        text-align: center;
        animation: modalSlide 0.3s ease;
    }

    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal h3 {
        color: #d32f2f;
        margin: 0 0 10px 0;
        font-size: 18px;
    }

    .modal p {
        margin: 15px 0 25px 0;
        color: #666;
        font-size: 14px;
        line-height: 1.6;
    }

    .modal-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .modal-actions button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 13px;
        min-width: 100px;
    }

    .modal-confirm {
        background-color: #d32f2f;
        color: white;
    }

    .modal-confirm:hover {
        background-color: #b71c1c;
        transform: translateY(-1px);
    }

    .modal-cancel {
        background-color: #e0e0e0;
        color: #333;
    }

    .modal-cancel:hover {
        background-color: #d0d0d0;
    }

    .preview-btn {
        padding: 8px 14px;
        border-radius: 5px;
        font-weight: 600;
        text-decoration: none;
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .preview-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
    }

    .floating-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #2c5aa0;
        color: white;
        padding: 14px 18px;
        border-radius: 50%;
        text-decoration: none;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 18px;
    }

    .floating-btn:hover {
        background-color: #1a3a52;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .floating-btn.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.3s, visibility 0.3s;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar-header {
            flex-direction: column;
            gap: 12px;
            padding: 12px;
        }

        .navbar-brand h1 {
            font-size: 18px;
        }

        .navbar-user {
            width: 100%;
            justify-content: space-between;
            gap: 10px;
        }

        .user-info {
            font-size: 11px;
        }

        table th,
        table td {
            padding: 10px 6px;
            font-size: 11px;
        }

        th {
            font-size: 9px;
        }

        .stat-box {
            padding: 16px;
        }

        .stat-number {
            font-size: 24px;
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .action-popover {
            min-width: 200px;
        }

        .action-popover button {
            padding: 6px 10px;
            font-size: 11px;
        }
    }
</style>
@endpush

@section('content')


<div class="admin_layout">
    <div class="admin-topbar">

        <div class="topbar-left">

            <img src="logo.jpg" alt="NTC Logo">
            <span>NTC SIP Documents Dashboard</span>
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
</div>
<div class="admin-main">
    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stat-box stat-1">
            <div class="stat-number">{{ $totalCompanies }}</div>
            <div class="stat-label"><i class="fas fa-building"></i> Total Companies</div>
        </div>
        <div class="stat-box stat-2">
            <div class="stat-number">{{ $totalSIPs }}</div>
            <div class="stat-label"><i class="fas fa-phone"></i> Total SIP Numbers</div>
        </div>
        <div class="stat-box stat-3">
            <div class="stat-number">{{ $totalFiles }}</div>
            <div class="stat-label"><i class="fas fa-file"></i> Total Files</div>
        </div>
    </div>

    @php
    // build list of distinct company names for filter
    $companyList = $companies->pluck('company_name')->filter()->unique()->sort()->values();
    @endphp

    <!-- Filter Controls -->
    <div class="controls-section">
        <h3 class="controls-title"><i class="fas fa-filter"></i> Filters & Search</h3>
        <div class="filter-row">
            <div class="filter-group">
                <label for="companyFilter"><i class="fas fa-building"></i> Filter by Company</label>
                <select id="companyFilter">
                    <option value="">-- All Companies --</option>
                    @foreach($companyList as $c)
                    <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="searchOption"><i class="fas fa-search"></i> Search by</label>
                <select id="searchOption">
                    <option value="file">File.No</option>
                    <option value="sip">SIP Number</option>
                    <option value="dn">D.N</option>
                    <option value="company">Company Name</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="searchInput"><i class="fas fa-keyboard"></i> Keyword Search</label>
                <input type="text" id="searchInput" placeholder="Type to search...">
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <div class="table-wrapper">
            <table id="docsTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-file-alt"></i> File.No</th>
                        <th><i class="fas fa-phone"></i> SIP Number</th>
                        <th><i class="fas fa-users"></i> D.N</th>
                        <th><i class="fas fa-building"></i> Company Name</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companies as $row)
                    @php
                    $originalSip = $row->sip_number ?? '';
                    $displaySip = 'SIP' . preg_replace('/^sip\s*/i', '', ($originalSip ?? ''));
                    @endphp
                    <tr data-original-sip="{{ $originalSip }}">
                        <td><strong>{{ $row->file_no ?? '-' }}</strong></td>
                        <td>{{ $displaySip }}</td>
                        <td>{{ $row->DN ?? '-' }}</td>
                        <td>{{ $row->company_name }}</td>
                        <td>
                            <div class="action-cell">
                                <a href="{{ route('dashboard.show', $row->sip_number) }}" class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>


</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        function updateTableDisplay() {
            const companyFilter = document.getElementById('companyFilter').value.trim();
            const searchInput = document.getElementById('searchInput').value.trim().toLowerCase();
            const searchOption = document.getElementById('searchOption').value;
            const rows = document.querySelectorAll('#docsTable tbody tr');

            rows.forEach(row => {
                let showRow = true;

                // Company filter
                if (companyFilter) {
                    showRow = row.cells[3].textContent
                        .toLowerCase()
                        .includes(companyFilter.toLowerCase());
                }

                // Search filter
                if (showRow && searchInput) {
                    let cellIndex = 0;

                    if (searchOption === 'file') cellIndex = 0;
                    else if (searchOption === 'sip') cellIndex = 1;
                    else if (searchOption === 'dn') cellIndex = 2;
                    else if (searchOption === 'company') cellIndex = 3;

                    showRow = row.cells[cellIndex].textContent
                        .toLowerCase()
                        .includes(searchInput);
                }

                row.style.display = showRow ? '' : 'none';
            });
        }

        document.getElementById('companyFilter')
            .addEventListener('change', updateTableDisplay);

        document.getElementById('searchInput')
            .addEventListener('keyup', updateTableDisplay);

        document.getElementById('searchOption')
            .addEventListener('change', updateTableDisplay);
    });
</script>


@endsection
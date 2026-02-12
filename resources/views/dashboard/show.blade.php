@extends('layouts.app')

@section('title', 'Company Details - ' . $company->company_name)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
.receipt-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.receipt-header {
    text-align: center;
    padding: 30px 0;
    border-bottom: 3px solid #003366;
    margin-bottom: 40px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px 12px 0 0;
}

.receipt-header h1 {
    color: #003366;
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 10px 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.receipt-header .subtitle {
    color: #6c757d;
    font-size: 16px;
    margin: 0;
}

.receipt-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 40px;
}

.receipt-section {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.receipt-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #003366;
}

.section-header i {
    color: #003366;
    font-size: 20px;
    margin-right: 12px;
    width: 30px;
    text-align: center;
}

.section-header h3 {
    color: #003366;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-bottom: 12px;
}

.field-row.single {
    grid-template-columns: 1fr;
}

.field-label {
    font-weight: 600;
    color: #495057;
    font-size: 14px;
    margin-bottom: 4px;
    display: block;
}

.field-value {
    color: #212529;
    font-size: 15px;
    padding: 8px 12px;
    background: #f8f9fa;
    border-radius: 6px;
    border: 1px solid #e9ecef;
    min-height: 20px;
    word-break: break-word;
}

.field-value.empty {
    color: #6c757d;
    font-style: italic;
}

.documents-section {
    grid-column: 1 / -1;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 30px;
    margin-top: 20px;
}

.document-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.document-card {
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.document-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: #003366;
    transition: width 0.3s ease;
}

.document-card:hover::before {
    width: 8px;
}

.document-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.document-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 12px;
    font-size: 15px;
    word-break: break-word;
}

.document-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-primary {
    background: #003366;
    color: white;
}

.btn-primary:hover {
    background: #0055aa;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 51, 102, 0.3);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.action-bar {
    grid-column: 1 / -1;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px 0;
    border-top: 2px solid #e9ecef;
    margin-top: 40px;
}

.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.85);
    overflow: auto;
    backdrop-filter: blur(4px);
}

.modal-content {
    background-color: #ffffff;
    margin: 2% auto;
    padding: 0;
    border: none;
    width: 95%;
    max-width: 1400px;
    height: 90vh;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 25px 30px;
    background: linear-gradient(135deg, #003366 0%, #0055aa 100%);
    color: white;
}

.modal-header h4 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
}

.close {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.close:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
}

.modal-body {
    padding: 0;
    height: calc(90vh - 80px);
    overflow: hidden;
}

.document-tabs {
    display: flex;
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    overflow-x: auto;
    padding: 0 20px;
}

.tab-btn {
    background: none;
    border: none;
    padding: 18px 24px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    white-space: nowrap;
    font-size: 14px;
    font-weight: 500;
    color: #6c757d;
    transition: all 0.2s ease;
    position: relative;
}

.tab-btn.active {
    border-bottom-color: #003366;
    background: #ffffff;
    color: #003366;
    font-weight: 600;
}

.tab-btn:hover:not(.active) {
    background: #e9ecef;
    color: #495057;
}

.document-viewer {
    height: calc(100% - 60px);
    overflow: auto;
    background: #ffffff;
}

.document-frame {
    width: 100%;
    height: 100%;
    border: none;
}

.hidden {
    display: none;
}

@media (max-width: 768px) {
    .receipt-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .field-row {
        grid-template-columns: 1fr;
    }
    
    .document-grid {
        grid-template-columns: 1fr;
    }
    
    .action-bar {
        flex-direction: column;
        gap: 15px;
        align-items: stretch;
    }
    
    .modal-content {
        width: 98%;
        height: 95vh;
        margin: 1% auto;
    }
}

.print-header {
    display: none;
}

@media print {
    .print-header {
        display: block;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .action-bar, .modal {
        display: none;
    }
    
    .receipt-container {
        box-shadow: none;
        border: 1px solid #000;
    }
}
</style>
@endpush

@section('content')

<div class="receipt-container">
    <!-- Print Header -->
    <div class="print-header">
        <h1>Company Details Receipt</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <!-- Receipt Header -->
    <div class="receipt-header">
        <h1>Company Details</h1>
        <p class="subtitle">{{ $company->company_name }} - SIP: {{ $company->sip_number }}</p>
    </div>

    <!-- Receipt Content -->
    <div class="receipt-content">
        <!-- SIP & Customer Information -->
        <div class="receipt-section">
            <div class="section-header">
                <i class="fas fa-building"></i>
                <h3>SIP & Customer Information</h3>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">SIP Number</span>
                    <div class="field-value">{{ $company->sip_number ?? 'N/A' }}</div>
                </div>
                <div>
                    <span class="field-label">SIP Type</span>
                    <div class="field-value">{{ $company->sip_type ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Company Name</span>
                    <div class="field-value">{{ $company->company_name ?? 'N/A' }}</div>
                </div>
                <div>
                    <span class="field-label">Customer Type</span>
                    <div class="field-value">{{ $company->customer_type ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Service/DN No</span>
                    <div class="field-value">{{ $company->service_dn ?? 'N/A' }}</div>
                </div>
                <div>
                    <span class="field-label">Name of Proprietor/Director</span>
                    <div class="field-value">{{ $company->name_of_proprietor ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Company Registration No.</span>
                    <div class="field-value">{{ $company->company_reg_no ?? 'N/A' }}</div>
                </div>
                <div>
                    <span class="field-label">Registration Date</span>
                    <div class="field-value">{{ $company->reg_date ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="field-row single">
                <div>
                    <span class="field-label">PAN/VAT No.</span>
                    <div class="field-value">{{ $company->pan_no ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Permanent Address -->
        <div class="receipt-section">
            <div class="section-header">
                <i class="fas fa-home"></i>
                <h3>Permanent Address</h3>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Province</span>
                    <div class="field-value {{ $company->province_perm ? '' : 'empty' }}">{{ $company->province_perm ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">District</span>
                    <div class="field-value {{ $company->district_perm ? '' : 'empty' }}">{{ $company->district_perm ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Municipality</span>
                    <div class="field-value {{ $company->municipality_perm ? '' : 'empty' }}">{{ $company->municipality_perm ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">Ward</span>
                    <div class="field-value {{ $company->ward_perm ? '' : 'empty' }}">{{ $company->ward_perm ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row single">
                <div>
                    <span class="field-label">Tole</span>
                    <div class="field-value {{ $company->tole_perm ? '' : 'empty' }}">{{ $company->tole_perm ?? 'Not Provided' }}</div>
                </div>
            </div>
        </div>

        <!-- Installation Address -->
        <div class="receipt-section">
            <div class="section-header">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Installation Address</h3>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Province</span>
                    <div class="field-value {{ $company->province_install ? '' : 'empty' }}">{{ $company->province_install ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">District</span>
                    <div class="field-value {{ $company->district_install ? '' : 'empty' }}">{{ $company->district_install ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Municipality</span>
                    <div class="field-value {{ $company->municipality_install ? '' : 'empty' }}">{{ $company->municipality_install ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">Ward</span>
                    <div class="field-value {{ $company->ward_install ? '' : 'empty' }}">{{ $company->ward_install ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row single">
                <div>
                    <span class="field-label">Tole</span>
                    <div class="field-value {{ $company->tole_install ? '' : 'empty' }}">{{ $company->tole_install ?? 'Not Provided' }}</div>
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="receipt-section">
            <div class="section-header">
                <i class="fas fa-phone"></i>
                <h3>Contact Details</h3>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Landline</span>
                    <div class="field-value {{ $company->landline ? '' : 'empty' }}">{{ $company->landline ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">Mobile</span>
                    <div class="field-value {{ $company->mobile ? '' : 'empty' }}">{{ $company->mobile ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Email</span>
                    <div class="field-value {{ $company->email ? '' : 'empty' }}">{{ $company->email ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">Website</span>
                    <div class="field-value {{ $company->website ? '' : 'empty' }}">{{ $company->website ?? 'Not Provided' }}</div>
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="receipt-section">
            <div class="section-header">
                <i class="fas fa-briefcase"></i>
                <h3>Business Information</h3>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Objectives of Company</span>
                    <div class="field-value {{ $company->objectives ? '' : 'empty' }}">{{ $company->objectives ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">Purpose of SIP PBX / SIP Trunk</span>
                    <div class="field-value {{ $company->purpose ? '' : 'empty' }}">{{ $company->purpose ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row single">
                <div>
                    <span class="field-label">No. of Sessions</span>
                    <div class="field-value {{ $company->sessions ? '' : 'empty' }}">{{ $company->sessions ?? 'Not Provided' }}</div>
                </div>
            </div>
        </div>

        <!-- Authorization -->
        <div class="receipt-section">
            <div class="section-header">
                <i class="fas fa-signature"></i>
                <h3>Authorization</h3>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Authorized Signature</span>
                    <div class="field-value {{ $company->authorized_signature ? '' : 'empty' }}">{{ $company->authorized_signature ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">Signature Name</span>
                    <div class="field-value {{ $company->signature_name ? '' : 'empty' }}">{{ $company->signature_name ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row">
                <div>
                    <span class="field-label">Position</span>
                    <div class="field-value {{ $company->position ? '' : 'empty' }}">{{ $company->position ?? 'Not Provided' }}</div>
                </div>
                <div>
                    <span class="field-label">Signature Date</span>
                    <div class="field-value {{ $company->signature_date ? '' : 'empty' }}">{{ $company->signature_date ?? 'Not Provided' }}</div>
                </div>
            </div>
            <div class="field-row single">
                <div>
                    <span class="field-label">Seal</span>
                    <div class="field-value {{ $company->seal ? '' : 'empty' }}">{{ $company->seal ?? 'Not Provided' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="documents-section">
        <div class="section-header">
            <i class="fas fa-file-alt"></i>
            <h3>Uploaded Documents</h3>
        </div>
        
        @if(count($documents) > 0)
            <div class="document-grid">
                @foreach($documents as $doc)
                    <div class="document-card">
                        <div class="document-name">
                            <i class="fas fa-file-pdf" style="color: #dc3545; margin-right: 8px;"></i>
                            {{ basename($doc) }}
                        </div>
                        <div class="document-actions">
                            <a href="{{ asset('storage/' . $doc) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fas fa-external-link-alt"></i>
                                View Document
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
                <i class="fas fa-folder-open" style="font-size: 48px; color: #6c757d; margin-bottom: 15px;"></i>
                <p style="color: #6c757d; font-size: 16px; margin: 0;">No documents uploaded for this SIP.</p>
            </div>
        @endif
    </div>

    <!-- Action Bar -->
    <div class="action-bar">
        <div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Go Back
            </a>
            @if(count($documents) > 0)
                <button onclick="openDocumentViewer()" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    View All Documents
                </button>
                <a href="{{ route('user.view_documents', ['sip' => $company->sip_number]) }}" class="btn btn-secondary" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    Open Document Gallery
                </a>
            @endif
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-secondary">
                <i class="fas fa-print"></i>
                Print Receipt
            </button>
        </div>
    </div>
</div>

<!-- Document Viewer Modal -->
<div id="documentViewerModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h4>
                <i class="fas fa-file-alt"></i>
                Scanned Documents Viewer
            </h4>
            <button class="close" onclick="closeDocumentViewer()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="document-tabs">
                @foreach($documents as $index => $doc)
                    <button class="tab-btn @if($loop->first) active @endif" onclick="showDocument({{ $index }})">
                        {{ basename($doc) }}
                    </button>
                @endforeach
            </div>
            <div class="document-viewer">
                @foreach($documents as $index => $doc)
                    <div id="doc-{{ $index }}" class="document-frame @if(!$loop->first) hidden @endif">
                        <iframe src="{{ asset('storage/' . $doc) }}" width="100%" height="600px"></iframe>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
function openDocumentViewer() {
    document.getElementById('documentViewerModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeDocumentViewer() {
    document.getElementById('documentViewerModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function showDocument(index) {
    // Hide all document frames
    const frames = document.querySelectorAll('.document-frame');
    frames.forEach(frame => frame.classList.add('hidden'));
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tab-btn');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Show selected document frame
    document.getElementById('doc-' + index).classList.remove('hidden');
    
    // Add active class to selected tab
    tabs[index].classList.add('active');
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('documentViewerModal');
    if (event.target == modal) {
        closeDocumentViewer();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDocumentViewer();
    }
});
</script>

@endsection

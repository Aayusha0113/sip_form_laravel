@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            @include('dashboard.admin_sidebar_partial')
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 mt-3">

            <div class="content-card">
                <h4 class="fw-bold mb-3">
                    <i class="fa fa-file-import me-2"></i> Import SIP Documents
                </h4>

                {{-- Success Message --}}
                @if(session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Upload Form --}}
                <form method="POST"
                      action="{{ route('dashboard.upload_docs') }}"
                      enctype="multipart/form-data">

                    @csrf

                    <!-- SIP Number -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">SIP Number</label>
                        <input type="text"
                               name="sip_number"
                               class="form-control"
                               placeholder="Enter SIP Number"
                               value="{{ old('sip_number') }}"
                               required>
                    </div>

                    <!-- Document -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Document</label>
                        <input type="file"
                               name="document"
                               class="form-control"
                               accept=".pdf,.doc,.docx,.xls,.xlsx"
                               required>
                        <small class="text-muted">
                            Allowed: PDF, DOC, DOCX, XLS, XLSX (Max 10MB)
                        </small>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload me-1"></i> Upload Document
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection

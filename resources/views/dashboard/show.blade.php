@extends('layouts.app')

@section('content')

<div class="container">

<h2>Details - {{ $company->company_name }}</h2>

<!-- SIP & Customer Info -->
<div class="section">
<h3>SIP & Customer Information</h3>
<p><strong>SIP Type:</strong> {{ $company->sip_type }}</p>
<p><strong>Company Name:</strong> {{ $company->company_name }}</p>
<p><strong>Customer Type:</strong> {{ $company->customer_type }}</p>
</div>

<hr>

<h3>Uploaded Documents</h3>

@if(count($documents) > 0)
    <ul>
        @foreach($documents as $doc)
            <li>
                {{ basename($doc) }}
                <a href="{{ asset('storage/' . $doc) }}" target="_blank">
                    View
                </a>
            </li>
        @endforeach
    </ul>
@else
    <p>No documents uploaded for this SIP.</p>
@endif

<br>

<a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>

</div>

@endsection

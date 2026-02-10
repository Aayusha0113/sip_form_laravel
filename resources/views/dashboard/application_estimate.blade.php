@extends('layouts.app')
@section('title', 'Estimate Application')
@section('content')
<h2>Estimate for Application #{{ $application->id }}</h2>
<p>Customer: {{ $application->customer_name }}</p>
<p>Type: {{ $application->sip_type }}</p>
<p>Sessions: {{ $application->sessions }}</p>
<p>DN/DID: {{ $application->did }}</p>
<p>Status: {{ ucfirst($application->status) }}</p>
<p>-- You can add your estimate calculation here --</p>
<a href="{{ route('user.view_client_apps') }}" class="btn btn-secondary">Back</a>
@endsection

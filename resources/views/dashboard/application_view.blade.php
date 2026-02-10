@extends('layouts.app')
@section('title', 'View Application')
@section('content')
<h2>Application #{{ $application->id }}</h2>
<ul>
    <li>Customer Name: {{ $application->customer_name }}</li>
    <li>Type: {{ $application->sip_type }}</li>
    <li>Sessions: {{ $application->sessions }}</li>
    <li>DN/DID: {{ $application->did }}</li>
    <li>Status: {{ ucfirst($application->status) }}</li>
</ul>
<a href="{{ route('user.view_client_apps') }}" class="btn btn-secondary">Back</a>
@endsection

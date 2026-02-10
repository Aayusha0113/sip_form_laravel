@extends('layouts.app')
@section('title', 'Letter Application')
@section('content')
<h2>Letter for Application #{{ $application->id }}</h2>
<p>Customer: {{ $application->customer_name }}</p>
<p>Type: {{ $application->sip_type }}</p>
<p>Sessions: {{ $application->sessions }}</p>
<p>DN/DID: {{ $application->did }}</p>
<p>Status: {{ ucfirst($application->status) }}</p>
<p>-- You can add your letter template/content here --</p>
<a href="{{ route('user.view_client_apps') }}" class="btn btn-secondary">Back</a>
@endsection

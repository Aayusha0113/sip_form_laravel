@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div style="max-width:900px; margin:30px auto;">
    <div style="background:#fff; padding:20px; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,0.06); text-align:center;">
        @if(file_exists(public_path('logo.jpg')))
            <img src="{{ asset('logo.jpg') }}" alt="NTC Logo" style="width:110px; display:block; margin:0 auto 10px;">
        @endif

        <h2 style="color:#0b61c3; margin:6px 0;">Welcome, {{ $user->username ?? $user->name }}</h2>
        <p style="color:#6b7280; margin-top:0;">NTC User Dashboard</p>

        <div style="margin-top:28px;">
            <a href="{{ route('form.index') }}" style="background:#4f9be6; color:#fff; padding:12px 28px; border-radius:8px; text-decoration:none; display:inline-block;">Upload docs</a>
        </div>
    </div>
</div>
@endsection
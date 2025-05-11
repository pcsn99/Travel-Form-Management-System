@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <p>This is your admin dashboard.</p>

    <a href="{{ route('admin.upload.signature.form') }}">Upload Signature</a>

    <a href="{{ route('travel-request-questions.index') }}">
        <button>Manage Travel Request Questions</button>
    </a>

    <a href="{{ route('travel-requests.index', ['status' => 'pending']) }}">
        <button>View Travel Requests</button>
    </a>

    <a href="{{ route('local-forms.index') }}">
        <button>View Local Travel Forms</button>
    </a>

    <a href="{{ route('Overseas-forms.index') }}">
        <button>View Overseas Travel Forms</button>
    </a>



    <a href="{{ route('local-form-questions.index') }}">
        <button>Manage Local Form Questions</button>
    </a>
    
    <a href="{{ route('Overseas-form-questions.index') }}">
        <button>Manage Overseas Form Questions</button>
    </a>

    <a href="{{ route('admin.members.index') }}">
        <button>Community Members</button>
    </a>

@endsection


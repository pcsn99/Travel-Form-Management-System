@extends('layouts.app')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 20px;
    }

    .dashboard-header {
        background-color: #17224D;
        padding: 15px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: white;
        border-bottom: 2px solid #2980b9;
        margin-bottom: 30px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    }

    .welcome-section {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 30px;
        position: relative;
        color: white;
        overflow: hidden;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 0;
        border-radius: 12px;
    }

    .welcome-section h2,
    .welcome-section .btn {
        position: relative;
        z-index: 1;
    }

    .dashboard-container {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    }

    .dashboard-section {
        margin-top: 30px;
        padding: 20px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        color: #17224D;
    }

    .dashboard-section h3 {
        font-size: 20px;
        font-weight: bold;
        color: #17224D;
        margin-bottom: 10px;
    }

    .dashboard-section ul {
        list-style-type: none;
        padding: 0;
    }

    .dashboard-section ul li {
        padding: 10px;
        font-size: 16px;
        border-bottom: 1px solid rgba(23, 34, 77, 0.2);
    }

    .dashboard-section a {
        color: #2980b9;
        font-size: 16px;
        text-decoration: none;
    }

    .dashboard-section a:hover {
        color: #17224D;
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">Dashboard</div>

<div class="welcome-section">
    <h2>Welcome, {{ Auth::user()->name }}!</h2>
    <a href="{{ route('travel-requests.create') }}" class="btn btn-light mt-3">
        <i class="bi bi-plus-circle"></i> Create Travel Request
    </a>
</div>

<div class="dashboard-container">
    @if(session('success'))
        <p style="color: #27ae60;">{{ session('success') }}</p>
    @endif

    @if(count($pendingForms))
    <div class="alert alert-warning">
        <strong>Reminder:</strong> You have pending travel forms. Please scroll down and complete them.
    </div>
    @endif

    <div class="dashboard-section">
        <h3>Pending Travel Requests</h3>
        <ul>
            @forelse($pendingRequests as $req)
                <li>
                    {{ ucfirst($req->type) }} - {{ $req->intended_departure_date }} to {{ $req->intended_return_date }}
                    ({{ ucfirst($req->status) }})
                    | <a href="{{ route('travel-requests.edit', $req->id) }}">Edit</a>
                    | <form method="POST" action="{{ route('travel-requests.destroy', $req->id) }}" style="display:inline;" onsubmit="return confirm('Delete this travel request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #c0392b; cursor: pointer;">Delete</button>
                    </form>
                </li>
            @empty
                <li>No pending travel requests yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="dashboard-section">
        <h3>Pending Travel Forms</h3>
        <ul>
            @forelse($pendingForms as $form)
                <li>
                    {{ ucfirst($form->request->type) }} Travel Form -
                    {{ $form->request->intended_departure_date }} to {{ $form->request->intended_return_date }}
                    | <a href="{{ $form->request->type === 'local'
                        ? route('member.local-forms.edit', $form->id)
                        : route('member.Overseas-forms.edit', $form->id) }}">Fill Out</a>
                </li>
            @empty
                <li>No pending travel forms to complete.</li>
            @endforelse
        </ul>
    </div>

    <div class="dashboard-section">
        <h3>Submitted Travel Forms</h3>
        <ul>
            @forelse($submittedForms as $form)
                <li>
                    {{ ucfirst($form->request->type) }} Travel Form - 
                    <strong>{{ ucfirst($form->status) }}</strong>
                    ({{ $form->request->intended_departure_date }} to {{ $form->request->intended_return_date }})
                    | <a href="{{ $form->request->type === 'local' 
                        ? route('member.local-forms.show', $form->id) 
                        : route('member.Overseas-forms.show', $form->id) }}">View</a>
                </li>
            @empty
                <li>No submitted or approved upcoming forms yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

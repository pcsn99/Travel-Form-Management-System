@extends('layouts.app')

@section('styles')
<style>
    /* ✅ Page Styles */
    body {
        background-color: #f0f2f5; 
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 20px;
    }

    /* ✅ Dashboard Header */
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

    /* ✅ Image Section */
    .dashboard-image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
    }

    .dashboard-image {
        width: 90%;
        max-width: 800px;
        border-radius: 12px;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
    }

    /* ✅ Content Container */
    .dashboard-container {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        background: rgba(255, 255, 255, 0.9); 
        border-radius: 12px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
    }

    /* ✅ Sidebar - Adjust Vertical Space */
    .sidebar {
        width: 260px;
        background-color: #17224D;
        color: white;
        padding: 20px 10px;
        position: fixed;
        left: 0;
        top: 60px; /* ✅ Space between top bar and sidebar */
        height: calc(100vh - 60px); /* ✅ Adjusted height */
        transition: all 0.3s ease;
    }

    /* ✅ Ensure "Dashboard" button has space */
    .sidebar a:first-child {
        margin-top: 20px; /* ✅ Creates vertical spacing for adjustment */
    }

    .sidebar a {
        display: flex;
        align-items: center;
        padding: 14px;
        gap: 10px;
        text-decoration: none;
        color: white;
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .sidebar a img {
        width: 35px; /* ✅ Adjusted to prevent cut-off */
        height: 35px;
    }

    /* ✅ Dashboard Sections */
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

    /* ✅ Lists */
    .dashboard-section ul {
        list-style-type: none;
        padding: 0;
    }

    .dashboard-section ul li {
        padding: 10px;
        font-size: 16px;
        border-bottom: 1px solid rgba(23, 34, 77, 0.2);
    }

    /* ✅ Links */
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

<!-- ✅ Dashboard Header -->
<div class="dashboard-header">Dashboard</div>

<!-- ✅ Image Section -->
<div class="dashboard-image-container">
    <img src="{{ asset('images/bg.jpeg') }}" alt="Dashboard Image" class="dashboard-image">
</div>

<!-- ✅ Content Section -->
<div class="dashboard-container">
    @if(session('success'))
        <p style="color: #27ae60;">{{ session('success') }}</p>
    @endif

    <!-- ✅ Pending Travel Requests -->
    <div class="dashboard-section">
        <h3>Pending Travel Requests</h3>
        <ul>
            @forelse($pendingRequests as $req)
                <li>
                    {{ ucfirst($req->type) }} - {{ $req->intended_departure_date }} to {{ $req->intended_return_date }}
                    ({{ ucfirst($req->status) }})
                    | <a href="{{ route('travel-requests.edit', $req->id) }}">✏️ Edit</a>
                    | <form method="POST" action="{{ route('travel-requests.destroy', $req->id) }}" style="display:inline;" onsubmit="return confirm('Delete this travel request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #c0392b; cursor: pointer;">🗑️ Delete</button>
                    </form>
                </li>
            @empty
                <li>No pending travel requests yet.</li>
            @endforelse
        </ul>
    </div>

    <!-- ✅ Pending Travel Forms -->
    <div class="dashboard-section">
        <h3>Pending Travel Forms</h3>
        <ul>
            @forelse($pendingForms as $form)
                <li>
                    {{ ucfirst($form->request->type) }} Travel Form -
                    {{ $form->request->intended_departure_date }} to {{ $form->request->intended_return_date }}
                    | <a href="{{ $form->request->type === 'local'
                        ? route('member.local-forms.edit', $form->id)
                        : route('member.Overseas-forms.edit', $form->id) }}">✏️ Fill Out</a>
                </li>
            @empty
                <li>No pending travel forms to complete.</li>
            @endforelse
        </ul>
    </div>

    <!-- ✅ Submitted Travel Forms -->
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
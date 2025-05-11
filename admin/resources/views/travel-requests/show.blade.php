@extends('layouts.app')

@section('title', 'Travel Request Details')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .container-custom {
        max-width: 900px;
        margin: auto;
    }

    .header-banner {
        position: relative;
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        text-align: center;
        color: white;
    }

    .header-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.75);
        border-radius: 12px;
        z-index: 0;
    }

    .header-banner h2 {
        position: relative;
        z-index: 1;
        font-size: 28px;
    }

    .card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        padding: 30px;
        margin-bottom: 30px;
    }

    .card h4 {
        font-size: 20px;
        margin-bottom: 15px;
        border-bottom: 2px solid #ccc;
        padding-bottom: 5px;
    }

    ul li {
        margin-bottom: 10px;
    }

    textarea {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .btn-link, .action-button {
        display: inline-block;
        margin-top: 15px;
        text-align: center;
        font-weight: bold;
        text-decoration: none;
        border-radius: 6px;
        padding: 10px 18px;
        transition: background-color 0.3s;
    }

    .btn-link {
        color: #007bff;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .approve-btn {
        background-color: #198754;
        color: white;
    }

    .approve-btn:hover {
        background-color: #157347;
    }

    .reject-btn {
        background-color: #dc3545;
        color: white;
    }

    .reject-btn:hover {
        background-color: #bb2d3b;
    }

    .view-form-btn {
        background-color: #6a4c93;
        color: white;
        padding: 10px 24px;
        border-radius: 6px;
        display: inline-block;
        text-decoration: none;
        font-weight: bold;
        margin-top: 15px;
        width: auto;
    }

    .view-form-btn:hover {
        background-color: #563d7c;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-start;
        margin-top: 15px;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>Travel Request Details</h2>
    </div>

    <div class="card">
        <p><strong>Name:</strong> {{ $request->user->name }}</p>
        <p><strong>Type:</strong> {{ ucfirst($request->type) }}</p>
        <p><strong>Departure:</strong> {{ \Carbon\Carbon::parse($request->intended_departure_date)->format('F d, Y') }}</p>
        <p><strong>Return:</strong> {{ \Carbon\Carbon::parse($request->intended_return_date)->format('F d, Y') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>

        @if($request->status === 'approved')
            @if(strtolower($request->type) === 'overseas' && $request->OverseasForm)
                <a href="{{ route('Overseas-forms.show', $request->OverseasForm->id) }}" class="view-form-btn">View Overseas Travel Form</a>
            @elseif(strtolower($request->type) === 'local' && $request->localForm)
                <a href="{{ route('local-forms.show', $request->localForm->id) }}" class="view-form-btn">View Local Travel Form</a>
            @endif
        @endif
    </div>

    <div class="card">
        <h4>Submitted Answers</h4>
        <ul>
            @foreach($request->answers as $answer)
                <li><strong>{{ $answer->question->question }}:</strong> {{ $answer->answer }}</li>
            @endforeach
        </ul>
    </div>

    @if($request->status === 'pending')
    <div class="card">
        <form method="POST" action="{{ route('travel-requests.approve', $request->id) }}">
            @csrf
            <label>Admin Comment (Optional):</label>
            <textarea name="admin_comment" placeholder="Add comment..."></textarea>
            <div class="form-actions">
                <button type="submit" class="approve-btn">Approve</button>
            </div>
        </form>

        <form method="POST" action="{{ route('travel-requests.reject', $request->id) }}">
            @csrf
            <label>Rejection Comment (Optional):</label>
            <textarea name="admin_comment" placeholder="Add comment..."></textarea>
            <div class="form-actions">
                <button type="submit" class="reject-btn">Reject</button>
            </div>
        </form>
    </div>
    @else
    <div class="card">
        <p><strong>Admin Comment:</strong> {{ $request->admin_comment }}</p>
    </div>
    @endif

    @if($request->status !== 'pending')
        <form method="POST" action="{{ route('travel-requests.reset', $request->id) }}" onsubmit="return confirm('Reset this request back to pending status? This will delete any travel forms related to this request');">
            @csrf
            <button type="submit" style="background-color: #ffc107; color: black;">🔁 Set Status to Pending</button>
        </form>
    @endif

    <a href="{{ route('travel-requests.index') }}" class="btn-link">⬅ Back to Travel Requests</a>
</div>
@endsection

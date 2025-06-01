@extends('layouts.app')

@section('title', 'Travel Request Details')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px 20px;
    }

    .container-custom {
        max-width: 900px;
        margin: auto;
    }

    .card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 25px;
        overflow: hidden;
    }

    .card-header {
        background-color: #17224D;
        color: white;
        font-size: 18px;
        padding: 15px 20px;
        font-weight: bold;
    }

    .card-body {
        padding: 20px;
    }

    .info-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 15px;
    }

    .info-label {
        flex: 1 1 45%;
        font-weight: 600;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
    }

    .list-group-item {
        border: none;
        padding: 15px 20px;
        border-bottom: 1px solid #eee;
    }

    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 20px;
    }

    .form-actions button,
    .form-actions form button {
        flex: 1 1 auto;
        padding: 10px 15px;
        font-weight: bold;
        border: none;
        border-radius: 6px;
    }

    .approve-btn { background-color: #198754; color: white; }
    .decline-btn { background-color: #dc3545; color: white; }
    .reset-btn { background-color: #ffc107; color: black; }

    .btn-link {
        display: inline-block;
        margin-top: 20px;
        color: #0d6efd;
        font-weight: 500;
        text-decoration: none;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .info-label { flex: 1 1 100%; }
        .form-actions { flex-direction: column; }
    }
</style>
@endsection

@section('content')
<div class="container-custom" >
    <div class="card">
        <div class="card-header">
            Travel Request Information
        </div>
        <div class="card-body">
            <div class="info-row">
                <div class="info-label">Name: {{ $request->user->name }}</div>
                <div class="info-label">Type: {{ ucfirst($request->type) }}</div>
                <div class="info-label">Departure: {{ \Carbon\Carbon::parse($request->intended_departure_date)->format('F d, Y') }}</div>
                <div class="info-label">Return: {{ \Carbon\Carbon::parse($request->intended_return_date)->format('F d, Y') }}</div>
                <div class="info-label">
                    Status:
                    <span class="badge {{ $request->status === 'approved' ? 'bg-success' : ($request->status === 'declined' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
            </div>

            @if($request->status === 'approved')
                @if(strtolower($request->type) === 'overseas' && $request->OverseasForm)
                    <a href="{{ route('Overseas-forms.show', $request->OverseasForm->id) }}" class="btn btn-outline-primary">View Overseas Travel Form</a>
                @elseif(strtolower($request->type) === 'local' && $request->localForm)
                    <a href="{{ route('local-forms.show', $request->localForm->id) }}" class="btn btn-outline-primary">View Local Travel Form</a>
                @endif
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Submitted Answers
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($request->answers as $answer)
                    <li class="list-group-item">
                        <strong>{{ $answer->question->question }}:</strong><br>
                        {{ $answer->answer }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @if($request->status === 'pending')
    <div class="form-actions">
        <button class="approve-btn" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
        <button class="decline-btn" data-bs-toggle="modal" data-bs-target="#declineModal">Decline</button>
    </div>
    @else
    <div class="card">
        <div class="card-body">
            <strong>Admin Comment:</strong><br>
            {{ $request->admin_comment }}
        </div>
    </div>
    @endif

    @if($request->status !== 'pending')
    <form method="POST" action="{{ route('travel-requests.reset', $request->id) }}" onsubmit="return confirm('Reset this request back to pending status? This will delete any travel forms related to this request.');">
        @csrf
        <button type="submit" class="reset-btn" style="text-align: center">🔁 Set Status Back to Pending</button>
    </form>
    @endif

 
</div>

@include('partials.modals.travel_request_modals', ['request' => $request])
@endsection

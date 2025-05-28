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

    .decline-btn {
        background-color: #dc3545;
        color: white;
    }

    .decline-btn:hover {
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



    <!-- Header -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <h5 class="mb-0">Travel Request Details</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-6"><strong>Name:</strong> {{ $request->user->name }}</div>
                <div class="col-md-6"><strong>Type:</strong> {{ ucfirst($request->type) }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Departure:</strong> {{ \Carbon\Carbon::parse($request->intended_departure_date)->format('F d, Y') }}</div>
                <div class="col-md-6"><strong>Return:</strong> {{ \Carbon\Carbon::parse($request->intended_return_date)->format('F d, Y') }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <span class="badge 
                        {{ 
                            $request->status === 'approved' ? 'bg-success' : 
                            ($request->status === 'declined' ? 'bg-danger' : 'bg-warning text-dark') 
                        }}">
                        {{ ucfirst($request->status) }}
                    </span>
                </div>
            </div>

            @if($request->status === 'approved')
                @if(strtolower($request->type) === 'overseas' && $request->OverseasForm)
                    <a href="{{ route('Overseas-forms.show', $request->OverseasForm->id) }}" class="btn btn-outline-primary mt-3">
                        <i class="bi bi-globe2 me-1"></i> View Overseas Travel Form
                    </a>
                @elseif(strtolower($request->type) === 'local' && $request->localForm)
                    <a href="{{ route('local-forms.show', $request->localForm->id) }}" class="btn btn-outline-primary mt-3">
                        <i class="bi bi-house-door-fill me-1"></i> View Local Travel Form
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Answers -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-dark text-white d-flex align-items-center">
            <i class="bi bi-chat-dots-fill me-2 fs-5"></i>
            <h5 class="mb-0">Submitted Answers</h5>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($request->answers as $answer)
                    <li class="list-group-item py-3 px-4">
                        <strong>{{ $answer->question->question }}:</strong>
                        <span class="text-muted">{{ $answer->answer }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>


    @if($request->status === 'pending')
    <div class="card">
        <div class="form-actions">
            <button type="button" class="approve-btn" data-bs-toggle="modal" data-bs-target="#approveModal">Approve</button>
            <button type="button" class="decline-btn" data-bs-toggle="modal" data-bs-target="#declineModal">Decline</button>
        </div>
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



<!-- Modal for Approve -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('travel-requests.approve', $request->id) }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">Approve Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>Admin Comment (Optional):</label>
                <textarea name="admin_comment" class="form-control" placeholder="Add comment..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Confirm Approve</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- Modal for Decline -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('travel-requests.decline', $request->id) }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="declineModalLabel">Decline Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>Declineion Comment (Optional):</label>
                <textarea name="admin_comment" class="form-control" placeholder="Add comment..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Confirm Decline</button>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

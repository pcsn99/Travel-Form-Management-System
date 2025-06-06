@extends('layouts.app')

@section('title', 'Local Travel Form Details')

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

    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 10px;
    }

    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 20px;
    }

    .file-list li {
        margin-bottom: 10px;
    }

    .btn-link {
        display: inline-block;
        margin-top: 15px;
        color: #007bff;
        font-weight: bold;
        text-decoration: none;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    button {
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>Local Travel Form Details</h2>
    </div>

    <div class="form-actions">
        @if(in_array($form->status, ['submitted', 'pending','declined']))
            <a href="{{ route('local-forms.edit', $form->id) }}">
                <button class="btn btn-primary">
                    <i class="bi bi-pencil-square"></i> Edit Form
                </button>
            </a>
        @endif

        @if($form->status !== 'pending')

            <form method="POST" action="{{ route('local-forms.reset', $form->id) }}" onsubmit="return confirm('Reset this form back to pending status?');">
                @csrf
                <button type="submit" class="btn btn-warning text-dark">
                    <i class="bi bi-arrow-clockwise"></i> Set Status to Pending
                </button>
            </form>

        @endif
    </div>

    <!-- Form Metadata Card -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Travel Form Information</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6"><strong>User:</strong> {{ $form->request->user->name }}</div>
                <div class="col-md-6"><strong>Status:</strong> <span class="badge bg-secondary">{{ ucfirst($form->status) }}</span></div>
            </div>
            <div class="row mb-3">
                
                <div class="col-md-6"><strong>Departure:</strong> {{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</div>
                <div class="col-md-6"><strong>Return:</strong> {{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</div>
            </div>
            <div class="row">
                <div class="col-md-6"><strong>Submitted:</strong> {{ $form->submitted_at }}</div>
                
            </div>
        </div>
    </div>

    <!-- Form Answers Card -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Form Answers</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @foreach($form->answers as $answer)
                    <div class="list-group-item">
                        <strong>{{ $answer->question->question }}</strong><br>
                        <span class="text-muted">{{ $answer->answer }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Approval Buttons -->
    @if(in_array($form->status, ['submitted', 'pending']))
    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex gap-3 flex-wrap justify-content-start">
            <button type="button" class="btn btn-dark px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal">
                <i class="bi bi-check-circle-fill me-1"></i> Approve
            </button>
            <button type="button" class="btn btn-danger px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#declineModal">
                <i class="bi bi-x-circle-fill me-1"></i> Decline
            </button>
        </div>
    </div>
    @endif
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('local-forms.approve', $form->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel">Confirm Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Optional comment before approving:</p>
                        <textarea name="admin_comment" class="form-control" placeholder="Your comment..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Decline Modal -->
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('local-forms.decline', $form->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="declineModalLabel">Confirm Decline</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Please provide a reason for declining:</p>
                        <textarea name="admin_comment" class="form-control" placeholder="Reason..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-x-circle"></i> Decline
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    @if($form->admin_comment)
        <div class="card">
            <p><strong>Admin Comment:</strong> {{ $form->admin_comment }}</p>
        </div>
    @endif

    @if($form->attachments->count())
        <div class="card">
            <h4>Additional Requirements</h4>
            <ul class="file-list">
                @foreach($form->attachments as $file)
                    <li>
                        <a href="{{ route('attachments.download', $file->id) }}" target="_blank">
                            {{ $file->original_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif


</div>
@endsection

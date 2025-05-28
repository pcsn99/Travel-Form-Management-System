@extends('layouts.app')

@section('title', 'Overseas Travel Form Details')

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
        background-color: rgba(0,0,0,0.55);
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

    .form-section label {
        font-weight: bold;
        margin-bottom: 5px;
    }

    .form-section .form-control-plaintext {
        background-color: #f9f9f9;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 15px;
    }

    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 30px;
    }

    textarea.form-control {
        width: 100%;
        resize: vertical;
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
</style>
@endsection

@section('content')

@php
    $admin = Auth::user();
@endphp

@include('partials.modals') <!-- Assume modals code is moved to a partial to keep the view clean -->

<div class="container-custom">
    <div class="header-banner">
        <h2><i class="bi bi-clipboard-check me-2"></i> Overseas Travel Form Details</h2>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions">
        @if(in_array($form->status, ['submitted', 'pending', 'declined']))
            <form action="{{ route('Overseas-forms.edit', $form->id) }}" method="GET">
                <button type="submit" class="btn btn-secondary">Edit Form</button>
            </form>
        @endif

        @if($admin->signature)
            <form action="{{ route('admin.overseas-forms.export', $form->id) }}" method="GET">
                <button type="submit" class="btn btn-success">Export to Excel</button>
            </form>
        @else
            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadSignatureModal">
                <i class="bi bi-exclamation-triangle-fill"></i> Upload Signature to Export
            </button>
        @endif

        @if($form->status !== 'pending')
            <form method="POST" action="{{ route('Overseas-forms.reset', $form->id) }}" onsubmit="return confirm('Reset this form back to pending status?');">
                @csrf
                <button type="submit" class="btn btn-warning text-dark">
                    <i class="bi bi-arrow-clockwise"></i> Set Status to Pending
                </button>
            </form>
        @endif

        @if($form->status === 'approved')
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#sendEmailModal">
                <i class="bi bi-envelope-fill"></i> Email to Director of Works
            </button>
        @endif
    </div>

    <!-- Travel Information -->
    <div class="card form-section">
        <div class="card-header bg-primary text-white">
            <h5>Travel Form Information</h5>
        </div>
        <div class="card-body">
            <label>User:</label>
            <div class="form-control-plaintext">{{ $form->request->user->name }}</div>

            <label>Status:</label>
            <div class="form-control-plaintext"><span class="badge bg-secondary">{{ ucfirst($form->status) }}</span></div>

            <label>Departure:</label>
            <div class="form-control-plaintext">{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</div>

            <label>Return:</label>
            <div class="form-control-plaintext">{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</div>

            <label>Submitted:</label>
            <div class="form-control-plaintext">{{ $form->submitted_at }}</div>
        </div>
    </div>

    <!-- Answers Section -->
    <div class="card form-section">
        <div class="card-header bg-dark text-white">
            <h5>Form Answers</h5>
        </div>
        <div class="card-body">
            @foreach($form->answers as $answer)
                <label>{{ $answer->question->question }}</label>
                <div class="form-control-plaintext">{{ $answer->answer }}</div>
            @endforeach
        </div>
    </div>

    <!-- Admin Comment -->
    @if($form->admin_comment)
        <div class="card form-section">
            <label>🗒 Admin Comment:</label>
            <div class="form-control-plaintext">{{ $form->admin_comment }}</div>
        </div>
    @endif

    <!-- Attachments -->
    @if($form->attachments->count())
        <div class="card form-section">
            <label>📁 Additional Requirements:</label>
            <ul class="file-list">
                @foreach($form->attachments as $file)
                    <li><a href="{{ route('attachments.download', $file->id) }}" target="_blank">{{ $file->original_name }}</a></li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Admin Approval Buttons -->
    @if(in_array($form->status, ['submitted', 'pending']))
        <div class="card form-section">
            <h5><i class="bi bi-shield-check me-2"></i> Admin Actions</h5>
            <div class="form-actions">
                @if($admin->signature)
                    <button type="button" class="btn btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal">
                        <i class="bi bi-check-circle-fill"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#declineModal">
                        <i class="bi bi-x-circle-fill"></i> Decline
                    </button>
                @else
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadSignatureModal">
                        <i class="bi bi-exclamation-triangle-fill"></i> Upload Signature to Proceed
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>

<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.overseas-forms.email-export', $form->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sendEmailModalLabel">Send Form via Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject:</label>
                        <input type="text" name="subject" class="form-control" placeholder="Overseas Travel Form Submission">
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Message (you can edit this):</label>
                        <textarea class="form-control" name="message" rows="5" required>
Dear Director of Works,

Please find attached the exported Overseas Travel Form for your review.

Best regards,  
{{ Auth::user()->name }}
                        </textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('Overseas-forms.approve', $form->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Confirm Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Optional Comment:</label>
                    <textarea name="admin_comment" class="form-control" placeholder="Your comment..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">✅ Approve</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Decline Modal -->
<div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('Overseas-forms.decline', $form->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel">Confirm Declineion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label>Reason for Declineion:</label>
                    <textarea name="admin_comment" class="form-control" required placeholder="Reason for declineion..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">❌ Decline</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

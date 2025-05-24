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

    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 10px;
    }

    button {
        background-color: #17224D;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background-color: #1f2f5f;
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
</style>
@endsection

@section('content')

@php
    $admin = Auth::user();
@endphp
<div class="container-custom">

    @if(session('success'))
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="bi bi-check-circle-fill me-2"></i>Success
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {!! session('success') !!}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
    @endif

    @if(session('error'))
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">
                        <i class="bi bi-x-circle-fill me-2"></i>Error
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {!! session('error') !!}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        });
    </script>
    @endif

    @if(session('warning'))
    <!-- Warning Modal -->
    <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="warningModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Notice
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {!! session('warning') !!}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var warningModal = new bootstrap.Modal(document.getElementById('warningModal'));
            warningModal.show();
        });
    </script>
    @endif

    <div class="header-banner text-center py-4 mb-4 rounded shadow" style="background-color:#17224D; color:white;">
        <h2 class="mb-0"><i class="bi bi-clipboard-check me-2"></i> Overseas Travel Form Details</h2>
    </div>

    <div class="d-flex flex-wrap justify-content-start gap-3 mb-4">
        @if(in_array($form->status, ['submitted', 'pending', 'rejected']))
        <form action="{{ route('Overseas-forms.edit', $form->id) }}" method="GET" class="d-inline">
            <button type="submit" class="btn btn-secondary">
                Edit Form
            </button>
        </form>
        @endif

        @if($admin->signature)
        <form action="{{ route('admin.overseas-forms.export', $form->id) }}" method="GET" class="d-inline">
            <button type="submit" class="btn btn-success">
                Export to Excel
            </button>
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
        <div class="card-header bg-dark text-white d-flex align-items-center">
            <i class="bi bi-card-text me-2 fs-5"></i>
            <h5 class="mb-0">Form Answers</h5>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($form->answers as $answer)
                    <li class="list-group-item py-3 px-4">
                        <strong>{{ $answer->question->question }}:</strong> 
                        <span class="text-muted">{{ $answer->answer }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>


    @if(in_array($form->status, ['submitted', 'pending']))
    <div class="card mb-4 shadow-sm p-4">
        <h5 class="mb-3"><i class="bi bi-shield-check me-2"></i> Admin Actions</h5>
        <div class="d-flex flex-wrap gap-3">
            @if($admin->signature)
                <button type="button" class="btn btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="bi bi-check-circle-fill"></i> Approve
                </button>
                <button type="button" class="btn btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="bi bi-x-circle-fill"></i> Reject
                </button>
            @else
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#uploadSignatureModal">
                    <i class="bi bi-exclamation-triangle-fill"></i> Upload Signature to Proceed
                </button>
            @endif
        </div>
    </div>
    @endif

<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('Overseas-forms.approve', $form->id) }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="approveModalLabel">Confirm Approval</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Please leave an optional comment before approving this form:</p>
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
  

  <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" action="{{ route('Overseas-forms.reject', $form->id) }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="rejectModalLabel">Confirm Rejection</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Please leave a reason for rejecting this form:</p>
            <textarea name="admin_comment" class="form-control" placeholder="Reason for rejection..."></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">❌ Reject</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Signature Upload Modal -->
    <div class="modal fade" id="uploadSignatureModal" tabindex="-1" aria-labelledby="uploadSignatureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="uploadSignatureModalLabel">Upload Your Signature</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if($admin->signature)
                        <p><strong>Current Signature:</strong></p>
                        <img src="{{ asset('shared/' . $admin->signature) }}" alt="Signature" class="img-fluid mb-3" style="max-height: 150px;">
                    @endif

                    <form method="POST" action="{{ route('admin.upload.signature') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="signature" class="form-label">Choose a signature file (.jpg, .png):</label>
                            <input type="file" class="form-control" name="signature" required accept=".jpg,.jpeg,.png">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload / Replace</button>
                    </form>
                </div>

            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="sendEmailModal" tabindex="-1" aria-labelledby="sendEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.overseas-forms.email-export', $form->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sendEmailModalLabel">Send Form via Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
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



    @if($form->admin_comment)
        <div class="card">
            <p><strong>🗒 Admin Comment:</strong> {{ $form->admin_comment }}</p>
        </div>
    @endif

    @if($form->attachments->count())
        <div class="card">
            <h4>📁 Additional Requirements</h4>
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

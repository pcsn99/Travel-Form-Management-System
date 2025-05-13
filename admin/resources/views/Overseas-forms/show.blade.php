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
<div class="container-custom">
    <div class="header-banner">
        <h2>Overseas Travel Form Details</h2>
    </div>

    <div class="form-actions">
        @if(in_array($form->status, ['submitted', 'pending']))
            <a href="{{ route('Overseas-forms.edit', $form->id) }}">
                <button>✏️ Edit Form</button>
            </a>
        @endif

        <a href="{{ route('admin.overseas-forms.export', $form->id) }}">
            <button>📥 Export to Excel</button>
        </a>
    </div>

    <div class="card">
        <p><strong>User:</strong> {{ $form->request->user->name }}</p>
        <p><strong>Status:</strong> {{ ucfirst($form->status) }}</p>
        <p><strong>Submitted:</strong> {{ $form->submitted_at }}</p>
    </div>

    <div class="card">
        <h4>🧾 Answers</h4>
        <ul>
            @foreach($form->answers as $answer)
                <li><strong>{{ $answer->question->question }}:</strong> {{ $answer->answer }}</li>
            @endforeach
        </ul>
    </div>

    @if(in_array($form->status, ['submitted', 'pending']))
    <div class="card py-3 px-4">
        
        <div class="d-flex flex-column align-items-start gap-2">
            <button type="button" class="btn btn-dark px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#approveModal">
                ✅ Approve
            </button>
            <button type="button" class="btn btn-danger px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal">
                ❌ Reject
            </button>
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

    @if($form->status !== 'pending')
    <div class="card">
        <form method="POST" action="{{ route('Overseas-forms.reset', $form->id) }}" onsubmit="return confirm('Reset this form back to pending status?');">
            @csrf
            <button type="submit">🔁 Set Status to Pending</button>
        </form>
    </div>
    @endif

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

    <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
</div>
@endsection

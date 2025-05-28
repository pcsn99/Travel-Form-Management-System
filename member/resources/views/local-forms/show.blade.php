@extends('layouts.app')

@section('title', 'View Local Travel Form')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        background: 
            linear-gradient(to right, rgba(23, 34, 77, 0.85), rgba(23, 34, 77, 0.85)),
            url('/images/bg.jpeg') no-repeat center center;
        background-size: cover;
        padding: 40px 20px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
        color: white;
        border-bottom: 3px solid #17224D;
        margin-bottom: 40px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .form-control-plaintext {
        background-color: #f8f9fa;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
    }

    h4 {
        margin-top: 30px;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        margin-top: 20px;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }
</style>
@endsection

@section('content')
<div class="dashboard-header">View Local Travel Form</div>

<!-- Travel Summary -->
<div class="card mb-4">
    <h5 class="mb-3"><i class="bi bi-airplane me-2"></i> Travel Summary</h5>
    <div class="row mb-2">
        <div class="col-md-6"><strong>Type of Travel:</strong> {{ ucfirst($form->request->type) }}</div>
        <div class="col-md-6"><strong>Status:</strong> <span class="badge bg-info text-dark">{{ ucfirst($form->status) }}</span></div>
    </div>
    <div class="row mb-2">
        <div class="col-md-6"><strong>Departure:</strong> {{ $form->request->intended_departure_date }}</div>
        <div class="col-md-6"><strong>Return:</strong> {{ $form->request->intended_return_date }}</div>
    </div>

    @if($form->request->admin_comment)
        <div class="mt-3">
            <strong>Request Remarks:</strong> {{ $form->request->admin_comment }}
        </div>
    @endif

    @if($form->admin_comment)
        <div class="mt-2">
            <strong>Form Remarks:</strong> {{ $form->admin_comment }}
        </div>
    @endif
</div>

<!-- Travel Request Answers -->
<div class="card mb-4">
    <h5 class="mb-3"><i class="bi bi-chat-left-text me-2"></i> Travel Request Answers</h5>
    <div class="row">
        @foreach($form->request->questionAnswers as $answer)
            <div class="col-md-6 mb-3">
                <label class="fw-bold">{{ $answer->question->question }}</label>
                <div class="form-control-plaintext">{{ $answer->answer ?: '-' }}</div>
            </div>
        @endforeach
    </div>
</div>

<!-- Travel Form Answers -->
<div class="card mb-4">
    <h5 class="mb-3"><i class="bi bi-ui-checks me-2"></i> Travel Form Answers</h5>
    <div class="row">
        @foreach($questions as $q)
            @php
                $answer = $form->answers->where('question_id', $q->id)->first()?->answer ?? '';
            @endphp
            <div class="col-md-6 mb-3">
                <label class="fw-bold">{{ $q->question }}</label>
                <div class="form-control-plaintext">{{ $answer ?: '-' }}</div>
            </div>
        @endforeach
    </div>

    @if(in_array(strtolower($form->status), ['pending', 'submitted', 'declined']))
        <div class="text-end mt-3">
            <a href="{{ route('member.local-forms.edit', $form->id) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
        </div>
    @endif
</div>

<!-- Attachments -->
@if($form->attachments->count())
<div class="card mb-4">
    <h5 class="mb-3"><i class="bi bi-paperclip me-2"></i> Uploaded Files</h5>
    <ul class="list-group list-group-flush">
        @foreach($form->attachments as $file)
            <li class="list-group-item">
                <a href="{{ route('attachments.download', $file->id) }}" target="_blank">
                    <i class="bi bi-file-earmark-arrow-down me-1"></i> {{ $file->original_name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endif

<a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">
    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
</a>

@endsection

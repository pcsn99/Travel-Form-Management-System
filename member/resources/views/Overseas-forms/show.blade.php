@extends('layouts.app')

@section('title', 'View Overseas Travel Form')

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
<div class="dashboard-header">📄 View Overseas Travel Form</div>
<div class="card">
    <h4>Travel Details</h4>
    <p><strong>Type of Travel:</strong> {{ ucfirst($form->request->type) }}</p>
    <p><strong>Departure:</strong> {{ $form->request->intended_departure_date }}</p>
    <p><strong>Return:</strong> {{ $form->request->intended_return_date }}</p>
    <hr>
    <p><strong>Form Status:</strong> <span class="badge bg-info text-dark">{{ ucfirst($form->status) }}</span></p>
    @if($form->admin_comment)
        <p><strong>Remarks:</strong> {{ $form->admin_comment }}</p>
    @endif
</div>

<div class="card">
    <h4>Travel Request Questions</h4>
    @foreach($form->request->questionAnswers as $answer)
        <div class="form-group">
            <label>{{ $answer->question->question }}</label>
            <p class="form-control-plaintext">{{ $answer->answer ?: '-' }}</p>
        </div>
    @endforeach
</div>

<div class="card">
    <h4>Travel Form Answers</h4>
    @foreach($questions as $q)
        @php
            $answer = $form->answers->where('question_id', $q->id)->first()?->answer ?? '';
        @endphp
        <div class="form-group">
            <label>{{ $q->question }}</label>
            <p class="form-control-plaintext">{{ $answer ?: '-' }}</p>
        </div>
    @endforeach

    <a href="{{ route('admin.overseas-forms.export', $form->id) }}">
        <button>📥 Export to Excel</button>
    </a>
    
</div>


@if($form->attachments->count())
<div class="card">
    <h4>📎 Uploaded Files</h4>
    <ul class="list-group">
        @foreach($form->attachments as $file)
            <li class="list-group-item">
                <a href="{{ route('attachments.download', $file->id) }}" target="_blank">{{ $file->original_name }}</a>
            </li>
        @endforeach
    </ul>
</div>
@endif

<a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅ Back to Dashboard</a>
@endsection

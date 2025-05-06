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

    h2 {
        text-align: center
    }

   
    .container-custom {
        max-width: 800px;
        margin: auto;
        padding-top: 20px;
    }

    
    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 50px;
    }

    .card-header {
        background-color: #17224D;
        color: white;
        font-size: 18px;
        font-weight: bold;
        border-radius: 6px 6px 0 0;
        padding: 15px;
        text-align: center;
    }

 
    .form-group {
        display: flex;
        align-items: center; 
        justify-content: space-between;
        width: 100%;
        gap: 15px; 
    }

    input[type="file"] {
        flex-grow: 1; 
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
    }

    button {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        flex-shrink: 0; 
    }

    button:hover {
        background-color: #1f2f5f;

    }

    .back-btn {
        background-color: #6c757d;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        margin-top: 20px;
    }

    .back-btn:hover {
        background-color: #5a6268;
    }


</style>
@endsection


@section('content')
    <h2 class="mb-4">View Local Travel Form</h2>

    {{-- 📄 Travel Request + Form Status --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">Travel Details</div>
        <div class="card-body">
            <p><strong>Type of Travel:</strong> {{ ucfirst($form->request->type) }}</p>
            <p><strong>Departure:</strong> {{ $form->request->intended_departure_date }}</p>
            <p><strong>Return:</strong> {{ $form->request->intended_return_date }}</p>



            <hr>

            <p><strong>Form Status:</strong> <span class="badge bg-info text-dark">{{ ucfirst($form->status) }}</span></p>
            @if($form->admin_comment)
                <p><strong>Remarks:</strong> {{ $form->admin_comment }}</p>
            @endif
        </div>
    </div>

    {{-- 📋 Travel Request Answers --}}

    @foreach($form->request->questionAnswers as $answer)
        <div class="mb-3">
            <label class="fw-bold">{{ $answer->question->question }}</label>
            <p class="form-control-plaintext">{{ $answer->answer ?: '-' }}</p>
        </div>
    @endforeach

    {{-- 📝 Travel Form Answers --}}

    @foreach($questions as $q)
        @php
            $answer = $form->answers->where('question_id', $q->id)->first()?->answer ?? '';
        @endphp
        <div class="mb-3">
            <label class="fw-bold">{{ $q->question }}</label>
            <p class="form-control-plaintext">{{ $answer ?: '-' }}</p>
        </div>
    @endforeach

    {{-- 📎 Uploaded Files --}}
    @if($form->attachments->count())
        <h4 class="mt-4">Uploaded Files</h4>
        <ul class="list-group mb-4">
            @foreach($form->attachments as $file)
                <li class="list-group-item">
                    <a href="{{ route('attachments.download', $file->id) }}" target="_blank">{{ $file->original_name }}</a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅ Back to Dashboard</a>
@endsection

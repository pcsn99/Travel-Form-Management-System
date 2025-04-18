@extends('layouts.app')

@section('title', 'View Local Travel Form')

@section('content')
    <h2 class="mb-4">View Local Travel Form</h2>

    {{-- 📄 Travel Request + Form Status --}}
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">🧾 Travel Details</div>
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
        <h4 class="mt-4">📎 Uploaded Files</h4>
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

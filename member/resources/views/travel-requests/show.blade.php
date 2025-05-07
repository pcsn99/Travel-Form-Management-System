@extends('layouts.app')

@section('title', 'View Travel Request')

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
        background-color: rgba(0,0,0,0.6);
        border-radius: 12px;
        z-index: 0;
    }

    .header-banner h2 {
        position: relative;
        z-index: 1;
        font-size: 28px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 30px;
    }

    .btn-edit-form {
        background-color: #6a4c93;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
        margin-top: 15px;
    }

    .btn-edit-form:hover {
        background-color: #563d7c;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>View Travel Request</h2>
    </div>

    <div class="card">
        <div class="card-body">
            <p><strong>Type of Travel:</strong> {{ ucfirst($request->type) }}</p>
            <p><strong>Departure Date:</strong> {{ $request->intended_departure_date }}</p>
            <p><strong>Return Date:</strong> {{ $request->intended_return_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>
            @if($request->admin_comment)
                <p><strong>Remarks:</strong> {{ $request->admin_comment }}</p>
            @endif

            @if($request->questionAnswers->count())
                <div class="mt-4">
                    @foreach($request->questionAnswers as $answer)
                        <div class="mb-3">
                            <label class="fw-bold">{{ $answer->question->question }}</label>
                            <p class="form-control-plaintext">{{ $answer->answer ?: '-' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($request->status === 'approved')
                @if($request->type === 'local' && $request->localForm)
                    <a href="{{ route('member.local-forms.edit', $request->localForm->id) }}" class="btn-edit-form"> Edit Local Travel Form</a>
                @elseif($request->type === 'overseas' && $request->overseasForm)
                    <a href="{{ route('member.Overseas-forms.edit', $request->overseasForm->id) }}" class="btn-edit-form"> Edit Overseas Travel Form</a>
                @endif
            @endif
                <br>
            <a href="{{ route('member.travel-requests.index') }}" class="btn btn-secondary mt-4">⬅ Back to All Requests</a>
        </div>
    </div>
</div>
@endsection

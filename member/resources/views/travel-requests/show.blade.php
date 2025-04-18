@extends('layouts.app')

@section('title', 'View Travel Request')

@section('content')
    <h2>View Travel Request</h2>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Travel Request Details
        </div>
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
        </div>
    </div>



    <a href="{{ route('member.travel-requests.index') }}" class="btn btn-secondary mt-4">⬅ Back to All Requests</a>
@endsection

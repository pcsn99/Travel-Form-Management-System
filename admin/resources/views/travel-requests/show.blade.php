@extends('layouts.app')

@section('content')
    <h2>Travel Request Details</h2>

    <p><strong>Name:</strong> {{ $request->user->name }}</p>
    <p><strong>Type:</strong> {{ ucfirst($request->type) }}</p>
    <p><strong>Departure:</strong> {{ $request->intended_departure_date }}</p>
    <p><strong>Return:</strong> {{ $request->intended_return_date }}</p>
    <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>

    <h4>Submitted Answers:</h4>
    <ul>
        @foreach($request->answers as $answer)
            <li><strong>{{ $answer->question->question }}:</strong> {{ $answer->answer }}</li>
        @endforeach
    </ul>

    @if($request->status === 'approved')
        @if($request->type === 'local' && $request->localForm)
            <a href="{{ route('local-forms.show', $request->localForm->id) }}">
                <button>📋 View Local Travel Form</button>
            </a>
        @elseif($request->type === 'Overseas' && $request->OverseasForm)
            <a href="{{ route('Overseas-forms.show', $request->OverseasForm->id) }}">
                <button>🌐 View Overseas Travel Form</button>
            </a>
        @endif
    @endif

    @if($request->status === 'pending')
    <form method="POST" action="{{ route('travel-requests.approve', $request->id) }}">
        @csrf
        <textarea name="admin_comment" placeholder="Optional admin comment..."></textarea>
        <br>
        <button type="submit">✅ Approve</button>
    </form>

    <form method="POST" action="{{ route('travel-requests.reject', $request->id) }}">
        @csrf
        <textarea name="admin_comment" placeholder="Optional rejection comment..."></textarea>
        <br>
        <button type="submit">❌ Reject</button>
    </form>
    @else
        <p><strong>Admin Comment:</strong> {{ $request->admin_comment }}</p>
    @endif

    <br>
    <a href="{{ route('travel-requests.index') }}">⬅️ Back to List</a>
@endsection

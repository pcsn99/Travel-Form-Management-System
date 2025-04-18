@extends('layouts.app')

@section('content')
<h2>📝 Create Travel Request for {{ $user->name }}</h2>

<form method="POST" action="{{ route('admin-travel-requests.store', $user->id) }}">
    @csrf

    <div>
        <label>Travel Type:</label>
        <select name="type" required>
            <option value="local">Local</option>
            <option value="Overseas">Overseas</option>
        </select>
    </div>

    <div>
        <label>Intended Departure Date:</label>
        <input type="date" name="intended_departure_date" required>
    </div>

    <div>
        <label>Intended Return Date:</label>
        <input type="date" name="intended_return_date" required>
    </div>

    <h4>Answer Questions:</h4>
    @foreach($questions as $question)
        <div>
            <label>{{ $question->question }}</label><br>
            <textarea name="answers[{{ $question->id }}]" rows="2" cols="60" placeholder="Answer here..."></textarea>
        </div>
    @endforeach

    <br>
    <button type="submit">✅ Submit Travel Request</button>
    <a href="{{ route('travel-requests.index') }}">🔙 Back to Requests</a>
</form>
@endsection

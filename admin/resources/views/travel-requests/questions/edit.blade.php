@extends('layouts.app')

@section('content')
    <h2>Edit Travel Request Question</h2>

    <form method="POST" action="{{ route('travel-request-questions.update', $question->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label>Question:</label>
            <input type="text" name="question" value="{{ $question->question }}" required>
        </div>
        <br>
        <button type="submit">✅ Update</button>
        <a href="{{ route('travel-request-questions.index') }}">🔙 Back</a>
    </form>
@endsection

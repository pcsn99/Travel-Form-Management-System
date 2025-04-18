@extends('layouts.app')

@section('content')
    <h2>Add Travel Request Question</h2>

    <form method="POST" action="{{ route('travel-request-questions.store') }}">
        @csrf
        <div>
            <label>Question:</label>
            <input type="text" name="question" required>
        </div>
        <br>
        <button type="submit">💾 Save</button>
        <a href="{{ route('travel-request-questions.index') }}">🔙 Back</a>
    </form>
@endsection


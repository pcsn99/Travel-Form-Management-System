@extends('layouts.app')

@section('content')
    <h2>✏️ Edit Local Form Question</h2>

    <form method="POST" action="{{ route('local-form-questions.update', $question->id) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Question:</label>
            <input type="text" name="question" value="{{ $question->question }}" required>
        </div>

        <div>
            <label>Question Type:</label>
            <select name="type" id="type-select" onchange="toggleChoices()">
                <option value="text" {{ $question->type === 'text' ? 'selected' : '' }}>Text</option>
                <option value="choice" {{ $question->type === 'choice' ? 'selected' : '' }}>Multiple Choice</option>
            </select>
        </div>

        <div id="choices-area" style="{{ $question->type === 'choice' ? '' : 'display:none;' }}">
            <label>Choices (one per line):</label><br>
            <textarea name="choices" rows="5" cols="40">@if(is_array($question->choices)){{ implode("\n", $question->choices) }}@endif</textarea><br>
            <label>
                <input type="checkbox" name="allow_other" {{ $question->allow_other ? 'checked' : '' }}>
                Allow "Other (please specify)"
            </label>
        </div>

        <br>
        <button type="submit">✅ Update</button>
        <a href="{{ route('local-form-questions.index') }}">🔙 Back</a>
    </form>

    <script>
        function toggleChoices() {
            const type = document.getElementById('type-select').value;
            document.getElementById('choices-area').style.display = type === 'choice' ? 'block' : 'none';
        }
    </script>
@endsection

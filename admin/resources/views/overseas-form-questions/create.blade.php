@extends('layouts.app')

@section('content')
    <h2>➕ Add Overseas Form Question</h2>

    <form method="POST" action="{{ route('Overseas-form-questions.store') }}">
        @csrf

        <div>
            <label>Question:</label>
            <input type="text" name="question" required>
        </div>

        <div>
            <label>Question Type:</label>
            <select name="type" id="type-select" onchange="toggleChoices()">
                <option value="text">Text</option>
                <option value="choice">Multiple Choice</option>
            </select>
        </div>

        <div id="choices-area" style="display:none;">
            <label>Choices (one per line):</label><br>
            <textarea name="choices" rows="5" cols="40" placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea><br>
            <label>
                <input type="checkbox" name="allow_other" value="1"> Allow "Other (please specify)"
            </label>
        </div>

        <br>
        <button type="submit">💾 Save</button>
        <a href="{{ route('Overseas-form-questions.index') }}">🔙 Back</a>
    </form>

    <script>
        function toggleChoices() {
            const type = document.getElementById('type-select').value;
            document.getElementById('choices-area').style.display = type === 'choice' ? 'block' : 'none';
        }
    </script>
@endsection

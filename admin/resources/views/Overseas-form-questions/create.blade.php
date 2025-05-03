@extends('layouts.app')

@section('title', 'Add Overseas Form Question')

@section('styles')
<style>

    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
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
        margin-bottom: 15px;
    }

    input[type="text"], select, textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
        font-size: 16px;
    }

    .toggle-area {
        display: none;
        margin-top: 10px;
    }

    .checkbox-container {
        margin-top: 5px;
        font-size: 14px;
    }

    button {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        width: 100%;
        border: none;
        margin-top: 15px;
        cursor: pointer;
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

<div class="container-custom">
    
    <div class="card">
        <div class="card-header">+ Add Overseas Form Question</div>
        <div class="card-body">
            
            <form method="POST" action="{{ route('Overseas-form-questions.store') }}">
                @csrf

                <div class="form-group">
                    <label><strong>Question:</strong></label>
                    <input type="text" name="question" required>
                </div>

                <div class="form-group">
                    <label><strong>Question Type:</strong></label>
                    <select name="type" id="type-select" onchange="toggleChoices()">
                        <option value="text">Text</option>
                        <option value="choice">Multiple Choice</option>
                    </select>
                </div>

                <div id="choices-area" class="toggle-area">
                    <label><strong>Choices (one per line):</strong></label>
                    <textarea name="choices" rows="5" placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                    <div class="checkbox-container">
                        <label>
                            <input type="checkbox" name="allow_other" value="1"> Allow "Other (please specify)"
                        </label>
                    </div>
                </div>

                <button type="submit">Save Question</button>
            </form>

            <a href="{{ route('Overseas-form-questions.index') }}" class="back-btn">Back</a>
        </div>
    </div>
</div>

<script>
    function toggleChoices() {
        const type = document.getElementById('type-select').value;
        document.getElementById('choices-area').style.display = type === 'choice' ? 'block' : 'none';
    }
</script>

@endsection
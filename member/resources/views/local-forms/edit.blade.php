@extends('layouts.app')

@section('title', 'Edit Local Travel Form')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        background: 
            linear-gradient(to right, rgba(23, 34, 77, 0.85), rgba(23, 34, 77, 0.85)),
            url('/images/bg.jpeg') no-repeat center center;
        background-size: cover;
        padding: 40px 20px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
        color: white;
        border-bottom: 3px solid #17224D;
        margin-bottom: 40px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }

    .container-custom {
        max-width: 900px;
        margin: auto;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 30px;
    }

    textarea, select, input[type="text"], input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
        margin-bottom: 10px;
    }

    button {
        background-color: #17224D;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 10px;
    }

    button:hover {
        background-color: #1f2f5f;
    }

    .cancel-button {
        background-color: red;
        margin-top: 20px;
    }

    .file-list li {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .file-list form {
        display: inline;
    }

    .request-info {
        background-color: #f8f9fa;
        border-left: 4px solid #17224D;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 30px;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="dashboard-header">Edit Local Travel Form</div>

    <div class="request-info">
        <h5>📋 Travel Request Summary</h5>
        <p><strong>Type:</strong> {{ ucfirst($form->request->type) }}</p>
        <p><strong>Departure:</strong> {{ $form->request->intended_departure_date }}</p>
        <p><strong>Return:</strong> {{ $form->request->intended_return_date }}</p>
        <p><strong>Status:</strong> {{ ucfirst($form->request->status) }}</p>
    </div>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red">{{ session('error') }}</p>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('member.local-forms.update', $form->id) }}">
            @csrf
            @foreach($questions as $q)
                @php
                    $choices = is_array($q->choices) ? $q->choices : json_decode($q->choices ?? '[]', true);
                    $existingAnswer = $form->answers->where('question_id', $q->id)->first()?->answer ?? '';
                    $isOther = $q->type === 'choice' && $q->allow_other && !in_array($existingAnswer, $choices);
                @endphp

                <div style="margin-bottom: 20px;">
                    <label><strong>{{ $q->question }}</strong></label><br>

                    @if($q->type === 'text')
                        <textarea name="answers[{{ $q->id }}]" rows="2" required>{{ $existingAnswer }}</textarea>

                    @elseif($q->type === 'choice')
                        <select name="answers[{{ $q->id }}]" onchange="toggleOther(this, '{{ $q->id }}')" required>
                            <option value="">-- Select an option --</option>
                            @foreach($choices as $choice)
                                <option value="{{ $choice }}" {{ $existingAnswer === $choice ? 'selected' : '' }}>{{ $choice }}</option>
                            @endforeach
                            @if($q->allow_other)
                                <option value="__other__" {{ $isOther ? 'selected' : '' }}>Other (please specify)</option>
                            @endif
                        </select>

                        @if($q->allow_other)
                            <input type="text" name="answers_other[{{ $q->id }}]" id="other-{{ $q->id }}" placeholder="Other..." style="margin-top: 5px; display: {{ $isOther ? 'block' : 'none' }};" value="{{ $isOther ? $existingAnswer : '' }}">
                        @endif
                    @endif
                </div>
            @endforeach

            <button type="submit">✅ Submit Form</button>
        </form>
    </div>

    @if($form->status === 'submitted')
    <div class="card">
        <h4>📤 Upload Additional Requirement</h4>
        <form action="{{ route('attachments.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="form_type" value="local">
            <input type="hidden" name="form_id" value="{{ $form->id }}">

            <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
            <button type="submit">📤 Upload</button>
        </form>

        @if($form->attachments->count())
        <h4 style="margin-top: 20px;">📎 Uploaded Files</h4>
        <ul class="file-list">
            @foreach($form->attachments as $file)
                <li>
                    <span><a href="{{ route('attachments.download', $file->id) }}" target="_blank">{{ $file->original_name }}</a></span>
                    <form action="{{ route('attachments.delete', $file->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color:red; color:white; padding: 5px 10px; border: none; border-radius: 4px;">🗑️</button>
                    </form>
                </li>
            @endforeach
        </ul>
        @endif
    </div>
    @else
        <p style="margin-top: 30px; color: gray;">📁 File upload is available after form submission.</p>
    @endif

    <form method="POST" action="{{ route('member.local-forms.cancel', $form->id) }}" onsubmit="return confirm('Are you sure you want to cancel this travel form?');">
        @csrf
        @method('PATCH')
        <button type="submit" class="cancel-button">❌ Cancel Form</button>
    </form>

    <a href="{{ route('dashboard') }}">
        <button style="margin-top: 30px;">⬅ Back to Dashboard</button>
    </a>
</div>

<script>
    function toggleOther(selectEl, id) {
        const otherInput = document.getElementById('other-' + id);
        if (selectEl.value === '__other__') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
            otherInput.value = '';
        }
    }
</script>
@endsection
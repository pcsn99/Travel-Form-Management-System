@extends('layouts.app')

@section('title', 'Edit Overseas Travel Form')

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
        background-color: rgba(0,0,0,0.55);
        border-radius: 12px;
        z-index: 0;
    }

    .header-banner h2 {
        position: relative;
        z-index: 1;
        font-size: 28px;
    }

    .card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        padding: 30px;
        margin-bottom: 30px;
    }

    textarea, select, input[type="text"], input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 5px;
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

    .form-section {
        margin-bottom: 20px;
    }

    .file-list li {
        margin-bottom: 10px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .btn-cancel {
        background-color: #6c757d;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-block;
        margin-top: 10px;
    }

    .btn-cancel:hover {
        background-color: #5a6268;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>Edit Overseas Travel Form for {{ $form->request->user->name }}</h2>
    </div>

    <form method="POST" action="{{ route('Overseas-forms.update', $form->id) }}">
        @csrf

        <div class="card">
            @foreach($questions as $q)
                @php
                    $choices = is_array($q->choices) ? $q->choices : json_decode($q->choices ?? '[]', true);
                    $existingAnswer = $form->answers->where('question_id', $q->id)->first()?->answer ?? '';
                    $isOther = $q->type === 'choice' && $q->allow_other && !in_array($existingAnswer, $choices);
                @endphp

                <div class="form-section">
                    <label><strong>{{ $q->question }}</strong></label>

                    @if($q->type === 'text')
                        <textarea name="answers[{{ $q->id }}]" rows="2">{{ $existingAnswer }}</textarea>

                    @elseif($q->type === 'choice')
                        <select name="answers[{{ $q->id }}]" onchange="toggleOther(this, '{{ $q->id }}')">
                            <option value="">-- Select an option --</option>
                            @foreach($choices as $choice)
                                <option value="{{ $choice }}" {{ $existingAnswer === $choice ? 'selected' : '' }}>
                                    {{ $choice }}
                                </option>
                            @endforeach
                            @if($q->allow_other)
                                <option value="__other__" {{ $isOther ? 'selected' : '' }}>Other (please specify)</option>
                            @endif
                        </select>

                        @if($q->allow_other)
                            <input 
                                type="text" 
                                name="answers_other[{{ $q->id }}]" 
                                id="other-{{ $q->id }}" 
                                placeholder="Other..."
                                style="margin-top: 5px; display: {{ $isOther ? 'inline-block' : 'none' }};"
                                value="{{ $isOther ? $existingAnswer : '' }}"
                            >
                        @endif
                    @endif
                </div>
            @endforeach

            <div class="form-actions">
                <button type="submit">✅ Save Changes</button>
                <a href="{{ route('Overseas-forms.show', $form->id) }}" class="btn-cancel"> Cancel</a>
            </div>
        </div>
    </form>

    <form action="{{ route('attachments.upload') }}" method="POST" enctype="multipart/form-data" class="card">
        @csrf
        <input type="hidden" name="form_type" value="Overseas">
        <input type="hidden" name="form_id" value="{{ $form->id }}">

        <label><strong>Upload Additional Requirement (PDF or Image):</strong></label>
        <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
        <button type="submit">Upload</button>
    </form>

    @if($form->attachments->count())
        <div class="card">
            <h4>📁 Uploaded Requirements</h4>
            <ul class="file-list">
                @foreach($form->attachments as $file)
                    <li>
                        <a href="{{ route('attachments.download', $file->id) }}" target="_blank">
                            {{ $file->original_name }}
                        </a>
                        <form action="{{ route('attachments.delete', $file->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this file?')" style="margin-left: 10px;">🗑️</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<script>
    function toggleOther(selectEl, id) {
        const otherInput = document.getElementById('other-' + id);
        if (selectEl.value === '__other__') {
            otherInput.style.display = 'inline-block';
        } else {
            otherInput.style.display = 'none';
            otherInput.value = '';
        }
    }
</script>
@endsection

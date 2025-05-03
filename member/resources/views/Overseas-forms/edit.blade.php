@extends('layouts.app')

@section('content')
    <h2>✏️ Fill Out Overseas Travel Form</h2>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif

    {{-- 🧾 Answer Questions --}}
    <form id="form-answers" method="POST" action="{{ route('member.Overseas-forms.update', $form->id) }}">
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
                    <textarea name="answers[{{ $q->id }}]" rows="2" cols="60" required>{{ $existingAnswer }}</textarea>

                @elseif($q->type === 'choice')
                    <select name="answers[{{ $q->id }}]" onchange="toggleOther(this, '{{ $q->id }}')" required>
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

        <button type="submit">✅ Submit Form</button>
    </form>

    {{-- 📎 Upload Additional File (only after submission) --}}
    @if($form->status === 'submitted')
        <form action="{{ route('attachments.upload') }}" method="POST" enctype="multipart/form-data" style="margin-top: 30px;">
            @csrf
            <input type="hidden" name="form_type" value="Overseas">
            <input type="hidden" name="form_id" value="{{ $form->id }}">

            <label><strong>📎 Upload Additional Requirement (PDF or Image):</strong></label><br>
            <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
            <button type="submit">📤 Upload</button>
        </form>

        @if($form->attachments->count())
            <h4 style="margin-top: 20px;">📁 Uploaded Files</h4>
            <ul>
                @foreach($form->attachments as $file)
                    <li>
                        <a href="{{ route('attachments.download', $file->id) }}" target="_blank">{{ $file->original_name }}</a>
                        <form action="{{ route('attachments.delete', $file->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this file?')" style="color: red; margin-left: 10px;">🗑️</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    @else
        <p style="margin-top: 30px; color: gray;">📎 File upload is available after form submission.</p>
    @endif

    {{-- ❌ Cancel Form --}}
    <form method="POST" action="{{ route('member.Overseas-forms.cancel', $form->id) }}"
        onsubmit="return confirm('Are you sure you want to cancel this travel form? This cannot be undone.');"
        style="margin-top: 20px;">
        @csrf
        @method('PATCH')
        <button type="submit" style="color: red;">❌ Cancel Form</button>
    </form>

    {{-- ⬅ Back Button --}}
    <a href="{{ route('dashboard') }}">
        <button style="margin-top: 30px;">⬅ Back to Dashboard</button>
    </a>

    <script>
        function toggleOther(selectEl, id) {
            const otherInput = document.getElementById('other-' + id);
            if (selectEl.value === '__other__') {
                otherInput.style.display = 'inline-block';
                otherInput.required = true;
            } else {
                otherInput.style.display = 'none';
                otherInput.required = false;
                otherInput.value = '';
            }
        }
    </script>
@endsection

@extends('layouts.app')

@section('content')
    <h2>✏️ Edit Local Travel Form for {{ $form->request->user->name }}</h2>

    <form method="POST" action="{{ route('local-forms.update', $form->id) }}">
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
                    <textarea name="answers[{{ $q->id }}]" rows="2" cols="60">{{ $existingAnswer }}</textarea>

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

        <br>
        <button type="submit">✅ Save Changes</button>
        <a href="{{ route('local-forms.show', $form->id) }}">🔙 Cancel</a>
    </form>

    <form action="{{ route('attachments.upload') }}" method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf
        <input type="hidden" name="form_type" value="local">
        <input type="hidden" name="form_id" value="{{ $form->id }}">
    
        <label><strong>Upload Additional Requirement (PDF or Image):</strong></label><br>
        <input type="file" name="file" accept=".jpg,.jpeg,.png,.pdf" required>
        <button type="submit">Upload</button>




    </form>

    @if($form->attachments->count())
        <div style="margin-top: 20px;">
            <h4>📁 Uploaded Requirements</h4>
            <ul>
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

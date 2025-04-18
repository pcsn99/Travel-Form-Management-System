@extends('layouts.app')

@section('content')
    <h2>Overseas Travel Form</h2>

    @if(in_array($form->status, ['submitted', 'pending']))
        <a href="{{ route('Overseas-forms.edit', $form->id) }}">
            <button>✏️ Edit Form</button>
        </a>
    @endif

    <a href="{{ route('Overseas-forms.export', $form->id) }}">
        <button>📥 Export to Excel</button>
    </a>

    <p><strong>User:</strong> {{ $form->request->user->name }}</p>
    <p><strong>Status:</strong> {{ ucfirst($form->status) }}</p>
    <p><strong>Submitted:</strong> {{ $form->submitted_at }}</p>

    <h4>Answers:</h4>
    <ul>
        @foreach($form->answers as $answer)
            <li><strong>{{ $answer->question->question }}:</strong> {{ $answer->answer }}</li>
        @endforeach
    </ul>

    <form method="POST" action="{{ route('Overseas-forms.approve', $form->id) }}" onsubmit="return confirm('Are you sure you want to approve this form?');">
        @csrf
        <textarea name="admin_comment" placeholder="Optional comment..."></textarea>
        <button type="submit">✅ Approve</button>
    </form>

    <form method="POST" action="{{ route('Overseas-forms.reject', $form->id) }}" onsubmit="return confirm('Are you sure you want to reject this form?');">
        @csrf
        <textarea name="admin_comment" placeholder="Optional rejection reason..."></textarea>
        <button type="submit">❌ Reject</button>
    </form>

    @if($form->status !== 'pending')
    <form method="POST" action="{{ route('Overseas-forms.reset', $form->id) }}" onsubmit="return confirm('Reset this form back to pending status?');">
        @csrf
        <button type="submit">🔁 Set Status to Pending</button>
    </form>
    @endif

    @if($form->admin_comment)
        <p><strong>Admin Comment:</strong> {{ $form->admin_comment }}</p>
    @endif

    @if($form->attachments->count())
        <h4>📁 Additional Requirements</h4>
        <ul>
            @foreach($form->attachments as $file)
                <li>
                    <a href="{{ route('attachments.download', $file->id) }}" target="_blank">
                        {{ $file->original_name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('Overseas-forms.index') }}">⬅️ Back</a>
@endsection

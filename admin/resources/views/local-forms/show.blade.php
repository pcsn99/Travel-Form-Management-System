@extends('layouts.app')

@section('content')
    <h2>📝 Local Travel Form Details</h2>

    <div style="margin-bottom: 20px;">
        @if(in_array($form->status, ['submitted', 'pending']))
            <a href="{{ route('local-forms.edit', $form->id) }}">
                <button>✏️ Edit Form</button>
            </a>
        @endif

        <a href="{{ route('local-forms.export', $form->id) }}">
            <button>📥 Export to Excel</button>
        </a>
    </div>

    <div style="margin-bottom: 10px;">
        <p><strong>User:</strong> {{ $form->request->user->name }}</p>
        <p><strong>Status:</strong> {{ ucfirst($form->status) }}</p>
        <p><strong>Submitted:</strong> {{ $form->submitted_at }}</p>
        <p><strong>Departure Date:</strong> {{ $form->request->intended_departure_date }}</p>
        <p><strong>Return Date:</strong> {{ $form->request->intended_return_date }}</p>
    </div>

    <h4>🧾 Answers:</h4>
    <ul>
        @foreach($form->answers as $answer)
            <li><strong>{{ $answer->question->question }}:</strong> {{ $answer->answer }}</li>
        @endforeach
    </ul>

    <hr style="margin: 20px 0;">

    @if(in_array($form->status, ['submitted', 'pending']))
        <form method="POST" action="{{ route('local-forms.approve', $form->id) }}" onsubmit="return confirm('Are you sure you want to approve this form?');" style="margin-bottom: 10px;">
            @csrf
            <textarea name="admin_comment" placeholder="Optional comment..." rows="2" style="width:100%;"></textarea>
            <button type="submit">✅ Approve</button>
        </form>

        <form method="POST" action="{{ route('local-forms.reject', $form->id) }}" onsubmit="return confirm('Are you sure you want to reject this form?');" style="margin-bottom: 10px;">
            @csrf
            <textarea name="admin_comment" placeholder="Optional rejection reason..." rows="2" style="width:100%;"></textarea>
            <button type="submit">❌ Reject</button>
        </form>
    @endif

    @if($form->status !== 'pending')
        <form method="POST" action="{{ route('local-forms.reset', $form->id) }}" onsubmit="return confirm('Reset this form back to pending status?');" style="margin-bottom: 10px;">
            @csrf
            <button type="submit">🔁 Set Status to Pending</button>
        </form>
    @endif

    @if($form->admin_comment)
        <p><strong>🗒 Admin Comment:</strong> {{ $form->admin_comment }}</p>
    @endif

    @if($form->attachments->count())
        <hr>
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

    <hr>
    <a href="{{ route('local-forms.index') }}">⬅️ Back</a>
@endsection

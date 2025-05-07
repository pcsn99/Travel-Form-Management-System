@extends('layouts.app')

@section('title', 'Local Travel Form Details')

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
        background-color: rgba(0,0,0,0.75);
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
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
        padding: 30px;
        margin-bottom: 30px;
    }

    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-top: 10px;
    }

    button {
        background-color: #6a4c93;
        color: white;
        font-weight: bold;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        margin-top: 10px;
        display: inline-block;
        width: auto;
    }

    button:hover {
        background-color: #563d7c;
    }

    .btn-link {
        display: inline-block;
        margin-top: 15px;
        color: #007bff;
        font-weight: bold;
        text-decoration: none;
    }

    .btn-link:hover {
        text-decoration: underline;
    }

    .button-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>Local Travel Form Details</h2>
    </div>

    <div class="card">
        <div class="button-group">
            @if(in_array($form->status, ['submitted', 'pending']))
                <a href="{{ route('local-forms.edit', $form->id) }}">
                    <button>✏️ Edit Form</button>
                </a>
            @endif

            <a href="{{ route('local-forms.export', $form->id) }}">
                <button>📥 Export to Excel</button>
            </a>
        </div>

        <hr>
        <p><strong>User:</strong> {{ $form->request->user->name }}</p>
        <p><strong>Status:</strong> {{ ucfirst($form->status) }}</p>
        <p><strong>Submitted:</strong> {{ $form->submitted_at }}</p>
        <p><strong>Departure Date:</strong> {{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</p>
        <p><strong>Return Date:</strong> {{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</p>

        <h3 >Form Answers:</h3>
        <ul>
            @foreach($form->answers as $answer)
                <li><strong>{{ $answer->question->question }}:</strong> {{ $answer->answer }}</li>
            @endforeach
        </ul>

        @if(in_array($form->status, ['submitted', 'pending']))
            <form method="POST" action="{{ route('local-forms.approve', $form->id) }}" onsubmit="return confirm('Are you sure you want to approve this form?');">
                @csrf
                <textarea name="admin_comment" placeholder="Optional comment..." rows="2"></textarea>
                <button type="submit">✅ Approve</button>
            </form>

            <div style="margin-top: 20px;"></div>

            <form method="POST" action="{{ route('local-forms.reject', $form->id) }}" onsubmit="return confirm('Are you sure you want to reject this form?');">
                @csrf
                <textarea name="admin_comment" placeholder="Optional rejection reason..." rows="2"></textarea>
                <button type="submit" style="background-color: #dc3545;">❌ Reject</button>
            </form>
        @endif

        @if($form->status !== 'pending')
            <form method="POST" action="{{ route('local-forms.reset', $form->id) }}" onsubmit="return confirm('Reset this form back to pending status?');">
                @csrf
                <button type="submit" style="background-color: #ffc107; color: black;">🔁 Set Status to Pending</button>
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
        <a href="{{ route('local-forms.index') }}" class="btn-link">⬅️ Back to Local Forms</a>
    </div>
</div>
@endsection

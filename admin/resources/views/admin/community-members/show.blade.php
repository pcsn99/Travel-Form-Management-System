@extends('layouts.app')

@section('content')
    <h2>👤 {{ $member->name }}'s Profile</h2>

    <p><strong>Email:</strong> {{ $member->email }}</p>
    <p><strong>Joined:</strong> {{ $member->created_at->format('Y-m-d') }}</p>

    <p>
        <strong>Status:</strong> 
        @if($isInTravel)
            <span style="color: orange;">🧳 Currently Traveling</span>
        @else
            <span style="color: green;">✅ Available</span>
        @endif
    </p>

    <h3>📝 Recent Travel Request</h3>
    @if($member->travelRequests->count())
        <p><a href="{{ route('travel-requests.show', $member->travelRequests->first()->id) }}">View Latest Request</a></p>
    @else
        <p>No travel requests yet.</p>
    @endif

    <h3>📋 Recent Travel Form</h3>
    @if($member->localForms->count())
        <p><a href="{{ route('local-forms.show', $member->localForms->first()->id) }}">View Latest Local Form</a></p>
    @elseif($member->OverseasForms->count())
        <p><a href="{{ route('Overseas-forms.show', $member->OverseasForms->first()->id) }}">View Latest Overseas Form</a></p>
    @else
        <p>No travel forms yet.</p>
    @endif

    <a href="{{ route('admin.members.history', $member->id) }}">
        <button>📜 View Full Travel History</button>
    </a>

    <br>
    <a href="{{ route('admin.members.index') }}">⬅ Back to Member List</a>
@endsection

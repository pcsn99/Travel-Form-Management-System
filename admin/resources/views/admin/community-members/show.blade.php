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
        <p>
            <a href="{{ route('travel-requests.show', $member->travelRequests->first()->id) }}">
                View Latest Request
            </a>
        </p>
    @else
        <p>No travel requests yet.</p>
    @endif

    <h3>📋 Recent Travel Form</h3>
    @if($member->localForms->count())
        <p>
            <a href="{{ route('local-forms.show', $member->localForms->first()->id) }}">
                View Latest Local Form
            </a>
        </p>
    @elseif($member->OverseasForms->count())
        <p>
            <a href="{{ route('Overseas-forms.show', $member->OverseasForms->first()->id) }}">
                View Latest Overseas Form
            </a>
        </p>
    @else
        <p>No travel forms yet.</p>
    @endif

    <a href="{{ route('admin.members.history', $member->id) }}">
        <button>📜 View Full Travel History</button>
    </a>

    <hr>

    <h3>📁 Uploaded Files</h3>

    {{-- Profile Photo --}}
    <div class="mb-3">
        <strong>Profile Photo:</strong><br>
        @if($member->profilePhoto)
            <img src="{{ asset('storage/' . $member->profilePhoto->photo_path) }}" width="100" class="rounded">
        @else
            <span class="text-muted">No photo uploaded.</span>
        @endif
    </div>

    {{--  CV --}}
    <div class="mb-3">
        <strong>CV:</strong><br>
        @if(isset($files['cv'][0]))
            <a href="{{ route('user-file.download', $files['cv'][0]->id) }}" target="_blank">
                {{ $files['cv'][0]->original_name }}
            </a>
        @else
            <span class="text-muted">No CV uploaded.</span>
        @endif
    </div>

    {{--  Medical Records --}}
    <div class="mb-3">
        <strong>Medical Records:</strong><br>
        @if(isset($files['medical']) && count($files['medical']))
            <ul>
                @foreach($files['medical'] as $file)
                    <li>
                        <a href="{{ route('user-file.download', $file->id) }}" target="_blank">
                            {{ $file->original_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <span class="text-muted">No medical records uploaded.</span>
        @endif
    </div>

    {{--  Other Files --}}
    <div class="mb-4">
        <strong>Other Files:</strong><br>
        @if(isset($files['other']) && count($files['other']))
            <ul>
                @foreach($files['other'] as $file)
                    <li>
                        <a href="{{ route('user-file.download', $file->id) }}" target="_blank">
                            {{ $file->original_name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <span class="text-muted">No other files uploaded.</span>
        @endif
    </div>

    <br>
    <a href="{{ route('admin.members.index') }}">⬅ Back to Member List</a>
@endsection

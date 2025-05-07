@extends('layouts.app')

@section('title', $member->name . "'s Profile")

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
    }

    .profile-header {
        text-align: center;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        background: white;
        padding: 20px;
        margin-bottom: 30px;
    }

    .card h5 {
        font-size: 18px;
        margin-bottom: 15px;
        color: #17224D;
    }

    .btn-section {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }

    .btn-section a {
        background-color: #2d6a4f;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.3s ease;
        min-width: 220px;
        text-align: center;
    }

    .btn-section a:nth-child(2) {
        background-color: #0077b6;
    }

    .btn-section a:nth-child(3) {
        background-color: #6a4c93;
    }

    .btn-section a:hover {
        filter: brightness(90%);
    }

    .back-btn {
        background-color: #6c757d;
        text-align: center;
        display: block;
        max-width: 300px;
        margin: 30px auto 0;
        padding: 12px;
        font-weight: bold;
        color: white;
        text-decoration: none;
        border-radius: 6px;
    }

    .back-btn:hover {
        background-color: #5a6268;
    }

    .profile-photo {
        border-radius: 6px;
        margin-top: 10px;
    }

    .file-entry {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #ccc;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="profile-header">{{ $member->name }}'s Profile</div>

    <div class="card">
        <h5>Member Details</h5>
        <p><strong>Email:</strong> {{ $member->email }}</p>
        <p><strong>Joined:</strong> {{ $member->created_at->format('F d, Y') }}</p>
        <p><strong>Status:</strong> 
            @if($isInTravel)
                <span style="color: orange; font-weight: bold;">Currently Traveling</span>
            @else
                <span style="color: green; font-weight: bold;">Available</span>
            @endif
        </p>
    </div>

    <div class="card">
        <h5>Uploaded Files</h5>
        <p><strong>Profile Photo:</strong><br>
            @if($member->profilePhoto && Storage::disk('shared')->exists($member->profilePhoto->photo_path))
                <img src="{{ asset('shared/' . $member->profilePhoto->photo_path) }}" class="profile-photo" width="120">
            @else
                <span class="text-muted">No photo uploaded.</span>
            @endif
        </p>

        @foreach ([
            'cv' => 'Curriculum Vitae (CV)',
            'medical' => 'Medical Records',
            'other' => 'Other Files'
        ] as $type => $label)
            <p><strong>{{ $label }}:</strong><br>
                @if(isset($files[$type]) && count($files[$type]))
                    <ul>
                        @foreach ($files[$type] as $file)
                            @if(Storage::disk('shared')->exists($file->file_path))
                                <li class="file-entry">
                                    <a href="{{ url('/user-files/' . $file->id . '/download') }}" target="_blank">
                                        {{ $file->original_name }}
                                    </a>
                                    
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">No {{ strtolower($label) }} uploaded.</span>
                @endif
            </p>
        @endforeach
    </div>

    <div class="btn-section">
        @if($member->travelRequests->count())
            <a href="{{ route('travel-requests.show', $member->travelRequests->first()->id) }}">View Latest Travel Request</a>
        @endif

        @if($member->localForms->count())
            <a href="{{ route('local-forms.show', $member->localForms->first()->id) }}">View Latest Local Form</a>
        @endif

        @if($member->OverseasForms->count())
            <a href="{{ route('Overseas-forms.show', $member->OverseasForms->first()->id) }}">View Latest Overseas Form</a>
        @endif

        <a href="{{ route('admin.members.history', $member->id) }}">View Full Travel History</a>
    </div>

    <a href="{{ route('admin.members.index') }}" class="back-btn">⬅ Back to Member List</a>
</div>
@endsection

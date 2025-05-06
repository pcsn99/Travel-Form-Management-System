@extends('layouts.app')

@section('title', '{{ $member->name }}\'s Profile')

@section('styles')
<style>
   
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    h2 {
        text-align: center
    }

   
    .container-custom {
        max-width: 800px;
        margin: auto;
        padding-top: 20px;
    }

    
    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 50px;
    }

    .card-header {
        background-color: #17224D;
        color: white;
        font-size: 18px;
        font-weight: bold;
        border-radius: 6px 6px 0 0;
        padding: 15px;
        text-align: center;
    }

 
    .form-group {
        display: flex;
        align-items: center; 
        justify-content: space-between;
        width: 100%;
        gap: 15px; 
    }

    input[type="file"] {
        flex-grow: 1; 
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
    }

    button {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        flex-shrink: 0; 
    }

    button:hover {
        background-color: #1f2f5f;

    }

    .back-btn {
        background-color: #6c757d;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        margin-top: 20px;
    }

    .back-btn:hover {
        background-color: #5a6268;
    }


</style>
@endsection

@section('content')

<div class="container-custom">
    <h2>{{ $member->name }}'s Profile</h2>
    
    <div class="profile-wrapper">
        <div class="column-left">
            <div class="card">
                <div class="card-header">Member Details</div>
                <div class="card-body">
                    <p><strong>Email:</strong> {{ $member->email }}</p>
                    <p><strong>Joined:</strong> {{ $member->created_at->format('Y-m-d') }}</p>
                    <p><strong>Status:</strong> 
                        @if($isInTravel)
                            <span style="color: orange;">Currently Traveling</span>
                        @else
                            <span style="color: green;">Available</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Uploaded Files</div>
                <div class="card-body">
                    <p><strong>Profile Photo:</strong><br>
                        @if($member->profilePhoto)
                            <img src="{{ asset('storage/' . $member->profilePhoto->photo_path) }}" class="rounded" width="100">
                        @else
                            <span class="text-muted">No photo uploaded.</span>
                        @endif
                    </p>

                    <p><strong>CV:</strong><br>
                        @if(isset($files['cv'][0]))
                            <a href="{{ route('user-file.download', $files['cv'][0]->id) }}" target="_blank">
                                {{ $files['cv'][0]->original_name }}
                            </a>
                        @else
                            <span class="text-muted">No CV uploaded.</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="column-right">
            <div class="card">
                <div class="card-header">Recent Travel Request</div>
                <div class="card-body">
                    @if($member->travelRequests->count())
                        <p><a href="{{ route('travel-requests.show', $member->travelRequests->first()->id) }}">View Latest Request</a></p>
                    @else
                        <p>No travel requests yet.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">Recent Travel Form</div>
                <div class="card-body">
                    @if($member->localForms->count())
                        <p><a href="{{ route('local-forms.show', $member->localForms->first()->id) }}">View Latest Local Form</a></p>
                    @elseif($member->OverseasForms->count())
                        <p><a href="{{ route('Overseas-forms.show', $member->OverseasForms->first()->id) }}">View Latest Overseas Form</a></p>
                    @else
                        <p>No travel forms yet.</p>
                    @endif
                </div>
            </div>

            <a href="{{ route('admin.members.history', $member->id) }}">
                <button>View Full Travel History</button>
            </a>
        </div>
    </div>

    <br>
    <a href="{{ route('admin.members.index') }}" class="back-btn">⬅ Back to Member List</a>

</div>

@endsection
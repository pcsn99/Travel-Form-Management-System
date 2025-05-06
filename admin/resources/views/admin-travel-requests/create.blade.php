@extends('layouts.app')

@section('title', 'Create Travel Request')

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
        margin-bottom: 15px;
    }

    select, input[type="date"], input[type="text"], textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
        font-size: 16px;
    }

    button {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        width: 100%;
        border: none;
        margin-top: 15px;
        cursor: pointer;
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
    <!-- ✅ Create Travel Request -->
    <div class="card">
        <div class="card-header">Create Travel Request for {{ $user->name }}</div>
        <div class="card-body">
            
            <form method="POST" action="{{ route('admin-travel-requests.store', $user->id) }}">
                @csrf

                <div class="form-group">
                    <label><strong>Travel Type:</strong></label>
                    <select name="type" required>
                        <option value="local">Local</option>
                        <option value="Overseas">Overseas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label><strong>Event:</strong></label>
                    <input type="text" name="event" placeholder="Enter event name..." required>
                </div>

                <div class="form-group">
                    <label><strong>Purpose:</strong></label>
                    <input type="text" name="purpose" placeholder="Enter purpose of travel..." required>
                </div>

                <div class="form-group">
                    <label><strong>Intended Departure Date:</strong></label>
                    <input type="date" name="intended_departure_date" required>
                </div>

                <div class="form-group">
                    <label><strong>Intended Return Date:</strong></label>
                    <input type="date" name="intended_return_date" required>
                </div>

                @foreach($questions as $question)
                    <div class="form-group">
                        <label>{{ $question->question }}</label>
                        <textarea name="answers[{{ $question->id }}]" rows="2" placeholder="Answer here..."></textarea>
                    </div>
                @endforeach

                <button type="submit">Submit Travel Request</button>
            </form>

            <a href="{{ route('travel-requests.index') }}" class="back-btn">Back to Requests</a>
        </div>
    </div>
</div>

@endsection
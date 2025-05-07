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
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        background: #ffffff;
        padding: 30px;
        margin-bottom: 40px;
    }

    .card-header {
        background-color: #17224D;
        color: white;
        font-size: 20px;
        font-weight: bold;
        border-radius: 6px 6px 0 0;
        padding: 15px;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        margin-bottom: 6px;
        display: block;
    }

    select, input[type="date"], textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        background: #f9f9f9;
    }

    button[type="submit"] {
        background-color: #17224D;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 12px 24px;
        border-radius: 6px;
        border: none;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #1f2f5f;
    }

    .back-btn {
        margin-top: 20px;
        display: block;
        text-align: center;
        background-color: #6c757d;
        color: white;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        text-decoration: none;
    }

    .back-btn:hover {
        background-color: #5a6268;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="card">
        <div class="card-header">Create Travel Request for {{ $user->name }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin-travel-requests.store', $user->id) }}">
                @csrf

                <div class="form-group">
                    <label>Travel Type</label>
                    <select name="type" required>
                        <option value="local">Local</option>
                        <option value="Overseas">Overseas</option>
                    </select>
                </div>

                <div style="display: flex; gap: 20px;" class="form-group">
                    <div style="flex: 1;">
                        <label>Departure Date:</label>
                        <input type="date" name="intended_departure_date" required>
                    </div>
                    <div style="flex: 1;">
                        <label>Return Date:</label>
                        <input type="date" name="intended_return_date" required>
                    </div>
                </div>

                @foreach($questions as $question)
                    <div class="form-group">
                        <label>{{ $question->question }}</label>
                        <textarea name="answers[{{ $question->id }}]" rows="2" placeholder="Answer here..." required></textarea>
                    </div>
                @endforeach

                <button type="submit">Submit Travel Request</button>
            </form>

            <a href="{{ route('travel-requests.index') }}" class="back-btn">Back to Requests</a>
        </div>
    </div>
</div>
@endsection

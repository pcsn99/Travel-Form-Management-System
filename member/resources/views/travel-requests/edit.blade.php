@extends('layouts.app')

@section('title', 'Edit Travel Request')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        position: relative;
        padding: 30px 20px;
        font-size: 28px;
        font-weight: bold;
        text-align: center;
        color: white;
        margin-bottom: 40px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        text-shadow: 1px 1px 4px #000;
    }

    .dashboard-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset('images/bg.jpeg') }}') center/cover no-repeat;
        opacity: 0.25;
        z-index: 0;
    }

    .dashboard-header span {
        position: relative;
        z-index: 1;
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

    select, input[type="date"], input[type="text"], textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
        margin-bottom: 15px;
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
    <div class="dashboard-header"><span>Travel Request Form</span></div>

    <div class="card">
        <div class="card-header">Edit Travel Request</div>
        <div class="card-body">
            <form method="POST" action="{{ route('travel-requests.update', $request->id) }}" onsubmit="return validateDate();">
                @csrf

                <label>Type of Travel:</label>
                <input type="text" value="{{ ucfirst($request->type) }}" disabled>
                <input type="hidden" name="type" value="{{ $request->type }}">

                <label>Departure Date:</label>
                <input type="date" name="intended_departure_date" id="departureDate" value="{{ $request->intended_departure_date }}" required>

                <label>Return Date:</label>
                <input type="date" name="intended_return_date" id="returnDate" value="{{ $request->intended_return_date }}" required>

                @foreach($questions as $q)
                    @php
                        $existingAnswer = $request->answers->firstWhere('question_id', $q->id)?->answer ?? '';
                    @endphp
                    <div style="margin-bottom: 15px;">
                        <label><strong>{{ $q->question }}</strong></label>
                        <textarea name="answers[{{ $q->id }}]" rows="2" required>{{ $existingAnswer }}</textarea>
                    </div>
                @endforeach

                <button type="submit">Save Changes</button>
                <a href="{{ route('dashboard') }}" class="back-btn">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script>
    function validateDate() {
        const dep = new Date(document.getElementById('departureDate').value);
        const ret = new Date(document.getElementById('returnDate').value);
        const today = new Date();

        if (ret < dep) {
            alert("Return date cannot be earlier than departure date.");
            return false;
        }

        return true;
    }
</script>
@endsection

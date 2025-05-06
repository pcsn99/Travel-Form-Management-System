@extends('layouts.app')

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

<h2>Edit Travel Request</h2>

<form method="POST" action="{{ route('travel-requests.update', $request->id) }}" onsubmit="return validateDate();">
    @csrf

    <label>Type of Travel:</label>
    <input type="text" value="{{ ucfirst($request->type) }}" Disabled>
    <input type="hidden" name="type" value="{{ $request->type }}">
    <br><br>

    <label>Departure Date:</label>
    <input type="date" name="intended_departure_date" id="departureDate" value="{{ $request->intended_departure_date }}" required><br>

    <label>Return Date:</label>
    <input type="date" name="intended_return_date" id="returnDate" value="{{ $request->intended_return_date }}" required><br><br>

    
    @foreach($questions as $q)
        @php
            $existingAnswer = $request->answers->firstWhere('question_id', $q->id)?->answer ?? '';
        @endphp
        <div style="margin-bottom: 15px;">
            <label><strong>{{ $q->question }}</strong></label><br>
            <textarea name="answers[{{ $q->id }}]" rows="2" cols="60" required>{{ $existingAnswer }}</textarea>
        </div>
    @endforeach

    <button type="submit">Save Changes</button>
    <a href="{{ route('dashboard') }}">Cancel</a>
</form>



<script>
    function validateDate() {
        const travelType = document.getElementById('travelType').value;
        const dep = new Date(document.getElementById('departureDate').value);
        const ret = new Date(document.getElementById('returnDate').value);
        const today = new Date();

        let minDep = new Date(today);
        if (travelType === 'local') {
            minDep.setDate(minDep.getDate() + 7);
        } else if (travelType === 'Overseas') {
            minDep.setMonth(minDep.getMonth() + 2);
        }

        if (dep < minDep) {
            const warn = (travelType === 'local')
                ? "For local travel, please submit at least 1 week in advance. Proceed?"
                : "For Overseas travel, please submit at least 2 months in advance. Proceed?";
            if (!confirm(warn)) return false;
        }

        if (ret < dep) {
            alert("Return date cannot be earlier than departure date.");
            return false;
        }

        return true;
    }
</script>


@endsection


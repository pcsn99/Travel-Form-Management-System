@extends('layouts.app')

@section('content')

<h2>✏️ Edit Travel Request</h2>

<form method="POST" action="{{ route('travel-requests.update', $request->id) }}" onsubmit="return validateDate();">
    @csrf

    <label>Type of Travel:</label>
    <input type="text" value="{{ ucfirst($request->type) }}" disabled>
    <input type="hidden" name="type" value="{{ $request->type }}">
    <br><br>

    <label>Departure Date:</label>
    <input type="date" name="intended_departure_date" id="departureDate" value="{{ $request->intended_departure_date }}" required><br>

    <label>Return Date:</label>
    <input type="date" name="intended_return_date" id="returnDate" value="{{ $request->intended_return_date }}" required><br><br>

    <h4>Answer the following:</h4>
    @foreach($questions as $q)
        @php
            $existingAnswer = $request->answers->firstWhere('question_id', $q->id)?->answer ?? '';
        @endphp
        <div style="margin-bottom: 15px;">
            <label><strong>{{ $q->question }}</strong></label><br>
            <textarea name="answers[{{ $q->id }}]" rows="2" cols="60" required>{{ $existingAnswer }}</textarea>
        </div>
    @endforeach

    <button type="submit">✅ Save Changes</button>
    <a href="{{ route('dashboard') }}">🔙 Cancel</a>
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


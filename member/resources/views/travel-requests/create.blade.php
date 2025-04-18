@extends('layouts.app')

@section('content')
<h2>📝 Create Travel Request</h2>

<form method="POST" action="{{ route('travel-requests.store') }}" onsubmit="return validateDate();">
    @csrf

    <label>Type of Travel:</label>
    <select name="type" id="travelType" required>
        <option value="">-- Select --</option>
        <option value="local">Local</option>
        <option value="Overseas">Overseas</option>
    </select><br><br>

    <label>Departure Date:</label>
    <input type="date" name="intended_departure_date" id="departureDate" required><br><br>

    <label>Return Date:</label>
    <input type="date" name="intended_return_date" id="returnDate" required><br><br>

    <h4>Answer the following:</h4>
    @foreach($questions as $q)
        <div style="margin-bottom: 15px;">
            <label><strong>{{ $q->question }}</strong></label><br>
            <textarea name="answers[{{ $q->id }}]" rows="2" cols="60" required></textarea>
        </div>
    @endforeach

    <button type="submit">✅ Submit Request</button>
</form>

<script>
    function validateDate() {
        const travelType = document.getElementById('travelType').value;
        const dep = new Date(document.getElementById('departureDate').value);
        const ret = new Date(document.getElementById('returnDate').value);
        const today = new Date();

        let minDep = new Date(today);
        if (travelType === 'local') {
            minDep.setDate(today.getDate() + 7);
        } else if (travelType === 'Overseas') {
            minDep.setMonth(today.getMonth() + 2);
        }

       
        if (dep < minDep) {
            const warnMsg = (travelType === 'local')
                ? 'For local travel, please submit at least 1 week in advance, or your request might automatically be rejected.\nDo you still want to proceed?'
                : 'For Overseas travel, please submit at least 2 months in advance.\nDo you still want to proceed?';

            if (!confirm(warnMsg)) return false;
        }

       
        if (ret < dep) {
            alert("Return date cannot be before departure date.");
            return false;
        }

        return true;
    }
</script>
@endsection
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
    .date-row {
        display: flex;
        gap: 20px;
    }
    .date-row > div {
        flex: 1;
    }
    .btn-primary {
        background-color: #17224D;
        border: none;
        width: 100%;
        padding: 12px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 6px;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')

<div class="container-custom">
    <!-- ✅ Dashboard Header -->
    <div class="dashboard-header"><span>Travel Request Form</span></div>

    <!-- ✅ Travel Request Form -->
    <div class="card">
        <div class="card-header">Create Travel Request</div>
        <div class="card-body">
            <form method="POST" action="{{ route('travel-requests.store') }}" onsubmit="return validateDate();">
                @csrf

                <label>Type of Travel:</label>
                <select name="type" id="travelType" required>
                    <option value="">-- Select --</option>
                    <option value="local">Local</option>
                    <option value="Overseas">Overseas</option>
                </select>

                <div class="date-row">
                    <div>
                        <label>Departure Date:</label>
                        <input type="date" name="intended_departure_date" id="departureDate" required>
                    </div>
                    <div>
                        <label>Return Date:</label>
                        <input type="date" name="intended_return_date" id="returnDate" required>
                    </div>
                </div>

                <h4>Answer the following:</h4>
                @foreach($questions as $q)
                    <div style="margin-bottom: 15px;">
                        <label><strong>{{ $q->question }}</strong></label>
                        <textarea name="answers[{{ $q->id }}]" rows="3" required></textarea>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary">Submit Request</button>
            </form>
        </div>
    </div>
</div>

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
                : 'For Overseas travel, please submit at least 2 months in advance, or your request might automatically be rejected.\nDo you still want to proceed?';

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

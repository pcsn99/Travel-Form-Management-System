@extends('layouts.app')

@section('title', 'Travel History: ' . $member->name)

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .container-custom {
        max-width: 1000px;
        margin: auto;
        padding-top: 20px;
    }

    h2 {
        text-align: center;
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.97);
        padding: 25px;
        margin-bottom: 30px;
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

    table {
        width: 100%;
        border-radius: 8px;
        border-collapse: collapse;
        background: white;
    }

    table th, table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    table th {
        background-color: #17224D;
        color: white;
        font-size: 16px;
    }

    .back-btn {
        display: block;
        background-color: #6c757d;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        text-align: center;
        text-decoration: none;
        margin-top: 30px;
        width: 100%;
    }

    .back-btn:hover {
        background-color: #5a6268;
    }

    a {
        color: #2980b9;
        font-weight: bold;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    @media screen and (max-width: 768px) {
        table th, table td {
            font-size: 14px;
            padding: 8px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <h2>Travel History: {{ $member->name }}</h2>

    <div class="card">
        <div class="card-header">Travel Requests</div>
        <div class="card-body">
            @if($travelRequests->count())
                <table id="requests-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Status</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($travelRequests as $request)
                            <tr>
                                <td>{{ ucfirst($request->type) }}</td>
                                <td>{{ ucfirst($request->status) }}</td>
                                <td><a href="{{ route('travel-requests.show', $request->id) }}">🔍</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p><i>No travel requests.</i></p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">Local Travel Forms</div>
        <div class="card-body">
            @if($localForms->count())
                <table id="local-forms-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Departure</th>
                            <th>Return</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($localForms as $form)
                            <tr>
                                <td>{{ ucfirst($form->status) }}</td>
                                <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') ?? '—' }}</td>
                                <td><a href="{{ route('local-forms.show', $form->id) }}">🔍</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p><i>No local travel forms.</i></p>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">Overseas Travel Forms</div>
        <div class="card-body">
            @if($OverseasForms->count())
                <table id="overseas-forms-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Departure</th>
                            <th>Return</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($OverseasForms as $form)
                            <tr>
                                <td>{{ ucfirst($form->status) }}</td>
                                <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') ?? '—' }}</td>
                                <td><a href="{{ route('Overseas-forms.show', $form->id) }}">🔍</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p><i>No overseas travel forms.</i></p>
            @endif
        </div>
    </div>

    <a href="{{ route('admin.members.show', $member->id) }}" class="back-btn">⬅ Back to Profile</a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#requests-table').DataTable();
            $('#local-forms-table').DataTable();
            $('#overseas-forms-table').DataTable();
        });
    </script>
</div>
@endsection

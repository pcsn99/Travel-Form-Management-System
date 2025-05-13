@extends('layouts.app')

@section('title', 'Travel Requests')

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

    .header-banner {
        position: relative;
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        text-align: center;
        color: white;
    }

    .header-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        border-radius: 12px;
        z-index: 0;
    }

    .header-banner h2,
    .header-banner .create-btn {
        position: relative;
        z-index: 1;
    }

    .create-btn {
        background-color: #2d6a4f;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .create-btn:hover {
        background-color: #22543d;
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

    .status-links {
        text-align: center;
        margin-bottom: 20px;
    }

    .status-links a {
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        color: white;
        margin: 0 10px;
        padding: 8px 14px;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    .status-links a[href*="pending"] {
        background-color: #ffc107;
    }

    .status-links a[href*="approved"] {
        background-color: #28a745;
    }

    .status-links a[href*="rejected"] {
        background-color: #dc3545;
    }

    .status-links a:hover {
        filter: brightness(90%);
    }

    .view-btn {
        background-color: #17224D;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .view-btn:hover {
        background-color: #1f2f5f;
    
    }

    .table-responsive-custom {
        overflow-x: auto;
        width: 100%;
    }

    .table-responsive-custom table {
        white-space: nowrap;
    }
    
    .container-custom {
        max-width: 100%;
        margin: auto;
        padding-top: 20px;
    }
</style>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>Manage Travel Requests</h2>
        <a href="{{ route('admin-travel-requests.search') }}" class="create-btn">+ Create Travel Request for Member</a>
    </div>

    <div class="card">
        <div class="card-header">Travel Requests ({{ ucfirst($status) }})</div>
        <div class="card-body">
            <div class="status-links">
                <a href="?status=pending">Pending</a>
                <a href="?status=approved">Approved</a>
                <a href="?status=rejected">Rejected</a>
            </div>

            @if($requests->count())
                <div class="table-responsive-custom">    
                    <table id="requests-table" class="display" style="width:100%">
                
                        @php
                            $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
                        @endphp
                        
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Departure</th>
                                <th>Return</th>
                                <th>Submitted</th>
                                @foreach($questions as $q)
                                    <th>{{ \Illuminate\Support\Str::limit($q->question, 20) }}</th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                <tr>
                                    <td>{{ $req->user->name }}</td>
                                    <td>{{ ucfirst($req->type) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->intended_departure_date)->format('F d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->intended_return_date)->format('F d, Y') }}</td>
                                    <td>{{ $req->created_at->format('F d, Y') }}</td>
                                    @foreach($questions as $q)
                                        @php
                                            $answer = $req->answers->firstWhere('question_id', $q->id);
                                        @endphp
                                        <td>{{ \Illuminate\Support\Str::limit($answer ? $answer->answer : '-', 30) }}</td>
                                    @endforeach
                                    <td>
                                        <a href="{{ route('travel-requests.show', $req->id) }}" class="view-btn">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <p class="text-center m-4">No travel requests found.</p>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#requests-table').DataTable();
    });
</script>
@endsection

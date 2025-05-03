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

    .create-btn {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .create-btn:hover {
        background-color: #1f2f5f;
    }

    
    .status-links {
        text-align: center;
        margin-bottom: 20px;
    }

    .status-links a {
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        color: #17224D;
        margin: 0 10px;
        padding: 8px 12px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.95);
        box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.2);
    }

    .status-links a:hover {
        background-color: #2980b9;
        color: white;
    }

    .table {
        width: 100%;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.95);
    }

    .table thead {
        background-color: #17224D;
        color: white;
        font-size: 16px;
    }

    .table th, .table td {
        padding: 12px;
        border: 1px solid #17224D;
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
</style>
@endsection

@section('content')

<div class="container-custom">
    <!-- ✅ Create Travel Request Button -->
    <a href="{{ route('admin-travel-requests.search') }}" class="create-btn">
        + Create Travel Request for Member
    </a>

    <!-- ✅ Travel Requests Section -->
    <div class="card">
        <div class="card-header">Travel Requests ({{ ucfirst($status) }})</div>
        <div class="card-body">
            
            <!-- ✅ Status Filters -->
            <div class="status-links">
                <a href="?status=pending">Pending</a>
                <a href="?status=approved">Approved</a>
                <a href="?status=rejected">Rejected</a>
            </div>

            <!-- ✅ Requests Table -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Departure</th>
                        <th>Return</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->user->name }}</td>
                            <td>{{ ucfirst($req->type) }}</td>
                            <td>{{ $req->intended_departure_date }}</td>
                            <td>{{ $req->intended_return_date }}</td>
                            <td>
                                <a href="{{ route('travel-requests.show', $req->id) }}" class="view-btn">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No travel requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
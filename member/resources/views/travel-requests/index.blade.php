@extends('layouts.app')

@section('title', 'All Travel Requests')
@section('styles')
<style>
   
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    h2 {
        text-align: center
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
    <h2>All Travel Requests</h2>

    @if($requests->count())
        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Departure</th>
                    <th>Return</th>
                    <th>Status</th>
                    <th>Admin Comment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr>
                        <td>{{ ucfirst($req->type) }}</td>
                        <td>{{ $req->intended_departure_date }}</td>
                        <td>{{ $req->intended_return_date }}</td>
                        <td>{{ ucfirst($req->status) }}</td>
                        <td>{{ $req->admin_comment ?: '-' }}</td>
                        <td>
                            @if($req->status === 'pending')
                                <a href="{{ route('travel-requests.edit', $req->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif
                            <a href="{{ route('travel-requests.show', $req->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No travel requests found.</p>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
@endsection

@extends('layouts.app')

@section('title', 'All Travel Requests')

@section('content')
    <h2>📑 All Travel Requests</h2>

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

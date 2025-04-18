@extends('layouts.app')

@section('content')

    <a href="{{ route('admin-travel-requests.search') }}">
        <button>➕ Create Travel Request for Member</button>
    </a>

    <br>
    <br>
    <br>

    
    <h2>Travel Requests ({{ ucfirst($status) }})</h2>




    <a href="?status=pending">Pending</a> |
    <a href="?status=approved">Approved</a> |
    <a href="?status=rejected">Rejected</a>

    <ul>
        @foreach($requests as $req)
            <li>
                {{ $req->user->name }} - {{ $req->type }} travel ({{ $req->intended_departure_date }} → {{ $req->intended_return_date }})
                <a href="{{ route('travel-requests.show', $req->id) }}">View</a>
            </li>
        @endforeach
    </ul>
@endsection

@extends('layouts.app')

@section('content')
<h2>🔍 Search Community Member</h2>

<form method="GET" action="{{ route('admin-travel-requests.find') }}">
    <input type="text" name="q" placeholder="Enter last name..." required>
    <button type="submit">Search</button>
</form>

@if(isset($users))
    <h3>Search Results:</h3>
    <ul>
        @forelse($users as $user)
            <li>
                {{ $user->name }} ({{ $user->email }})
                <a href="{{ route('admin-travel-requests.create', $user->id) }}">➕ Create Travel Request</a>
            </li>
        @empty
            <p>No matching users found.</p>
        @endforelse
    </ul>
@endif
@endsection

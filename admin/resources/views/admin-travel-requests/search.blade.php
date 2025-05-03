@extends('layouts.app')

@section('title', 'Search Community Member')

@section('styles')
<style>

    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
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

    
    .search-form {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
    }

    .search-input {
        width: 100%;
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
        font-size: 16px;
    }

    .search-btn {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }

    .search-btn:hover {
        background-color: #1f2f5f;
    }

    
    .results-list {
        list-style: none;
        padding: 0;
        margin-top: 15px;
    }

    .results-list li {
        padding: 10px;
        border-bottom: 1px solid #ccc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 16px;
    }

    .create-request-btn {
        background-color: #17224D;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .create-request-btn:hover {
        background-color: #1f2f5f;
    }
</style>
@endsection

@section('content')

<div class="container-custom">
  
    <div class="card">
        <div class="card-header">Search</div>
        <div class="card-body">
            
            <form method="GET" action="{{ route('admin-travel-requests.find') }}" class="search-form">
                <input type="text" name="q" class="search-input" placeholder="Enter last name..." required>
                <button type="submit" class="search-btn">Search</button>
            </form>

            @if(isset($users))
               
                <ul class="results-list">
                    @forelse($users as $user)
                        <li>
                            {{ $user->name }} ({{ $user->email }})
                            <a href="{{ route('admin-travel-requests.create', $user->id) }}" class="create-request-btn">+ Create Travel Request</a>
                        </li>
                    @empty
                        <p>No matching users found.</p>
                    @endforelse
                </ul>
            @endif
        </div>
    </div>
</div>

@endsection
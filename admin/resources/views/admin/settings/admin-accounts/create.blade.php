@extends('layouts.app')

@section('title', 'Create Admin Account')

@section('content')
<div class="container">
    <h2>Create Admin</h2>

    <form method="POST" action="{{ route('admin-accounts.store') }}">
        @csrf

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm Password:</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Admin</button>
        <a href="{{ route('admin-accounts.index') }}" class="btn btn-secondary">⬅ Back</a>
    </form>
</div>
@endsection

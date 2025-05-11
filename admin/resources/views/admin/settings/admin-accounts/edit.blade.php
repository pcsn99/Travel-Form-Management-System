@extends('layouts.app')

@section('title', 'Edit Admin Account')

@section('content')
<div class="container">
    <h2>Edit Admin</h2>

    <form method="POST" action="{{ route('admin-accounts.update', $admin->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
        </div>

        <div class="mb-3">
            <label>New Password (leave blank to keep current):</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Confirm New Password:</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin-accounts.index') }}" class="btn btn-secondary">⬅ Cancel</a>
    </form>
</div>
@endsection

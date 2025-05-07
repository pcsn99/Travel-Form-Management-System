@extends('layouts.app')

@section('title', 'Admin Accounts')

@section('content')
<div class="container">
    <h2>Admin Accounts</h2>

    <a href="{{ route('admin-accounts.create') }}" class="btn btn-primary mb-3">+ Add Admin</a>

    @if(session('success')) <p class="text-success">{{ session('success') }}</p> @endif
    @if(session('error')) <p class="text-danger">{{ session('error') }}</p> @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td>{{ $admin->name }}</td>
                <td>{{ $admin->email }}</td>
                <td>{{ $admin->created_at }}</td>
                <td>
                    <a href="{{ route('admin-accounts.edit', $admin->id) }}" class="btn btn-sm btn-warning">✏️ Edit</a>
                    @if(auth()->id() !== $admin->id)
                    <form action="{{ route('admin-accounts.destroy', $admin->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this admin?')">🗑️ Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

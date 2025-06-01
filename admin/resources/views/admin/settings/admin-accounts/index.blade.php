@extends('layouts.app')

@section('title', 'Admin Accounts')

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

    .header-title {
        text-align: center;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.97);
        margin-bottom: 30px;
    }

    .card-header {
        background-color: #17224D;
        color: white;
        font-size: 18px;
        font-weight: bold;
        border-radius: 12px 12px 0 0;
        padding: 15px;
        text-align: center;
    }

    .card-body {
        padding: 25px;
    }

    .table-responsive-custom {
        overflow-x: auto;
        position: relative;
    }

    .table-responsive-custom::after {
        content: '⇠ Scroll ⇢';
        position: absolute;
        bottom: 8px;
        right: 12px;
        font-size: 12px;
        color: #888;
    }

    table {
        width: 100%;
        white-space: nowrap;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    table th {
        background-color: #17224D;
        color: white;
        font-size: 16px;
    }

    .btn-sm {
        font-size: 14px;
        padding: 6px 10px;
    }

    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
    }

    .btn-warning {
        background-color: #ffc107;
        color: black;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .alert {
        margin-bottom: 20px;
    }

    @media screen and (max-width: 768px) {
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }

        .table-responsive-custom::after {
            font-size: 10px;
            right: 10px;
            bottom: 4px;
        }
    }
</style>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-custom">
    <h2 class="header-title">Admin Accounts</h2>

    <div class="mb-3">
        <a href="{{ route('admin-accounts.create') }}" class="btn btn-primary">+ Add Admin</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    <div class="card">
        <div class="card-body table-responsive-custom">
            <table id="admin-table" class="display nowrap">
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
                            <td>{{ $admin->created_at->format('F d, Y') }}</td>
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
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#admin-table').DataTable({ scrollX: true });
    });
</script>
@endsection

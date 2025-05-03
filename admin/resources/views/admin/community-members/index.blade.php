@extends('layouts.app')

@section('title', 'Community Members')

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

<!-- ✅ Include jQuery + DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')

<div class="container-custom">
 
    <div class="card">
        <div class="card-header">Community Members</div>
        <div class="card-body">
            
            <table id="members-table" class="table display" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Registered</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($members as $member)
                        <tr>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.members.show', $member->id) }}" class="view-btn">👁 View Profile</a>
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
        $('#members-table').DataTable();
    });
</script>
@endsection
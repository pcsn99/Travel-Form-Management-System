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

    .table-responsive-custom {
        overflow-x: auto;
        position: relative;
    }

    .table-responsive-custom::after {
        position: absolute;
        bottom: 8px;
        right: 12px;
        font-size: 12px;
        color: #888;
    }

    .table thead th {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .table th, .table td {
        padding: 12px;
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .view-btn {
        background-color: #17224D;
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background-color 0.3s ease;
    }

    .view-btn:hover {
        background-color: #1f2f5f;
        text-decoration: none;
        color: white;
    }

    @media (max-width: 700px) {
        body {
            padding: 20px;
        }

        .card {
            padding: 20px;
        }

        .card-header {
            font-size: 16px;
            padding: 10px;
        }

        .view-btn {
            font-size: 12px;
            padding: 6px 10px;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: center;
        }
    }
</style>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-custom">
    <div class="card">
        <div class="card-header">Community Members</div>
        <div class="card-body">
            <div class="table-responsive-custom">
                <table id="members-table" class="table display nowrap" style="width:100%">
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
                                    <a href="{{ route('admin.members.show', $member->id) }}" class="view-btn">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function () {
        $('#members-table').DataTable({
            responsive: false,
            scrollX: true,
            order: [[2, 'desc']],
            columnDefs: [
                { targets: 0, width: '150px' },
                { targets: 1, width: '200px' },
                { targets: 2, width: '130px' },
                { targets: 3, width: '100px' }
            ]
        });
    });
</script>
@endsection

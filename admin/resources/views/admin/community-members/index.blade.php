@extends('layouts.app')

@section('content')
    <h2>👥 Community Members</h2>

    <table id="members-table" class="display" style="width:100%">
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
                        <a href="{{ route('admin.members.show', $member->id) }}">
                            <button>👁 View Profile</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

        <!-- Include jQuery + DataTables -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link  href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
        <script>
            $(document).ready(function () {
                $('#members-table').DataTable();
            });
        </script>
@endsection



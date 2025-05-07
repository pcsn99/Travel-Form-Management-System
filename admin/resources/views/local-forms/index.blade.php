@extends('layouts.app')

@section('title', 'Local Travel Forms')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .container-custom {
        max-width: 900px;
        margin: auto;
    }

    .header-banner {
        position: relative;
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        text-align: center;
        color: white;
    }

    .header-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.75);
        border-radius: 12px;
        z-index: 0;
    }

    .header-banner h2 {
        position: relative;
        z-index: 1;
        font-size: 28px;
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

    .view-btn {
        background-color: #6a4c93;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .view-btn:hover {
        background-color: #563d7c;
    }
</style>
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>Local Travel Forms</h2>
    </div>

    <div class="card">
        <div class="card-body">
            @if($forms->count())
                <table id="local-forms-table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Departure</th>
                            <th>Return</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr>
                                <td>{{ $form->request->user->name }}</td>
                                <td>{{ ucfirst($form->status) }}</td>
                                <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                                <td><a href="{{ route('local-forms.show', $form->id) }}" class="view-btn">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center m-4">No local travel forms available.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#local-forms-table').DataTable();
    });
</script>
@endsection

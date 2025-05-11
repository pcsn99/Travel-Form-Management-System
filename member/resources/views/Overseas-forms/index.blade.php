@extends('layouts.app')

@section('title', 'All Overseas Travel Forms')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        background: 
            linear-gradient(to right, rgba(23, 34, 77, 0.85), rgba(23, 34, 77, 0.85)),
            url('/images/bg.jpeg') no-repeat center center;
        background-size: cover;
        padding: 40px 20px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
        color: white;
        border-bottom: 3px solid #17224D;
        margin-bottom: 40px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }

    .container-custom {
        max-width: 900px;
        margin: auto;
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

    .btn-primary {
        background-color: #17224D;
        border: none;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 6px;
        margin-right: 5px;
    }

    .btn-warning {
        background-color: #f39c12;
        border: none;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 6px;
        margin-right: 5px;
    }
</style>
@endsection

@section('content')
@php use Carbon\Carbon; @endphp
<div class="container-custom">
    <div class="dashboard-header">All Overseas Travel Forms</div>
    <div class="card">
        <div class="card-body">
            @if($forms->count())
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Travel Dates</th>
                            <th>Status</th>
                            <th>Form Comment</th>
                            <th>Request Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forms as $form)
                            <tr>
                                <td>{{ Carbon::parse($form->request->intended_departure_date)->format('F j, Y') }} to {{ Carbon::parse($form->request->intended_return_date)->format('F j, Y') }}</td>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($form->status) }}</span></td>
                                <td>{{ $form->admin_comment ?: '-' }}</td>
                                <td>{{ $form->request->admin_comment ?: '-' }}</td>
                                <td>
                                    <a href="{{ route('member.Overseas-forms.show', $form->id) }}" class="btn btn-primary">View</a>

                                    @if(is_null($form->submitted_at))
                                        <a href="{{ route('member.Overseas-forms.edit', $form->id) }}" class="btn btn-primary">Fill Out</a>
                                    @elseif($form->status === 'pending' ||$form->status === 'submitted')
                                        <a href="{{ route('member.Overseas-forms.edit', $form->id) }}" class="btn btn-primary">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center">No overseas travel forms found.</p>
            @endif
        </div>
    </div>
</div>
@endsection

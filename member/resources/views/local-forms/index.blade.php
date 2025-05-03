@extends('layouts.app')

@section('title', 'All Local Travel Forms')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        background-color: #17224D;
        padding: 20px;
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
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
        padding: 12px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 6px;
        margin-top: 20px;
    }
</style>
@endsection

@section('content')

<div class="container-custom">
    <div class="dashboard-header">All Local Travel Forms</div>
    <div class="card">
        <div class="card-body">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Travel Dates</th>
                        <th>Event</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $form)
                        <tr>
                            <td>{{ $form->request->intended_departure_date }} to {{ $form->request->intended_return_date }}</td>
                            <td>{{ $form->request->event }}</td>
                            <td>{{ $form->request->purpose }}</td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($form->status) }}</span></td>
                            <td>
                                <a href="{{ route('member.local-forms.show', $form->id) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No local travel forms found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- <a href="{{ route('dashboard') }}" class="btn btn-secondary">⬅ Back to Dashboard</a> --}}
</div>

@endsection
@extends('layouts.app')

@section('title', 'Member Dashboard')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
    }

    .dashboard-header {
        background-color: #17224D;
        padding: 15px;
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        color: white;
        margin-bottom: 20px;
    }

    .welcome-section {
        background-image: url('{{ asset('images/bg.jpeg') }}');
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        padding: 40px 20px;
        text-align: center;
        position: relative;
        color: white;
        margin-bottom: 30px;
    }

    .welcome-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 12px;
        z-index: 0;
    }

    .welcome-section h2,
    .welcome-section .btn {
        position: relative;
        z-index: 1;
    }

    .dashboard-container {
        width: 100%;
        padding: 0 15px;
    }

    .card {
        background: rgba(255, 255, 255, 0.97);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .table-responsive-custom {
        overflow-x: auto;
    }

    .table thead {
        background-color: #17224D;
        color: white;
    }

    .table th,
    .table td {
        padding: 12px;
        white-space: nowrap;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        font-size: 13px;
        font-weight: bold;
        border-radius: 12px;
        text-transform: capitalize;
    }

    .badge-approved {
        background-color: #198754;
        color: white;
    }

    .badge-pending {
        background-color: #ffc107;
        color: black;
    }

    .badge-submitted {
        background-color: #0d6efd;
        color: white;
    }

    .badge-declined {
        background-color: #dc3545;
        color: white;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            font-size: 20px;
            padding: 10px;
        }

        .card {
            padding: 15px;
        }

        .table th,
        .table td {
            padding: 8px;
            font-size: 13px;
        }

        .welcome-section h2 {
            font-size: 20px;
        }

        .welcome-section .btn {
            font-size: 15px;
        }
    }
</style>
@endsection

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@php use Illuminate\Support\Str; @endphp

<div class="dashboard-header">Dashboard</div>

<div class="welcome-section">
    <h2>Welcome, {{ Auth::user()->name }}!</h2>
    <a href="{{ route('travel-requests.create') }}" class="btn btn-light mt-3">
        <i class="bi bi-plus-circle"></i> Create Travel Request
    </a>
</div>

<div class="dashboard-container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- PENDING REQUESTS --}}
    @if($pendingRequests->count())
    <div class="card">
        <h3>Pending Travel Requests</h3>
        <div class="table-responsive-custom">
            <table id="datatable" class="table table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Departure</th>
                        <th>Return</th>
                        @foreach($questions as $q)
                            <th>{{ Str::limit($q->question, 20) }}</th>
                        @endforeach
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingRequests as $req)
                        <tr>
                            <td>{{ ucfirst($req->type) }}</td>
                            <td>{{ \Carbon\Carbon::parse($req->intended_departure_date)->format('F d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($req->intended_return_date)->format('F d, Y') }}</td>
                            @foreach($questions as $q)
                                @php $answer = $req->answers->firstWhere('question_id', $q->id); @endphp
                                <td>{{ Str::limit($answer?->answer ?? '-', 30) }}</td>
                            @endforeach
                            <td>
                                <a href="{{ route('travel-requests.edit', $req->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form method="POST" action="{{ route('travel-requests.destroy', $req->id) }}" class="d-inline" onsubmit="return confirm('Delete this travel request?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- PENDING FORMS --}}
    @if($pendingForms->count())
    <div class="card">
        <h3>Pending Travel Forms</h3>
        <div class="table-responsive-custom">
            <table id="datatable1" class="table table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Departure</th>
                        <th>Return</th>
                        @foreach($questions as $q)
                            <th>{{ Str::limit($q->question, 20) }}</th>
                        @endforeach
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingForms as $form)
                        <tr>
                            <td>{{ ucfirst($form->request->type) }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                            @foreach($questions as $q)
                                @php $answer = $form->request->answers->firstWhere('question_id', $q->id); @endphp
                                <td>{{ Str::limit($answer?->answer ?? '-', 30) }}</td>
                            @endforeach
                            <td>
                                <a href="{{ $form->request->type === 'local' 
                                    ? route('member.local-forms.edit', $form->id) 
                                    : route('member.Overseas-forms.edit', $form->id) }}" 
                                    class="btn btn-warning btn-sm">Fill Out</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- SUBMITTED / APPROVED --}}
    @if($submittedForms->count())
    <div class="card">
        <h3>Submitted or Approved Travel Forms</h3>
        <div class="table-responsive-custom">
            <table id="datatable2" class="table table-bordered display nowrap w-100">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Departure</th>
                        <th>Return</th>
                        <th>Status</th>
                        @foreach($questions as $q)
                            <th>{{ Str::limit($q->question, 20) }}</th>
                        @endforeach
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submittedForms as $form)
                        <tr>
                            <td>{{ ucfirst($form->request->type) }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                            @php
                                $badgeClass = match(strtolower($form->status)) {
                                    'submitted' => 'badge-submitted',
                                    'approved' => 'badge-approved',
                                    'declined' => 'badge-declined',
                                    default => 'badge-pending'
                                };
                            @endphp
                            <td><span class="status-badge {{ $badgeClass }}">{{ ucfirst($form->status) }}</span></td>
                            @foreach($questions as $q)
                                @php $answer = $form->request->answers->firstWhere('question_id', $q->id); @endphp
                                <td>{{ Str::limit($answer?->answer ?? '-', 30) }}</td>
                            @endforeach
                            <td>
                                <a href="{{ $form->request->type === 'local' 
                                    ? route('member.local-forms.show', $form->id) 
                                    : route('member.Overseas-forms.show', $form->id) }}" 
                                    class="btn btn-info btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#datatable').DataTable({ scrollX: true });
        $('#datatable1').DataTable({ scrollX: true });
        $('#datatable2').DataTable({ scrollX: true });
    });
</script>
@endsection

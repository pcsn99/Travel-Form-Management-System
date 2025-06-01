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
        max-width: 1000px;
        margin: auto;
        padding-top: 20px;
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
        background-color: rgba(0,0,0,0.5);
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
        background-color: #0d6efd;
        color: white;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: background-color 0.3s ease;
    }

    .view-btn:hover {
        background-color: #0b5ed7;
        text-decoration: none;
        color: white;
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

    .badge-declined {
        background-color: #dc3545;
        color: white;
    }

    .badge-default {
        background-color: #6c757d;
        color: white;
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

    .table-responsive-custom table {
        white-space: nowrap;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background-color: #17224D;
        color: white;
        z-index: 2;
    }

    @media (max-width: 700px) {
        .header-banner h2 {
            font-size: 20px;
        }

        .view-btn {
            font-size: 12px;
            padding: 6px 10px;
        }

        .card {
            padding: 20px;
        }

        .card-header {
            font-size: 16px;
            padding: 10px;
        }

        body {
            padding: 20px;
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
    <div class="header-banner">
        <h2>Local Travel Forms</h2>
    </div>

    <div class="card">
        <div class="card-header">Submitted Local Travel Forms</div>
        <div class="card-body">
            @if($forms->count())
                @php
                    $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
                @endphp

                <div class="table-responsive-custom">
                    <table id="local-forms-table" class="display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Departure</th>
                                <th>Return</th>
                                <th>Submitted</th>
                                @foreach($questions as $q)
                                    <th>{{ \Illuminate\Support\Str::limit($q->question, 20) }}</th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forms as $form)
                                <tr>
                                    <td>{{ $form->request->id }}</td>
                                    <td>{{ $form->request->user->name }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($form->status);
                                            $badgeClass = match($status) {
                                                'approved' => 'badge-approved',
                                                'pending' => 'badge-pending',
                                                'declined' => 'badge-declined',
                                                default => 'badge-default'
                                            };
                                        @endphp
                                        <span class="status-badge {{ $badgeClass }}">{{ ucfirst($form->status) }}</span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                                    <td>{{ $form->created_at->format('F d, Y') }}</td>
                                    @foreach($questions as $q)
                                        @php
                                            $answer = $form->request->answers->firstWhere('question_id', $q->id);
                                        @endphp
                                        <td>{{ \Illuminate\Support\Str::limit($answer ? $answer->answer : '-', 30) }}</td>
                                    @endforeach
                                    <td>
                                        <a href="{{ route('local-forms.show', $form->id) }}" class="view-btn">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function () {
        $('#local-forms-table').DataTable({
            responsive: false,
            scrollX: true,
            order: [[5, 'desc']],
            columnDefs: [
                { targets: 0, width: '80px' },
                { targets: 1, width: '150px' },
                { targets: 2, width: '120px' },
                { targets: 3, width: '130px' },
                { targets: 4, width: '130px' },
                { targets: 5, width: '130px' },
                { targets: -1, width: '90px' }
            ]
        });
    });
</script>
@endsection

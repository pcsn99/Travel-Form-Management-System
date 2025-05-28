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
        max-width: 100%;
        margin: auto;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 50px;
    }

    .table-responsive-custom {
        overflow-x: auto;
        width: 100%;
    }

    .table-responsive-custom table {
        min-width: 100%;
        white-space: nowrap;
    }

    .table {
        min-width: 1400px;
        width: 100%;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.95);
        white-space: nowrap;
    }

    .table thead {
        background-color: #17224D;
        color: white;
        font-size: 16px;
    }

    .table th, .table td {
        padding: 12px;
        border: 1px solid #17224D;
        vertical-align: middle;
    }

    .btn-primary {
        background-color: #17224D;
        border: none;
        padding: 8px 12px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 6px;
        margin: 2px 0;
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
</style>
@endsection

@section('content')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">



@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
@endphp

<div class="container-custom">
    <div class="dashboard-header">All Local Travel Forms</div>
    <div class="card">
        <div class="table-responsive-custom">
            <table id="datatable" class="table mt-3">
                <thead>
                    <tr>
                        <th>Travel Dates</th>
                        <th>Status</th>
                        <th>Form Comment</th>
                        <th>Request Comment</th>
                        @foreach($questions as $q)
                            <th>{{ Str::limit($q->question, 20) }}</th>
                        @endforeach
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $form)
                        <tr>
                            <td>{{ Carbon::parse($form->request->intended_departure_date)->format('F j, Y') }} to {{ Carbon::parse($form->request->intended_return_date)->format('F j, Y') }}</td>

                            @php
                                $badgeClass = match(strtolower($form->status)) {
                                    'approved' => 'badge-approved',
                                    'pending' => 'badge-pending',
                                    'submitted' => 'badge-submitted',
                                    'declined' => 'badge-declined',
                                    default => 'badge-pending'
                                };
                            @endphp
                            <td><span class="status-badge {{ $badgeClass }}">{{ ucfirst($form->status) }}</span></td>

                            <td>
                                <span title="{{ $form->admin_comment }}">{{ \Illuminate\Support\Str::limit($form->admin_comment ?: '-', 10) }}</span>
                            </td>
                            <td>
                                <span title="{{ $form->request->admin_comment }}">{{ \Illuminate\Support\Str::limit($form->request->admin_comment ?: '-', 10) }}</span>
                            </td>

                            @foreach($questions as $q)
                                @php
                                    $answer = $form->request->answers->firstWhere('question_id', $q->id);
                                @endphp
                                <td>
                                    <span title="{{ $answer?->answer }}">{{ Str::limit($answer?->answer ?? '-', 10) }}</span>
                                </td>
                            @endforeach

                            <td>
                                <a href="{{ route('member.local-forms.show', $form->id) }}" class="btn btn-primary">View</a>
                                @if(is_null($form->submitted_at))
                                    <a href="{{ route('member.local-forms.edit', $form->id) }}" class="btn btn-primary">Fill Out</a>
                                @elseif(in_array($form->status, ['pending', 'submitted', 'declined']))
                                    <a style="background-color: #e2b742" href="{{ route('member.local-forms.edit', $form->id) }}" class="btn btn-primary">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 5 + $questions->count() }}" class="text-center">No local travel forms found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#datatable').DataTable();
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Travel Requests')

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

    .header-banner h2,
    .header-banner .create-btn {
        position: relative;
        z-index: 1;
    }

    .create-btn {
        background-color: #2d6a4f;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
        text-decoration: none;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .create-btn:hover {
        background-color: #22543d;
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

        .create-btn {
            display: block;
            width: 100%;
            margin-top: 15px;
        }

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
@endsection

@section('content')
<div class="container-custom">


    @if(session('success'))
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="bi bi-check-circle-fill me-2"></i>Success
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {!! session('success') !!}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
    @endif

    @if(session('error'))
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">
                        <i class="bi bi-x-circle-fill me-2"></i>Error
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {!! session('error') !!}
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
        });
    </script>
    @endif


    <div class="header-banner" style="margin-top: 30px;">
        <h2>Manage Travel Requests</h2>
        <a href="{{ route('admin-travel-requests.search') }}" class="create-btn">+ Create Travel Request for Member</a>
    </div>

    <div class="card">
        <div class="card-header">Travel Requests ({{ ucfirst($status) }})</div>
        <div class="card-body">
            <div class="btn-group mb-3 w-100 justify-content-center" role="group">
                <a href="?status=pending" class="btn {{ $status == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">Pending</a>
                <a href="?status=approved" class="btn {{ $status == 'approved' ? 'btn-success' : 'btn-outline-success' }}">Approved</a>
                <a href="?status=declined" class="btn {{ $status == 'declined' ? 'btn-danger' : 'btn-outline-danger' }}">Declined</a>
            </div>


            @if($requests->count())
                <div class="table-responsive-custom" style="overflow-x: auto;">
                    <table id="requests-table" class="display nowrap" style="width:100%">
                
                        @php
                            $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
                        @endphp
                        
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Submitted</th>
                                <th>Departure</th>
                                <th>Return</th>
                                
                                @foreach($questions as $q)
                                    <th>{{ \Illuminate\Support\Str::limit($q->question, 20) }}</th>
                                @endforeach
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                <tr>
                                    <td>{{ $req->user->name }}</td>
                                    <td>{{ ucfirst($req->type) }}</td>
                                    <td>{{ $req->created_at->format('F d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->intended_departure_date)->format('F d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($req->intended_return_date)->format('F d, Y') }}</td>
                                    
                                    @foreach($questions as $q)
                                        @php
                                            $answer = $req->answers->firstWhere('question_id', $q->id);
                                        @endphp
                                        <td>{{ \Illuminate\Support\Str::limit($answer ? $answer->answer : '-', 30) }}</td>
                                    @endforeach
                                    <td>
                                        <a href="{{ route('travel-requests.show', $req->id) }}" class="view-btn">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <p class="text-center m-4">No travel requests found.</p>
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
        $('#requests-table').DataTable({
            responsive: false,
            scrollX: true,
            order: [[2, 'desc']], // order by 'Submitted'
            columnDefs: [
                { targets: 0, width: '120px' },
                { targets: 1, width: '90px' },
                { targets: 2, width: '130px' },
                { targets: 3, width: '130px' },
                { targets: 4, width: '130px' },
                { targets: -1, width: '80px' }
            ]
        });
    });
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
@endsection


@extends('layouts.app')

@section('title', 'Overseas Travel Forms')

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

    .badge-rejected {
        background-color: #dc3545;
        color: white;
    }

    .badge-default {
        background-color: #6c757d;
        color: white;
    }
    
    .container-custom {
        max-width: 100%;
        padding: 20px 40px;
        margin: auto;
    }

    .table-responsive-custom {
        width: 100%;
        overflow-x: auto;
        margin-top: 20px;
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




    <div class="header-banner">
        <h2>Overseas Travel Forms</h2>
    </div>

    <div class="card">
        <div class="card-body">
            @if($forms->count())
                <div class="table-responsive-custom">
                    <table id="overseas-forms-table" class="display" style="width:100%">
                        @php
                            
                            $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
                        @endphp
                        
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
                                                'rejected' => 'badge-rejected',
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
                                    <td><a href="{{ route('Overseas-forms.show', $form->id) }}" class="view-btn">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center m-4">No overseas travel forms available.</p>
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
        $('#overseas-forms-table').DataTable();
    });
</script>
@endsection

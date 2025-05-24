@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: rgba(23, 34, 77, 0.85);
        background-blend-mode: overlay;
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

    .card-header {
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: rgba(23, 34, 77, 0.85);
        background-blend-mode: overlay;
        color: white;
        font-size: 18px;
        font-weight: bold;
        border-radius: 6px 6px 0 0;
        padding: 15px;
        text-align: center;
    }

    .table-responsive-custom {
        width: 100%;
        overflow-x: auto;
        margin-top: 10px;
    }



    .table {
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

    #travel-calendar {
        max-width: 1000px;
        margin: auto;
        border: 1px solid #17224D;
        padding: 15px;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .fc-event-title {
        display: none;
    }
</style>
@endsection

@section('content')
@php
    use Illuminate\Support\Str;
    $questions = \App\Models\TravelRequestQuestion::where('status', 'active')->get();
@endphp

<div class="container-custom">
    <div class="dashboard-header">Dashboard</div>

    <div class="card">
        <div class="card-body">
            <div id="travel-calendar"></div>
        </div>
    </div>

    {{-- PENDING REQUESTS --}}
    <div class="card">
        <div class="card-header">Pending Travel Requests</div>

        <!-- Legend -->
        <div class="mb-3">
            <span class="badge" style="background-color: #fff3cd; border: 1px solid #ffeeba; color: #856404;">
                Yellow highlight = urgent or last-minute
            </span>
        </div>

        <div class="card-body table-responsive-custom">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Departure</th>
                        <th>Return</th>
                        <th>Submission Date</th>
                        @foreach($questions as $q)
                            <th>{{ Str::limit($q->question, 20) }}</th>
                        @endforeach
                        
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pendingRequests as $req)
                        @php
                            $submission = \Carbon\Carbon::parse($req->created_at);
                            $departure = \Carbon\Carbon::parse($req->intended_departure_date);
                            $return = \Carbon\Carbon::parse($req->intended_return_date);

                            $highlight = false;
                            if ($req->type === 'local' && $submission->diffInDays($departure, false) <= 7) {
                                $highlight = true;
                            }
                            if ($req->type === 'overseas' && $submission->diffInMonths($departure, false) < 2) {
                                $highlight = true;
                            }
                        @endphp

                        <tr>
                            <td>{{ $req->user->name }}</td>
                            <td>{{ ucfirst($req->type) }}</td>

                            <td @if($highlight) style="background-color: #fff3cd;" @endif>
                                {{ $departure->format('F d, Y') }}
                            </td>

                            <td @if($highlight) style="background-color: #fff3cd;" @endif>
                                {{ $return->format('F d, Y') }}
                            </td>

                            <td @if($highlight) style="background-color: #fff3cd;" @endif>
                                {{ $submission->format('F d, Y') }}
                            </td>

                            @foreach($questions as $q)
                                @php
                                    $answer = $req->answers->firstWhere('question_id', $q->id);
                                @endphp
                                <td>{{ Str::limit($answer?->answer ?? '-', 30) }}</td>
                            @endforeach

                            <td>
                                <a href="{{ route('travel-requests.show', $req->id) }}" class="btn btn-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 6 + $questions->count() }}">No pending travel requests.</td></tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>

    {{-- LOCAL FORMS --}}
    <div class="card">
        <div class="card-header">Upcoming Local Travel Forms (Next 3 Weeks)</div>
        <div class="card-body table-responsive-custom">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
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
                    @forelse($pendingLocalForms as $form)
                        <tr>
                            <td>{{ $form->request->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                            @php
                                $status = strtolower($form->status);
                                $badgeClass = match($status) {
                                    'approved' => 'badge-approved',
                                    'pending' => 'badge-pending',
                                    'rejected' => 'badge-rejected',
                                    default => 'badge-default'
                                };
                            @endphp
                            <td><span class="status-badge {{ $badgeClass }}">{{ ucfirst($form->status) }}</span></td>
                            @foreach($questions as $q)
                                @php
                                    $answer = $form->request->answers->firstWhere('question_id', $q->id);
                                @endphp
                                <td>{{ Str::limit($answer?->answer ?? '-', 30) }}</td>
                            @endforeach
                            <td><a href="{{ route('local-forms.show', $form->id) }}" class="btn btn-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 5 + $questions->count() }}">No upcoming local forms.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- OVERSEAS FORMS --}}
    <div class="card">
        <div class="card-header">Upcoming Overseas Travel Forms (Next 3 Months)</div>
        <div class="card-body table-responsive-custom">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
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
                    @forelse($pendingOverseasForms as $form)
                        <tr>
                            <td>{{ $form->request->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                            @php
                                $status = strtolower($form->status);
                                $badgeClass = match($status) {
                                    'approved' => 'badge-approved',
                                    'pending' => 'badge-pending',
                                    'rejected' => 'badge-rejected',
                                    default => 'badge-default'
                                };
                            @endphp
                            <td><span class="status-badge {{ $badgeClass }}">{{ ucfirst($form->status) }}</span></td>
                            @foreach($questions as $q)
                                @php
                                    $answer = $form->request->answers->firstWhere('question_id', $q->id);
                                @endphp
                                <td>{{ Str::limit($answer?->answer ?? '-', 30) }}</td>
                            @endforeach
                            <td><a href="{{ route('Overseas-forms.show', $form->id) }}" class="btn btn-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 5 + $questions->count() }}">No upcoming overseas forms.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CALENDAR MODAL --}}
    <div class="modal fade" id="travelModal" tabindex="-1" aria-labelledby="travelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="travelModalLabel">Travel Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="travelModalBody">Loading...</div>
            </div>
        </div>
    </div>

    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendar = new FullCalendar.Calendar(document.getElementById('travel-calendar'), {
                initialView: 'dayGridMonth',
                height: 600,
                nowIndicator: true,
                selectable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth'
                },
                dateClick: function (info) {
                    const date = info.dateStr;
                    const modal = new bootstrap.Modal(document.getElementById('travelModal'));
                    const label = document.getElementById('travelModalLabel');
                    const body = document.getElementById('travelModalBody');

                    label.textContent = `Travel Status for ${date}`;
                    body.innerHTML = 'Loading...';

                    modal.show();

                    fetch(`/admin/travel-calendar/details/${date}`)
                        .then(res => res.ok ? res.json() : Promise.reject(res.status))
                        .then(data => {
                            let html = `<h5>Traveling Members (${data.traveling.length})</h5>`;
                            html += data.traveling.length ? '<ul>' + data.traveling.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';
                            html += `<h5 class="mt-3">Available Members (${data.available.length})</h5>`;
                            html += data.available.length ? '<ul>' + data.available.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';
                            body.innerHTML = html;
                        })
                        .catch(error => {
                            body.innerHTML = `<p class='text-danger'>Error loading data. (${error})</p>`;
                        });
                },
                events: @json($calendarEvents)
            });

            calendar.render();
        });
    </script>
</div>
@endsection

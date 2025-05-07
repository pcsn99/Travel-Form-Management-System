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
        max-width: 1000px;
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
<div class="container-custom">
    <div class="dashboard-header">Dashboard</div>

    <div class="card">
        <div class="card-body">
            <div id="travel-calendar"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Pending Travel Requests</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Departure</th>
                        <th>Return</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingRequests as $req)
                        <tr>
                            <td>{{ $req->user->name }}</td>
                            <td>{{ ucfirst($req->type) }}</td>
                            <td>{{ \Carbon\Carbon::parse($req->intended_departure_date)->format('F d, Y') }}</td>

                            <td>{{ \Carbon\Carbon::parse($req->intended_return_date)->format('F d, Y') }}</td>

                            <td><a href="{{ route('travel-requests.show', $req->id) }}" class="btn btn-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No pending travel requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Upcoming Local Travel Forms (Next 3 Weeks)</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Departure</th>
                        <th>Return</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLocalForms as $form)
                        <tr>
                            <td>{{ $form->request->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>

                            <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>

                            <td>{{ ucfirst($form->status) }}</td>
                            <td><a href="{{ route('local-forms.show', $form->id) }}" class="btn btn-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No upcoming local forms.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Upcoming Overseas Travel Forms (Next 3 Months)</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Departure</th>
                        <th>Return</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingOverseasForms as $form)
                        <tr>
                            <td>{{ $form->request->user->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                            <td>{{ ucfirst($form->status) }}</td>
                            <td><a href="{{ route('Overseas-forms.show', $form->id) }}" class="btn btn-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No upcoming overseas forms.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Travel Modal for FullCalendar Pop-up --}}
    <div class="modal fade" id="travelModal" tabindex="-1" aria-labelledby="travelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="travelModalLabel">Travel Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="travelModalBody">
                    Loading...
                </div>
            </div>
        </div>
    </div>




    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('travel-calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
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
                    const modalLabel = document.getElementById('travelModalLabel');
                    const modalBody = document.getElementById('travelModalBody');

                    modalLabel.textContent = `Travel Status for ${date}`;
                    modalBody.innerHTML = 'Loading...';
                    const modal = new bootstrap.Modal(document.getElementById('travelModal'));
                    modal.show();

                    fetch(`/admin/travel-calendar/details/${date}`)
                        .then(res => res.ok ? res.json() : Promise.reject(res.status))
                        .then(data => {
                            let html = `<h5>🧳 Traveling Members (${data.traveling.length})</h5>`;
                            html += data.traveling.length ? '<ul>' + data.traveling.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';

                            html += `<h5 class="mt-3">✅ Available Members (${data.available.length})</h5>`;
                            html += data.available.length ? '<ul>' + data.available.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';

                            modalBody.innerHTML = html;
                        })
                        .catch(error => {
                            modalBody.innerHTML = `<p class='text-danger'>Error loading data. (${error})</p>`;
                        });
                },
                events: @json($calendarEvents)
            });

            calendar.render();
        });
    </script>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h1>📊 Admin Dashboard</h1>
    <hr>


    {{-- CALENDAR --}}
    <h2 style="margin-top:40px;">Travel Calendar</h2>
    <div id="travel-calendar" style="max-width: 1000px; margin: auto; border: 1px solid #ccc; padding: 10px;"></div>

    {{-- PENDING REQUESTS --}}
    <h2>📝 Pending Travel Requests</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Departure</th>
            <th>Return</th>
            <th>Action</th>
        </tr>
        @forelse($pendingRequests as $req)
            <tr>
                <td>{{ $req->user->name }}</td>
                <td>{{ ucfirst($req->type) }}</td>
                <td>{{ $req->intended_departure_date }}</td>
                <td>{{ $req->intended_return_date }}</td>
                <td><a href="{{ route('travel-requests.show', $req->id) }}">🔍 View</a></td>
            </tr>
        @empty
            <tr><td colspan="5">No pending travel requests.</td></tr>
        @endforelse
    </table>

    {{-- UPCOMING LOCAL FORMS --}}
    <h2 style="margin-top:30px;">📄 Upcoming Local Travel Forms (Next 3 Weeks)</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Departure</th>
            <th>Return</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @forelse($pendingLocalForms as $form)
            <tr>
                <td>{{ $form->request->user->name }}</td>
                <td>{{ $form->request->intended_departure_date }}</td>
                <td>{{ $form->request->intended_return_date }}</td>
                <td>{{ $form->status }}</td> {{-- ✅ --}}
                <td><a href="{{ route('local-forms.show', $form->id) }}">🔍 View</a></td>
            </tr>
        @empty
            <tr><td colspan="5">No upcoming local forms.</td></tr>
        @endforelse
    </table>

    {{-- UPCOMING Overseas FORMS --}}
    <h2 style="margin-top:30px;">🌍 Upcoming Overseas Travel Forms (Next 3 Months)</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Departure</th>
            <th>Return</th>
            <th>Status</th> 
            <th>Action</th>
        </tr>
        @forelse($pendingOverseasForms as $form)
            <tr>
                <td>{{ $form->request->user->name }}</td>
                <td>{{ $form->request->intended_departure_date }}</td>
                <td>{{ $form->request->intended_return_date }}</td>
                <td>{{ $form->submitted_at ? 'Submitted' : 'Not Submitted' }}</td> {{-- ✅ --}}
                <td><a href="{{ route('Overseas-forms.show', $form->id) }}">🔍 View</a></td>
            </tr>
        @empty
            <tr><td colspan="5">No upcoming Overseas forms.</td></tr>
        @endforelse
    </table>





    {{-- MODAL --}}
    <div class="modal fade" id="travelModal" tabindex="-1" aria-labelledby="travelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="travelModalLabel">Travel Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="travelModalBody">Loading...</div>
            </div>
        </div>
    </div>

    {{-- FullCalendar & Bootstrap --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('travel-calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 600,
                nowIndicator: true,
                dateClick: function (info) {
                    const date = info.dateStr;
                    $('#travelModal').modal('show');
                    $('#travelModalLabel').text(`Travel Status for ${date}`);
                    $('#travelModalBody').html('Loading...');

                    fetch(`/admin/travel-calendar/details/${date}`)
                        .then(res => res.json())
                        .then(data => {
                            let html = `<h5>🧳 Traveling Members (${data.traveling.length})</h5>`;
                            html += data.traveling.length ? '<ul>' + data.traveling.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';

                            html += `<h5 class="mt-3">✅ Available Members (${data.available.length})</h5>`;
                            html += data.available.length ? '<ul>' + data.available.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';

                            $('#travelModalBody').html(html);
                        });
                },

                events: @json($calendarEvents)
            });

            calendar.render();
        });
    </script>
@endsection

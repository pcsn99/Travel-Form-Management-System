@extends('layouts.app')

@section('content')
    <h2>Travel History: {{ $member->name }}</h2>

    <h3>Travel Requests</h3>
    @if($travelRequests->count())
        <table id="requests-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @foreach($travelRequests as $request)
                    <tr>
                        <td>{{ $request->created_at->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($request->type) }}</td>
                        <td>{{ ucfirst($request->status) }}</td>
                        <td><a href="{{ route('travel-requests.show', $request->id) }}">🔍</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><i>No travel requests.</i></p>
    @endif

    <h3 style="margin-top: 30px;">📋 Local Travel Forms</h3>
    @if($localForms->count())
        <table id="local-forms-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Departure</th>
                    <th>Return</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @foreach($localForms as $form)
                    <tr>
                        <td>{{ $form->created_at->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($form->status) }}</td>
                        <td>{{ $form->request->intended_departure_date ?? '—' }}</td>
                        <td>{{ $form->request->intended_return_date ?? '—' }}</td>
                        <td><a href="{{ route('local-forms.show', $form->id) }}">🔍</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><i>No local travel forms.</i></p>
    @endif

    <h3 style="margin-top: 30px;">🌐 Overseas Travel Forms</h3>
    @if($OverseasForms->count())
        <table id="Overseas-forms-table" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Departure</th>
                    <th>Return</th>
                    <th>View</th>
                </tr>
            </thead>
            <tbody>
                @foreach($OverseasForms as $form)
                    <tr>
                        <td>{{ $form->created_at->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($form->status) }}</td>
                        <td>{{ $form->request->intended_departure_date ?? '—' }}</td>
                        <td>{{ $form->request->intended_return_date ?? '—' }}</td>
                        <td><a href="{{ route('Overseas-forms.show', $form->id) }}">🔍</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p><i>No Overseas travel forms.</i></p>
    @endif

    <br>
    <a href="{{ route('admin.members.show', $member->id) }}">⬅ Back to Profile</a>



    <!-- DataTables CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            if ($('#requests-table').length) {
                $('#requests-table').DataTable({ order: [[0, 'desc']] });
            }
            if ($('#local-forms-table').length) {
                $('#local-forms-table').DataTable({ order: [[0, 'desc']] });
            }
            if ($('#Overseas-forms-table').length) {
                $('#Overseas-forms-table').DataTable({ order: [[0, 'desc']] });
            }
        });
    </script>
@endsection

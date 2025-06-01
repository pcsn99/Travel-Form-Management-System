<div class="card">
    <div class="dashboard-header">Pending Travel Requests</div>

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
                        <th>{{ \Illuminate\Support\Str::limit($q->question, 20) }}</th>
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
                        $highlight = $req->type === 'local'
                            ? $submission->diffInDays($departure, false) <= 7
                            : $submission->diffInMonths($departure, false) < 2;
                    @endphp
                    <tr>
                        <td>{{ $req->user->name }}</td>
                        <td>{{ ucfirst($req->type) }}</td>
                        <td @if($highlight) style="background-color: #fff3cd;" @endif>{{ $departure->format('F d, Y') }}</td>
                        <td @if($highlight) style="background-color: #fff3cd;" @endif>{{ $return->format('F d, Y') }}</td>
                        <td @if($highlight) style="background-color: #fff3cd;" @endif>{{ $submission->format('F d, Y') }}</td>
                        @foreach($questions as $q)
                            <td>{{ \Illuminate\Support\Str::limit($req->answers->firstWhere('question_id', $q->id)?->answer ?? '-', 30) }}</td>
                        @endforeach
                        <td><a href="{{ route('travel-requests.show', $req->id) }}" class="btn btn-primary">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="{{ 6 + $questions->count() }}">No pending travel requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

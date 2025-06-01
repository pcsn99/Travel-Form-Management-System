<div class="card">
    <div class="dashboard-header">Upcoming Local Travel Forms (Next 3 Weeks)</div>
    <div class="card-body table-responsive-custom">
        <table class="table">
            <thead style="background-color: #35599a; color: #fff; ">
                <tr>
                    <th>Name</th>
                    <th>Departure</th>
                    <th>Return</th>
                    <th>Status</th>
                    @foreach($questions as $q)
                        <th>{{ \Illuminate\Support\Str::limit($q->question, 20) }}</th>
                    @endforeach
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingLocalForms as $form)
                    @php
                        $status = strtolower($form->status);
                        $badgeClass = match($status) {
                            'approved' => 'badge-approved',
                            'pending' => 'badge-pending',
                            'declined' => 'badge-declined',
                            default => 'badge-default'
                        };
                    @endphp
                    <tr>
                        <td>{{ $form->request->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($form->request->intended_departure_date)->format('F d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($form->request->intended_return_date)->format('F d, Y') }}</td>
                        <td><span class="status-badge {{ $badgeClass }}">{{ ucfirst($form->status) }}</span></td>
                        @foreach($questions as $q)
                            <td>{{ \Illuminate\Support\Str::limit($form->request->answers->firstWhere('question_id', $q->id)?->answer ?? '-', 30) }}</td>
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

@extends('layouts.app')

@section('title', 'All Overseas Travel Forms')

@section('content')
    <h2>🌍 All Overseas Travel Forms</h2>

    @if($forms->count())
        <table class="table table-bordered mt-4">
            <thead class="table-light">
                <tr>
                    <th>Departure</th>
                    <th>Return</th>
                    <th>Status</th>
                    <th>Admin Comment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($forms as $form)
                    <tr>
                        <td>{{ $form->request->intended_departure_date }}</td>
                        <td>{{ $form->request->intended_return_date }}</td>
                        <td>{{ ucfirst($form->status) }}</td>
                        <td>{{ $form->admin_comment ?: '-' }}</td>
                        <td>
                            @if($form->status === 'submitted')
                                <a href="{{ route('member.Overseas-forms.edit', $form->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            @endif
                            <a href="{{ route('member.Overseas-forms.show', $form->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No Overseas travel forms found.</p>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>
@endsection

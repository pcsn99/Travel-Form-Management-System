@extends('layouts.app')

@section('title', 'All Local Travel Forms')

@section('content')
    <h2>📑 All Local Travel Forms</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Travel Dates</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($forms as $form)
                <tr>
                    <td>{{ $form->request->intended_departure_date }} to {{ $form->request->intended_return_date }}</td>
                    <td>{{ ucfirst($form->status) }}</td>
                    <td>
                        <a href="{{ route('member.local-forms.show', $form->id) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">No local travel forms found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection

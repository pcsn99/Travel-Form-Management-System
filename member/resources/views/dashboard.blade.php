@extends('layouts.app')

@section('content')


<h2>📋 My Dashboard</h2>


<a href="{{ route('account.show') }}" class="btn btn-outline-primary mb-3">
    👤 My Account
</a>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<a href="{{ route('travel-requests.create') }}">
    <button>➕ Create Travel Request</button>


</a>

<a href="{{ route('member.travel-requests.index') }}" class="btn btn-sm btn-outline-primary mb-2">🔍 View All Travel Requests</a>
<a href="{{ route('member.local-forms.all') }}" class="btn btn-sm btn-outline-primary mb-2">🔍 View All Local Forms</a>
<a href="{{ route('member.Overseas-forms.all') }}" class="btn btn-sm btn-outline-primary mb-2">🔍 View All Overseas Forms</a>


<h3 style="margin-top: 30px;">🕒 Pending Travel Requests</h3>

<ul>
    @forelse($pendingRequests as $req)
        <li>
            {{ ucfirst($req->type) }} - {{ $req->intended_departure_date }} to {{ $req->intended_return_date }}
            ({{ ucfirst($req->status) }})
            | <a href="{{ route('travel-requests.edit', $req->id) }}">✏️ Edit</a>

            <form method="POST" action="{{ route('travel-requests.destroy', $req->id) }}" style="display:inline;" onsubmit="return confirm('Delete this travel request?');">
                @csrf
                @method('DELETE')
                <button type="submit">🗑️ Delete</button>
            </form>
        </li>
    @empty
        <li>No pending travel requests yet.</li>
    @endforelse
</ul>

<hr>

<h3>📝 Pending Travel Forms</h3>
<ul>
    @forelse($pendingForms as $form)
        <li>
            {{ ucfirst($form->request->type) }} Travel Form -
            {{ $form->request->intended_departure_date }} to {{ $form->request->intended_return_date }}
            | <a href="{{ $form->request->type === 'local'
                ? route('member.local-forms.edit', $form->id)
                : route('member.Overseas-forms.edit', $form->id)
            }}">✏️ Fill Out</a>
        </li>
    @empty
        <li>No pending travel forms to complete.</li>
    @endforelse
</ul>

<h3 style="margin-top: 30px;">📨 Submitted Travel Forms</h3>
<ul>
    @forelse($submittedForms as $form)
        <li>
            {{ ucfirst($form->request->type) }} Travel Form - 
            <strong>{{ ucfirst($form->status) }}</strong>
            ({{ $form->request->intended_departure_date }} to {{ $form->request->intended_return_date }})

            @if($form->status === 'submitted')
                <a href="{{ $form->request->type === 'local' 
                    ? route('member.local-forms.edit', $form->id) 
                    : route('member.Overseas-forms.edit', $form->id) }}">
                    <button>✏️ Edit</button>
                </a>
            @endif

            @if($form->status === 'approved')
                <a href="{{ $form->request->type === 'local' 
                    ? route('member.local-forms.show', $form->id) 
                    : route('member.Overseas-forms.show', $form->id) }}">
                    <button>View</button>
                </a>
            @endif
        </li>
    @empty
        <li>No submitted or approved upcoming forms yet.</li>
    @endforelse
</ul>


@endsection

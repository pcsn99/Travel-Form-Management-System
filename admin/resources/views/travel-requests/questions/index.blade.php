@extends('layouts.app')

@section('content')
    <h2>Travel Request Questions</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('travel-request-questions.create') }}">➕ Add New Question</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Question</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $q)
                <tr>
                    <td>{{ $q->question }}</td>
                    <td>{{ ucfirst($q->status) }}</td>
                    <td>
                        <a href="{{ route('travel-request-questions.edit', $q->id) }}">✏️ Edit</a>

                        @if($q->status === 'active')
                        <form action="{{ route('travel-request-questions.destroy', $q->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Disable this question?')">❌ Disable</button>
                        </form>
                        @else
                        <span style="color: gray;">(Disabled)</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

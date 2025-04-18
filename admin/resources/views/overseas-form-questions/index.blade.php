@extends('layouts.app')

@section('content')
    <h2>🌐 Overseas Form Questions</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <a href="{{ route('Overseas-form-questions.create') }}">➕ Add New Question</a>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Question</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Reorder</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $q)
                @if($q->status === 'active')
                <tr>
                    <td>{{ $q->question }}</td>
                    <td>{{ ucfirst($q->status) }}</td>
                    <td>
                        <a href="{{ route('Overseas-form-questions.edit', $q->id) }}">✏️ Edit</a>

                        <form action="{{ route('Overseas-form-questions.destroy', $q->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Disable this question?')">❌ Disable</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('Overseas-form-questions.move', [$q->id, 'up']) }}" style="display:inline;">
                            @csrf
                            <button type="submit">⬆️</button>
                        </form>
                        <form method="POST" action="{{ route('Overseas-form-questions.move', [$q->id, 'down']) }}" style="display:inline;">
                            @csrf
                            <button type="submit">⬇️</button>
                        </form>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
@endsection

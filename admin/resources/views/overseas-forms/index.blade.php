@extends('layouts.app')

@section('content')
    <h2>Overseas Travel Forms</h2>

    <ul>
        @foreach($forms as $form)
            <li>
                {{ $form->request->user->name }} – 
                Status: {{ $form->status }} –
                <a href="{{ route('Overseas-forms.show', $form->id) }}">View</a>
            </li>
        @endforeach
    </ul>
@endsection

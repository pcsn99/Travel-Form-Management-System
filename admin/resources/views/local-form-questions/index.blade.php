@extends('layouts.app')

@section('title', 'Local Form Questions')

@section('styles')
<style>
   
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .container-custom {
        max-width: 800px;
        margin: auto;
        padding-top: 20px;
    }

   
    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
        margin-bottom: 50px;
    }

    .card-header {
        background-color: #17224D;
        color: white;
        font-size: 18px;
        font-weight: bold;
        border-radius: 6px 6px 0 0;
        padding: 15px;
        text-align: center;
    }

    
    .success-message {
        color: green;
        font-weight: bold;
        text-align: center;
        margin-bottom: 15px;
    }


    .add-btn {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        margin-bottom: 20px;
    }

    .add-btn:hover {
        background-color: #1f2f5f;
    }

    
    .table {
        width: 100%;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.95);
    }

    .table thead {
        background-color: #17224D;
        color: white;
        font-size: 16px;
    }

    .table th, .table td {
        padding: 12px;
        border: 1px solid #17224D;
        text-align: center;
    }

    .edit-btn, .disable-btn, .reorder-btn {
        background-color: #2980b9;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        border: none;
        cursor: pointer;
    }

    .edit-btn:hover {
        background-color: #21618C;
    }

    .disable-btn {
        background-color: red;
    }

    .disable-btn:hover {
        background-color: darkred;
    }

    .reorder-btn {
        background-color: #27AE60;
    }

    .reorder-btn:hover {
        background-color: #1D8348;
    }

    .disabled-text {
        color: gray;
    }
</style>
@endsection

@section('content')

<div class="container-custom">
    <!-- ✅ Local Form Questions -->
    <div class="card">
        <div class="card-header">Local Form Questions</div>
        <div class="card-body">
            
            @if(session('success'))
                <p class="success-message">{{ session('success') }}</p>
            @endif

           
            <a href="{{ route('local-form-questions.create') }}" class="add-btn">+ Add New Question</a>

            
            <table class="table">
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
                                <a href="{{ route('local-form-questions.edit', $q->id) }}" class="edit-btn">Edit</a>

                                <form action="{{ route('local-form-questions.destroy', $q->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="disable-btn" onclick="return confirm('Disable this question?')">Disable</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('local-form-questions.move', [$q->id, 'up']) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="reorder-btn">Move Up</button>
                                </form>
                                <form method="POST" action="{{ route('local-form-questions.move', [$q->id, 'down']) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="reorder-btn">Move Down</button>
                                </form>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@extends('layouts.app')

@section('title', 'Overseas Travel Forms')

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
    }

   
    .view-btn {
        background-color: #17224D;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
    }

    .view-btn:hover {
        background-color: #1f2f5f;
    }
</style>
@endsection

@section('content')

<div class="container-custom">
    <!-- ✅ Overseas Travel Forms -->
    <div class="card">
        <div class="card-header">Overseas Travel Forms</div>
        <div class="card-body">
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $form)
                        <tr>
                            <td>{{ $form->request->user->name }}</td>
                            <td>{{ ucfirst($form->status) }}</td>
                            <td><a href="{{ route('Overseas-forms.show', $form->id) }}" class="view-btn">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No overseas travel forms available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
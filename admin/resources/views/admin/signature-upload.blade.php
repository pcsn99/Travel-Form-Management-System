@extends('layouts.app')

@section('title', 'Upload Signature')

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

 
    .form-group {
        display: flex;
        align-items: center; 
        justify-content: space-between;
        width: 100%;
        gap: 15px; 
    }

    input[type="file"] {
        flex-grow: 1; 
        padding: 12px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
    }

    button {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        flex-shrink: 0; 
    }

    button:hover {
        background-color: #1f2f5f;
    }

    .back-btn {
        background-color: #6c757d;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        display: block;
        width: 100%;
        text-align: center;
        text-decoration: none;
        margin-top: 20px;
    }

    .back-btn:hover {
        background-color: #5a6268;
    }


    .signature-preview {
        height: 80px;
        border: 2px solid #17224D;
        display: block;
        margin: 15px auto;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')

<div class="container-custom">
   
    <div class="card">
        <div class="card-header">Upload Your Signature</div>
        <div class="card-body">
            
            @if(session('success'))
                <p style="color:green;">{{ session('success') }}</p>
            @endif

            @if($user->signature)
                <p><strong>Current Signature:</strong></p>
                <img src="{{ asset('storage/' . $user->signature) }}" alt="Signature" class="signature-preview">
            @endif

            <form method="POST" action="{{ route('admin.upload.signature') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label><strong>Select Signature Image (PNG, JPG):</strong></label>
                    <input type="file" name="signature" required>
                    <button type="submit">Upload / Replace </button>
                </div>
            </form>

            <a href="{{ route('admin.dashboard') }}" class="back-btn">⬅ Back to Dashboard</a>
        </div>
    </div>
</div>

@endsection
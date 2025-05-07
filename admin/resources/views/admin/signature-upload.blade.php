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
    }

    .header-banner {
        position: relative;
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        text-align: center;
        color: white;
    }

    .header-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.65);
        border-radius: 12px;
        z-index: 0;
    }

    .header-banner h2 {
        position: relative;
        z-index: 1;
        font-size: 28px;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 30px;
    }

    .form-group {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    input[type="file"] {
        flex-grow: 1;
        padding: 10px;
        border: 1px solid #17224D;
        border-radius: 6px;
        background: #f8f9fa;
    }

    button {
        background-color: #17224D;
        color: white;
        font-size: 16px;
        font-weight: bold;
        padding: 10px 20px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #1f2f5f;
    }

    .signature-preview {
        display: block;
        height: 80px;
        border: 2px solid #17224D;
        border-radius: 6px;
        margin: 15px auto;
    }

    .success-message {
        color: green;
        font-weight: bold;
        text-align: center;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="header-banner">
        <h2>Upload Signature</h2>
    </div>

    <div class="card">
        @if(session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        @if($user->signature)
            <p><strong>Current Signature:</strong></p>
            <img src="{{ asset('shared/' . $user->signature) }}" alt="Signature" class="signature-preview">
        @endif

        <form method="POST" action="{{ route('admin.upload.signature') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="file" name="signature" required>
                <button type="submit">Upload / Replace</button>
            </div>
        </form>
    </div>
</div>
@endsection

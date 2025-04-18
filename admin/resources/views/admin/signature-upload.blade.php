@extends('layouts.app')

@section('content')
    <h2>🖋️ Upload Your Signature</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if($user->signature)
        <p><strong>Current Signature:</strong></p>
        <img src="{{ asset('storage/' . $user->signature) }}" alt="Signature" style="height: 80px; border: 1px solid #ccc;">
    @endif

    <form method="POST" action="{{ route('admin.upload.signature') }}" enctype="multipart/form-data" style="margin-top: 20px;">
        @csrf

        <div>
            <label>Select Signature Image (PNG, JPG):</label><br>
            <input type="file" name="signature" required>
        </div>

        <br>
        <button type="submit">💾 Upload / Replace Signature</button>
    </form>

    <br>
    <a href="{{ route('admin.dashboard') }}">⬅️ Back to Dashboard</a>
@endsection

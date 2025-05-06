@extends('layouts.app')

@section('title', 'My Account')

@section('styles')
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }
    .dashboard-header {
        background-color: #17224D;
        padding: 20px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
        color: white;
        border-bottom: 3px solid #17224D;
        margin-bottom: 40px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }
    .container-custom {
        max-width: 800px;
        margin: auto;
    }
    .profile-account-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 35px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        margin-bottom: 60px;
    }

    .profile-account-section img {
        border: 4px solid #17224D;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        margin-bottom: 20px;
    }
    .btn-primary {
        background-color: #17224D;
        border: none;
        width: 100%;
        padding: 12px;
    }

    .btn-outline-primary {
        color: #17224D;
        padding: 10px;
    }
    .file-upload-group {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    input[type="file"] {
        padding: 14px;
        background: #f8f9fa;
        border: 1px solid #17224D;
        border-radius: 6px;
        width: 100%;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')

<div class="container-custom">
    
    <div class="dashboard-header">My Account</div>

    <div class="profile-account-section">
        <img src="{{ $photo ? asset('storage/'.$photo->photo_path) : asset('default-avatar.png') }}" alt="Profile Photo" width="120" class="rounded-circle">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>

        <form action="{{ route('profile-photo.update') }}" method="POST" enctype="multipart/form-data" class="file-upload-group">
            @csrf
            <input type="file" name="photo" accept="image/*" required>
            <button class="btn btn-sm btn-outline-primary upload-button">Upload</button>
        </form>

        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editAccountModal">Edit Account</button>
    </div>

    <!-- ✅ CV -->
    <div class="card">
        <div class="card-header">Curriculum Vitae (CV)</div>
        <div class="card-body file-upload-group">
            <form action="{{ route('user-file.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="cv">
                <input type="file" name="file" accept="application/pdf" required>
                <button class="btn btn-sm btn-primary upload-button">Upload CV</button>
            </form>
        </div>
    </div>

    <!-- ✅ Medical Records -->
    <div class="card">
        <div class="card-header">Medical Records</div>
        <div class="card-body file-upload-group">
            <form action="{{ route('user-file.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="medical">
                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                <button class="btn btn-sm btn-primary upload-button">Upload Medical Record</button>
            </form>
        </div>
    </div>

    <!-- ✅ Other Files -->
    <div class="card">
        <div class="card-header">Other Files</div>
        <div class="card-body file-upload-group">
            <form action="{{ route('user-file.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="other">
                <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
                <button class="btn btn-sm btn-primary upload-button">Upload Other File</button>
            </form>
        </div>
    </div>

    <!-- ✅ Edit Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('account.update') }}">
                @csrf @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
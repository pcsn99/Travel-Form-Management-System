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
        position: relative;
        padding: 30px 20px;
        font-size: 28px;
        font-weight: bold;
        text-align: center;
        color: white;
        margin-bottom: 40px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        text-shadow: 1px 1px 4px #000;
    }
    .dashboard-header::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('{{ asset('images/bg.jpeg') }}') center/cover no-repeat;
        opacity: 0.25;
        z-index: 0;
    }
    .dashboard-header span {
        position: relative;
        z-index: 1;
    }
    .container-custom {
        max-width: 800px;
        margin: auto;
    }
    .profile-account-section {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        gap: 40px;
        padding: 35px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        margin-bottom: 60px;
    }
    .profile-left, .profile-right {
        flex: 1;
    }
    .profile-left {
        display: flex;
        flex-direction: column;
        align-items: center;
        border-right: 3px solid #17224D;
        padding-right: 20px;
    }
    .profile-account-section img {
        border: 4px solid #17224D;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        margin-bottom: 20px;
    }
    .btn-primary,
    .btn-outline-primary,
    .upload-button,
    .btn-danger {
        width: auto;
        min-width: 120px;
        padding: 8px 16px;
    }
    .file-upload-group {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    input[type="file"] {
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #17224D;
        border-radius: 6px;
        width: auto;
        max-width: 100%;
    }
    .card-header {
        background-color: #17224D;
        color: white;
        font-weight: bold;
        border-bottom: none;
    }
    .file-entry {
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
</style>
@endsection

@section('content')
<div class="container-custom">
    <div class="dashboard-header"><span>My Account</span></div>

    <div class="profile-account-section">
        <div class="profile-left">
            <img src="{{ $photo ? asset('/shared/' . $user->profilePhoto->photo_path) : asset('default-avatar.png') }}" alt="Profile Photo" width="120" class="rounded-circle">
            <form action="{{ route('profile-photo.update') }}" method="POST" enctype="multipart/form-data" class="file-upload-group mt-3">
                @csrf
                <input type="file" name="photo" accept="image/*" required>
                <button class="btn btn-sm btn-outline-primary upload-button">Upload</button>
            </form>
        </div>
        <div class="profile-right">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editAccountModal">Edit Account</button>
        </div>
    </div>

    @foreach ([
        'cv' => ['label' => 'Curriculum Vitae (CV)', 'accept' => 'application/pdf'],
        'medical' => ['label' => 'Medical Records', 'accept' => '.pdf,.jpg,.jpeg,.png'],
        'other' => ['label' => 'Other Files', 'accept' => '.pdf,.jpg,.jpeg,.png']
    ] as $type => $info)
    <div class="card mb-3">
        <div class="card-header">{{ $info['label'] }}</div>
        <div class="card-body file-upload-group">
            @if(isset($files[$type]))
                @foreach ($files[$type] as $file)
                    <div class="d-flex justify-content-between align-items-center file-entry">
                        <a href="{{ asset('shared/' . $file->file_path) }}" target="_blank">{{ $file->original_name }}</a>
                        <form action="{{ route('user-file.destroy', $file->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                @endforeach
            @endif
            <form action="{{ route('user-file.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">
                <input type="file" name="file" accept="{{ $info['accept'] }}" required>
                <button class="btn btn-sm btn-primary upload-button mt-2">Upload {{ $info['label'] }}</button>
            </form>
        </div>
    </div>
    @endforeach

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

@extends('layouts.app')

@section('title', 'My Account')

@section('content')
<h2 class="mb-4">My Account</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{--  Profile Photo --}}
<div class="mb-4 text-center">
    
    <img src="{{ $photo ? asset('storage/'.$photo->photo_path) : asset('default-avatar.png') }}" alt="Profile Photo" width="120" class="rounded-circle mb-2">
    <form action="{{ route('profile-photo.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="photo" accept="image/*" required>
        <button class="btn btn-sm btn-outline-primary mt-2">Upload</button>
    </form>
</div>

{{--  Account Info --}}
<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editAccountModal">✏️ Edit Account</button>
    </div>
</div>

{{-- 📄 CV --}}
<div class="card mb-4">
    <div class="card-header">📄 Curriculum Vitae (CV)</div>
    <div class="card-body">
        @if(isset($files['cv']) && count($files['cv']))
            @php $cv = $files['cv'][0]; @endphp
            <a href="{{  route('user-file.download', $cv->id) }}" target="_blank">{{ $cv->original_name }}</a>
            <form action="{{ route('user-file.destroy', $cv->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">🗑 Delete</button>
            </form>
        @else
            <form action="{{ route('user-file.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="cv">
                <input type="file" name="file" accept="application/pdf" required>
                <button class="btn btn-sm btn-primary mt-2">Upload CV</button>
            </form>
        @endif
    </div>
</div>

{{-- 🏥 Medical Records --}}
<div class="card mb-4">
    <div class="card-header">🏥 Medical Records</div>
    <div class="card-body">
        @if(isset($files['medical']))
            <ul class="list-group mb-2">
                @foreach($files['medical'] as $file)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{  route('user-file.download', $file->id) }}" target="_blank">{{ $file->original_name }}</a>
                        <form action="{{ route('user-file.destroy', $file->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('user-file.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="medical">
            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
            <button class="btn btn-sm btn-primary">Upload Medical Record</button>
        </form>
    </div>
</div>

{{-- 📁 Other Files --}}
<div class="card mb-5">
    <div class="card-header">📁 Other Files</div>
    <div class="card-body">
        @if(isset($files['other']))
            <ul class="list-group mb-2">
                @foreach($files['other'] as $file)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{  route('user-file.download', $file->id) }}" target="_blank">{{ $file->original_name }}</a>
                        <form action="{{ route('user-file.destroy', $file->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('user-file.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="other">
            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required>
            <button class="btn btn-sm btn-primary">Upload Other File</button>
        </form>
    </div>
</div>

{{-- ✏️ Edit Modal --}}
<div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" action="{{ route('account.update') }}">
            @csrf
            @method('PATCH')
            <div class="modal-header">
                <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>
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
                <button type="submit" class="btn btn-primary">💾 Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

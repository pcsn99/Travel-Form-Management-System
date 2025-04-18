<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community Member Portal - @yield('title', 'Dashboard')</title>

    <!-- ✅ CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding-top: 30px;
        }

        .navbar-custom {
            background-color: #2c3e50;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-custom a {
            color: white;
            margin-right: 20px;
            text-decoration: none;
        }

        .container {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        textarea {
            resize: vertical;
        }

        .notif-badge {
            font-size: 0.75rem;
            padding: 3px 6px;
        }
    </style>
</head>
<body>

    <!-- ✅ Top Navigation -->
    <div class="navbar-custom">
        <!-- Left: Navigation -->
        <div>
            <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
        </div>

        <!-- Right: Notifications + Logout -->
        <div class="d-flex align-items-center">

            <!-- 🔔 Notification Bell -->
            <div class="dropdown me-3">
                @php
                    $notifications = Auth::user()->unreadNotifications;
                @endphp

                <button class="btn btn-light dropdown-toggle position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    🔔
                    @if($notifications->count())
                        <span class="badge bg-danger position-absolute top-0 start-100 translate-middle notif-badge">
                            {{ $notifications->count() }}
                        </span>
                    @endif
                </button>

                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="max-height: 300px; overflow-y: auto;">
                    @forelse($notifications as $notif)
                        <li class="dropdown-item d-flex justify-content-between align-items-center">
                            <span>{{ $notif->data['message'] ?? 'New notification' }}</span>
                            <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link p-0 ms-2">✔</button>
                            </form>
                        </li>
                    @empty
                        <li class="dropdown-item text-muted">No new notifications</li>
                    @endforelse
                </ul>
            </div>

            <!-- 🚪 Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-light">🚪 Logout</button>
            </form>
        </div>
    </div>

    <!-- Page Container -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>
</html>

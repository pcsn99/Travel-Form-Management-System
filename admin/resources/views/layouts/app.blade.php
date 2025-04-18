<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f6f8fa;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .topbar {
            background-color: #2c3e50;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .notif-bell {
            font-size: 24px;
            margin-right: 20px;
            cursor: pointer;
        }

        .logout-form button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .main {
            flex: 1;
            display: flex;
            height: 100%;
        }

        .sidebar {
            width: 220px;
            background-color: #34495e;
            color: white;
            padding: 20px 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar a button {
            width: 100%;
            background-color: #3498db;
            border: none;
            padding: 10px;
            color: white;
            font-size: 14px;
            border-radius: 4px;
            text-align: left;
        }

        .sidebar a button:hover {
            background-color: #2980b9;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: white;
            overflow-y: auto;
        }

        table {
            width: 100%;
            background: #fff;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

    <!-- 🔝 Topbar -->
    <div class="topbar d-flex justify-content-end align-items-center gap-3">
        <!-- 🔔 Notification Bell -->
        <div class="dropdown position-relative">
            @php
                $notifications = Auth::user()->unreadNotifications;
            @endphp

            <button class="btn btn-light dropdown-toggle position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                🔔
                @if($notifications->count())
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        {{ $notifications->count() }}
                    </span>
                @endif
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="max-height: 300px; overflow-y: auto;">
                @forelse($notifications as $notif)
                    <li class="dropdown-item d-flex justify-content-between align-items-center">
                        <a href="{{ $notif->data['url'] ?? '#' }}" class="text-decoration-none">
                            {{ $notif->data['message'] ?? 'New notification' }}
                        </a>
                        <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-link p-0">✔</button>
                        </form>
                    </li>
                @empty
                    <li class="dropdown-item text-muted">No new notifications</li>
                @endforelse
            </ul>
        </div>

        <!-- 🚪 Logout -->
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn btn-sm btn-danger">🚪 Logout</button>
        </form>
    </div>

    <!-- ⚙️ Main layout -->
    <div class="main">

        <!-- 📁 Sidebar -->
        <div class="sidebar">
            <a href="{{ route('admin.dashboard') }}"><button>🏠 Dashboard</button></a>
            <a href="{{ route('admin.upload.signature.form') }}"><button>🖋️ Signature</button></a>
            <a href="{{ route('travel-requests.index', ['status' => 'pending']) }}"><button>📋 Travel Requests</button></a>
            <a href="{{ route('local-forms.index') }}"><button>📄 Local Travel Forms</button></a>
            <a href="{{ route('Overseas-forms.index') }}"><button>🌍 Overseas Travel Forms</button></a>
            <a href="{{ route('admin.members.index') }}"><button>👥 Community Members</button></a>

            <!-- ⚙️ Settings collapsible section -->
            <button onclick="toggleSettings()" style="background-color: #1abc9c;">⚙️ Settings ▾</button>
            <div id="settingsMenu" style="display: none; margin-left: 10px;">
                <a href="{{ route('travel-request-questions.index') }}"><button style="background-color:#16a085;">📝 Travel Request Qs</button></a>
                <a href="{{ route('local-form-questions.index') }}"><button style="background-color:#16a085;">📄 Local Form Qs</button></a>
                <a href="{{ route('Overseas-form-questions.index') }}"><button style="background-color:#16a085;">🌐 Overseas Form Qs</button></a>
            </div>
        </div>

        <!-- 📄 Main Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function toggleSettings() {
            const menu = document.getElementById('settingsMenu');
            menu.style.display = (menu.style.display === 'none') ? 'block' : 'none';
        }
    </script>

</body>
</html>

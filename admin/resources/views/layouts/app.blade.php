<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding-top: 60px;
            display: flex;
        }
    
        .topbar {
            background-color: #17224D;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            height: 50px;
            z-index: 1000;
        }
    
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
    
        .notif-badge {
            position: absolute;
            top: -3px;
            right: -6px;
            background-color: red;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 6px;
            border-radius: 50%;
        }
    
        .logout-btn {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
    
        .logout-btn:hover {
            color: #f8f9fa;
        }
    
        .sidebar {
            width: 260px;
            background-color: #17224D;
            color: white;
            padding: 20px 10px;
            position: fixed;
            left: 0;
            top: 60px;
            height: calc(100vh - 60px);
            transition: width 0.3s ease;
            overflow: hidden;
        }
    
        .sidebar.collapsed {
            width: 60px;
        }
    
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px;
            text-decoration: none;
            color: white;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
    
        .sidebar a i {
            font-size: 20px;
            width: 30px;
            text-align: center;
            min-width: 30px;
            margin-left: -7px;
        }
    
        .sidebar a:hover {
            background-color: #1c2e5a;
        }
    
        .sidebar a.active {
            background-color: #1e3a8a;
            font-weight: bold;
            box-shadow: 0 0 5px #00bfff;
        }
    
        .nav-text {
            margin-left: 8px;
            opacity: 1;
            transition: opacity 0.3s ease;
            white-space: nowrap;
        }
    
        .hide-text {
            opacity: 0;
            pointer-events: none;
            width: 0;
            overflow: hidden;
        }
    
        .content {
            flex: 1;
            padding: 30px;
            background-color: white;
            margin-left: 260px;
            transition: margin-left 0.3s ease;
        }
    
        .content.full {
            margin-left: 80px;
        }
    
        .toggle-btn {
            cursor: pointer;
            font-size: 20px;
            background: none;
            border: none;
            color: white;
            padding: 5px 10px;
        }
    </style>
    

    @yield('styles')
</head>
<body>

    <div class="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

        <div class="topbar-right">
            <div class="dropdown me-3 position-relative">
                @php
                    $notifications = Auth::user()->unreadNotifications;
                @endphp

                <button class="btn btn-light dropdown-toggle position-relative" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @if($notifications->count())
                        <span class="notif-badge">{{ $notifications->count() }}</span>
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

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> <span class="nav-text">Dashboard</span>
        </a>
    
        <a href="{{ route('admin.upload.signature.form') }}" class="{{ request()->routeIs('admin.upload.signature.form') ? 'active' : '' }}">
            <i class="bi bi-upload"></i> <span class="nav-text">Uploads</span>
        </a>
    
        <a href="{{ route('travel-requests.index', ['status' => 'pending']) }}" class="{{ request()->is('travel-requests*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> <span class="nav-text">Travel Requests</span>
        </a>
    
        <a href="{{ route('local-forms.index') }}" class="{{ request()->is('local-forms*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> <span class="nav-text">Local Travel Forms</span>
        </a>
    
        <a href="{{ route('Overseas-forms.index') }}" class="{{ request()->is('Overseas-forms*') ? 'active' : '' }}">
            <i class="bi bi-globe"></i> <span class="nav-text">Overseas Travel Forms</span>
        </a>
    
        <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.index') ? 'active' : '' }}">
            <i class="bi bi-people"></i> <span class="nav-text">Community Members</span>
        </a>
    
        <a href="#" onclick="toggleSettings()">
            <i class="bi bi-gear"></i> <span class="nav-text">Settings ▾</span>
        </a>
    
        <div id="settingsMenu" style="display: none; margin-left: 10px;">
            <a href="{{ route('travel-request-questions.index') }}" class="{{ request()->is('travel-request-questions*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> <span class="nav-text">Travel Request Qs</span>
            </a>
            <a href="{{ route('local-form-questions.index') }}" class="{{ request()->is('local-form-questions*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> <span class="nav-text">Local Form Qs</span>
            </a>
            <a href="{{ route('Overseas-form-questions.index') }}" class="{{ request()->is('Overseas-form-questions*') ? 'active' : '' }}">
                <i class="bi bi-question-circle"></i> <span class="nav-text">Overseas Form Qs</span>
            </a>
        </div>
    </div>
    

    <div class="content" id="content">
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSettings() {
            const menu = document.getElementById('settingsMenu');
            menu.style.display = (menu.style.display === 'none') ? 'block' : 'none';
        }
    
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");
            const textSpans = sidebar.querySelectorAll(".nav-text");
    
            const isCollapsed = sidebar.classList.toggle("collapsed");
            content.classList.toggle("full");
    
            textSpans.forEach(span => {
                if (isCollapsed) {
                    span.classList.add("hide-text");
                } else {
                    span.classList.remove("hide-text");
                }
            });
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>

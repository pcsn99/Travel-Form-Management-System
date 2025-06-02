<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Member Portal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    @yield('styles')

    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
        }

        .topbar {
            background-color: #17224D;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -8px;
            background-color: red;
            color: white;
            font-size: 11px;
            padding: 4px 7px;
            border-radius: 50%;
        }

        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            background-color: #17224D;
            width: 250px;
            height: calc(100vh - 60px);
            overflow-y: auto;
            padding: 20px 10px;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            z-index: 1040;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            transition: background-color 0.2s;
        }

        .sidebar a i {
            width: 25px;
            margin-right: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #1e3a8a;
        }

        .content {
            padding: 80px 20px 30px;
        }

        @media (max-width: 768px) {
            .topbar {
                font-size: 18px;
            }

            .sidebar a {
                font-size: 15px;
                padding: 12px;
            }

            .content {
                padding: 100px 15px;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
                height: calc(100vh - 60px);
            }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <button class="btn btn-sm btn-light" onclick="toggleSidebar()">☰</button>

        <div class="dropdown me-3 position-relative">
            @php $notifications = Auth::user()->unreadNotifications; @endphp
            <button class="btn btn-light dropdown-toggle position-relative" id="notifDropdown" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                @if($notifications->count())
                    <span class="notif-badge">{{ $notifications->count() }}</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="max-height: 300px; overflow-y: auto;">
                @forelse($notifications as $notif)
                    <li class="dropdown-item d-flex justify-content-between align-items-center">
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           onclick="event.preventDefault(); markAsReadAndRedirect(this);"
                           data-url="{{ $notif->data['url'] ?? '#' }}"
                           class="mark-as-read"
                           style="text-decoration: none; color: inherit; flex: 1;">
                            {{ $notif->data['message'] ?? 'New notification' }}
                        </a>
                    </li>
                @empty
                    <li class="dropdown-item text-muted">No new notifications</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('account.show') }}" class="{{ request()->routeIs('account.show') ? 'active' : '' }}">
            <i class="bi bi-person"></i> My Account
        </a>
        <a href="{{ route('travel-requests.create') }}" class="{{ request()->routeIs('travel-requests.create') ? 'active' : '' }}">
            <i class="bi bi-journal-plus"></i> Create Travel Request
        </a>
        <a href="{{ route('member.local-forms.all') }}" class="{{ request()->is('member/local-forms*') ? 'active' : '' }}">
            <i class="bi bi-geo-alt"></i> Local Forms
        </a>
        <a href="{{ route('member.Overseas-forms.all') }}" class="{{ request()->is('member/Overseas-forms*') ? 'active' : '' }}">
            <i class="bi bi-globe"></i> Overseas Forms
        </a>

        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger w-100">Logout</button>
        </form>
    </div>

    <div class="content" id="content">
        <div class="container" style="padding-top: 10px">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function markAsReadAndRedirect(element) {
            const notifUrl = element.getAttribute('href');
            const redirectUrl = element.getAttribute('data-url');

            fetch(notifUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(() => {
                window.location.href = redirectUrl;
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    @yield('styles')

    <style>
         html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        overflow-x: hidden;
    }

    .topbar {
        background-color: #17224D;
        padding: 10px 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        height: 60px;
        width: 100%;
        z-index: 1000;
    }

    .topbar .btn {
        font-size: 16px;
        padding: 6px 12px;
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

    .admin-menu-popup {
        position: fixed;
        top: 60px;
        left: 10px;
        width: max-content;
        max-width: 90%;
        background-color: #1e2b50;
        color: #f8f9fa;
        z-index: 1100;
        padding: 15px 20px 60px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.35);
        border-radius: 0 0 8px 8px;
        border: 1px solid #2e3d68;
    }

    .admin-menu-popup a {
        color: white !important;
        text-decoration: none;
        padding: 10px;
        border-radius: 6px;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background-color: rgba(255, 255, 255, 0.03);
        transition: background-color 0.2s ease, border 0.2s ease;
        margin-bottom: 6px;
        font-size: 1rem;
    }

    .admin-menu-popup a:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .admin-menu-popup .btn-secondary {
        width: fit-content;
    }

    .logout-desktop {
        display: block;
    }

    .logout-mobile {
        display: none;
    }

    @media (max-width: 700px), (max-height: 500px) {
        .admin-menu-popup {
            left: 0;
            right: 0;
            top: 60px;
            width: 100vw;
            max-width: 100vw;
            height: calc(100vh - 60px);
            overflow-y: auto;
            border-radius: 0;
            padding: 20px 10px 100px;
            background-color: #1e2b50;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            box-sizing: border-box;
        }

        .admin-menu-popup a {
            width: 95%;
            font-size: 0.9rem;
            padding: 8px;
        }

        .admin-menu-popup .btn-secondary {
            font-size: 0.9rem;
            padding: 6px 12px;
        }

        .logout-desktop {
            display: none;
        }

        .logout-mobile {
            display: block;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 20px;
            z-index: 1110;
            background-color: #1a1a1a;
        }
        .topbar .dropdown-menu .dropdown-item {
            font-size: 13px;
            padding: 8px 10px;
            white-space: normal;
        }
    }
    </style>
</head>
<body>


<div class="topbar">
    <div class="admin-dropdown-menu">
        <button class="btn btn-light btn-sm" onclick="toggleAdminMenu()">☰</button>
    </div>

    <div class="topbar-right">
        <div class="dropdown me-3 position-relative">
            @php $notifications = Auth::user()->unreadNotifications; @endphp
            <button class="btn btn-light dropdown-toggle position-relative" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell"></i>
                @if($notifications->count())
                    <span class="notif-badge">{{ $notifications->count() }}</span>
                @endif
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown" style="max-height: 300px; overflow-y: auto;">
                @forelse($notifications as $notif)
                    <li class="dropdown-item d-flex justify-content-between align-items-center">
                        <a href="{{ $notif->data['url'] ?? '#' }}"
                           onclick="event.preventDefault(); markAsReadAndRedirect('{{ route('notifications.read', $notif->id) }}', this);"
                           class="mark-as-read d-block"
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
</div>

<!-- Floating admin nav menu -->
<div id="adminMenuContent" class="admin-menu-popup d-none">
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ route('admin.upload.signature.form') }}" class="{{ request()->routeIs('admin.upload.signature.form') ? 'fw-bold' : '' }}"><i class="bi bi-upload"></i> Uploads</a>
    <a href="{{ route('travel-requests.index', ['status' => 'pending']) }}" class="{{ request()->is('travel-requests*') ? 'fw-bold' : '' }}"><i class="bi bi-journal-text"></i> Travel Requests</a>
    <a href="{{ route('local-forms.index') }}" class="{{ request()->is('local-forms*') ? 'fw-bold' : '' }}"><i class="bi bi-geo-alt"></i> Local Forms</a>
    <a href="{{ route('Overseas-forms.index') }}" class="{{ request()->is('Overseas-forms*') ? 'fw-bold' : '' }}"><i class="bi bi-globe"></i> Overseas Forms</a>
    <a href="{{ route('admin.members.index') }}" class="{{ request()->routeIs('admin.members.index') ? 'fw-bold' : '' }}"><i class="bi bi-people"></i> Members</a>

    <button class="btn btn-sm btn-secondary mt-2" onclick="toggleSettingsMenu()">Settings ▾</button>
    <div id="settingsMenu" class="ms-3 d-none mt-1">
        <a href="{{ route('travel-request-questions.index') }}" class="d-block {{ request()->is('travel-request-questions*') ? 'fw-bold' : '' }}">• Travel Request Qs</a>
        <a href="{{ route('local-form-questions.index') }}" class="d-block {{ request()->is('local-form-questions*') ? 'fw-bold' : '' }}">• Local Form Qs</a>
        <a href="{{ route('Overseas-form-questions.index') }}" class="d-block {{ request()->is('Overseas-form-questions*') ? 'fw-bold' : '' }}">• Overseas Form Qs</a>
        <a href="{{ route('admin-accounts.index') }}" class="d-block {{ request()->routeIs('admin-accounts.index') ? 'fw-bold' : '' }}">• Admin Accounts</a>
    </div>

    
    <form action="{{ route('logout') }}" method="POST" class="mt-4 logout-desktop">
        @csrf
        <button type="submit" class="btn btn-danger w-100 fw-bold">Logout</button>
    </form>

    <div class="logout-mobile">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger w-100 fw-bold">Logout</button>
        </form>
    </div>
</div>



<script>
    function toggleAdminMenu() {
        const menu = document.getElementById('adminMenuContent');
        menu.classList.toggle('d-none');
    }

    function toggleSettingsMenu() {
        const submenu = document.getElementById('settingsMenu');
        submenu.classList.toggle('d-none');
    }

    function markAsReadAndRedirect(postUrl, anchor) {
        fetch(postUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.href = anchor.getAttribute('href');
        });
    }
    
    function markAsReadOnly(postUrl, btn) {
        fetch(postUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(() => {
            btn.closest('li').remove(); // optionally hide it
        });
    }

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('adminMenuContent');
        const toggleBtn = document.querySelector('.admin-dropdown-menu button');

        if (!menu.classList.contains('d-none') &&
            !menu.contains(e.target) &&
            !toggleBtn.contains(e.target)) {
            menu.classList.add('d-none');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <div class="content" id="content">
        <div class="container mt-4" style="padding-top: 40px">
            @yield('content') 
        </div>
    </div>


    @yield('scripts')
</body>
</html>
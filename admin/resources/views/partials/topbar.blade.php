<style>
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
        padding: 6px 10px;
        border-radius: 4px;
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

    @media (max-width: 700px) {
        .topbar {
            height: 70px !important;
            padding: 10px 15px;
        }

        .topbar .btn {
            padding: 10px 14px;
            font-size: 20px;
        }

        .topbar .bi-bell {
            font-size: 24px;
        }

        .admin-menu-popup {
            left: 0;
            right: 0;
            top: 60px;
            width: 100%;
            max-width: 100%;
            height: calc(100vh - 60px);
            overflow-y: auto;
            border-radius: 0;
            padding: 20px 20px 100px;
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
        }
    }
</style>

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
                        <a href="{{ route('notifications.read', $notif->id) }}"
                           onclick="event.preventDefault(); markAsReadAndRedirect(this);"
                           data-url="{{ $notif->data['url'] ?? '#' }}"
                           class="mark-as-read"
                           style="text-decoration: none; color: inherit; flex: 1;">
                            {{ $notif->data['message'] ?? 'New notification' }}
                        </a>
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

    function markAsReadAndRedirect(element) {
        const notifUrl = element.getAttribute('href');
        const redirectUrl = element.getAttribute('data-url');

        fetch(notifUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(() => {
            window.location.href = redirectUrl;
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

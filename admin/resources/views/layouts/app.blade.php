<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - @yield('title', 'Dashboard')</title>

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
            background-color: #17224d;
            padding: 10px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toggle-btn {
            cursor: pointer;
            font-size: 20px;
            background: none;
            border: none;
            color: white;
            padding: 5px 10px;
        }

        .notif-bell {
            width: 100%;
            height: auto;
            max-height: 24px;
        }

        .logout-form button {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
        }

        .main {
            flex: 1;
            display: flex;
            height: 100%;
        }

        .sidebar {
            width: 220px;
            background-color: #17224d;
            color: white;
            padding: 20px 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            position: fixed;
            height: 100vh;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar a img {
            width: 40px;
            height: 40px;
            transition: all 0.3s ease;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 8px;
            gap: 10px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed a {
            justify-content: center;
        }

        .sidebar.collapsed a span {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: opacity 0.3s ease, width 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #2980b9;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: white;
            margin-left: 240px;
            transition: margin-left 0.3s ease;
        }

        .content.full {
            margin-left: 80px;
        }
    </style>
</head>
<body>

    <div class="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown position-relative">
                <button class="btn btn-light dropdown-toggle position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('icons/Bell.png') }}" alt="Notifications" class="notif-bell">
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">3</span>
                </button>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>

    <div class="main">
        <!-- 📁 Sidebar -->
        <div class="sidebar" id="sidebar">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('icons/Dashboard.png') }}" alt="Dashboard">
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.upload.signature.form') }}">
                <img src="{{ asset('icons/Upload.png') }}" alt="Signature">
                <span>Uploads</span>
            </a>
            <a href="{{ route('travel-requests.index', ['status' => 'pending']) }}">
                <img src="{{ asset('icons/TR.png') }}" alt="Travel Requests">
                <span>Travel Requests</span>
            </a>
            <a href="{{ route('local-forms.index') }}">
                <img src="{{ asset('icons/LTF.png') }}" alt="Local Travel Forms">
                <span>Local Travel Forms</span>
            </a>
            <a href="{{ route('Overseas-forms.index') }}">
                <img src="{{ asset('icons/OTF.png') }}" alt="Overseas Travel Forms">
                <span>Overseas Travel Forms</span>
            </a>
            <a href="{{ route('admin.members.index') }}">
                <img src="{{ asset('icons/Profile.png') }}" alt="Community Members">
                <span>Community Members</span>
            </a>

            <a href="#" onclick="toggleSettings()">
                <img src="{{ asset('icons/Settings.png') }}" alt="Settings">
                <span>Settings ▾</span>
            </a>
            <div id="settingsMenu" style="display: none; margin-left: 10px;">
                <a href="{{ route('travel-request-questions.index') }}">
                    <img src="{{ asset('icons/Settings.png') }}" alt="Travel Request Qs">
                    <span>Travel Request Qs</span>
                </a>
                <a href="{{ route('local-form-questions.index') }}">
                    <img src="{{ asset('icons/Settings.png') }}" alt="Local Form Qs">
                    <span>Local Form Qs</span>
                </a>
                <a href="{{ route('Overseas-form-questions.index') }}">
                    <img src="{{ asset('icons/Settings.png') }}" alt="Overseas Form Qs">
                    <span>Overseas Form Qs</span>
                </a>
            </div>
        </div>

        <div class="content" id="content">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSettings() {
            const menu = document.getElementById('settingsMenu');
            menu.style.display = (menu.style.display === 'none') ? 'block' : 'none';
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');

            if (sidebar.classList.contains('collapsed')) {
                sidebar.classList.remove('collapsed');
                content.classList.remove('full');
            } else {
                sidebar.classList.add('collapsed');
                content.classList.add('full');
            }
        }
    </script>

</body>
</html>
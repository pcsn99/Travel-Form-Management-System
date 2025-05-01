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
            background-color: #f6f8fa; /* Default background color */
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

        .sidebar a img {
            width: 40px;
            height: 40px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 8px;
            gap: 10px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
        }

        .sidebar a:hover {
            background-color: #2980b9;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: white;
            overflow-y: auto;
        }

    </style>
</head>
<body>

    <!-- 🔝 Topbar -->
    <div class="topbar d-flex justify-content-end align-items-center gap-3">
        <!-- 🔔 Notification Bell -->
        <div class="dropdown position-relative">
            <button class="btn btn-light dropdown-toggle position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                🔔
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">3</span>
            </button>
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
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('icons/Dashboard.png') }}" alt="Dashboard"> Dashboard
            </a>
            <a href="{{ route('admin.upload.signature.form') }}">
                <img src="{{ asset('icons/Upload.png') }}" alt="Signature"> Uploads
            </a>
            <a href="{{ route('travel-requests.index', ['status' => 'pending']) }}">
                <img src="{{ asset('icons/TR.png') }}" alt="Travel Requests"> Travel Requests
            </a>
            <a href="{{ route('local-forms.index') }}">
                <img src="{{ asset('icons/LTF.png') }}" alt="Local Travel Forms"> Local Travel Forms
            </a>
            <a href="{{ route('Overseas-forms.index') }}">
                <img src="{{ asset('icons/OTF.png') }}" alt="Overseas Travel Forms"> Overseas Travel Forms
            </a>
            <a href="{{ route('admin.members.index') }}">
                <img src="{{ asset('icons/Profile.png') }}" alt="Community Members"> Community Members
            </a>

            <a href="#" onclick="toggleSettings()">
                <img src="{{ asset('icons/Settings.png') }}" alt="Settings"> Settings ▾
            </a>
            <div id="settingsMenu" style="display: none; margin-left: 10px;">
                <a href="{{ route('travel-request-questions.index') }}">
                    <img src="{{ asset('icons/Settings.png') }}" alt="Travel Request Qs"> Travel Request Qs
                </a>
                <a href="{{ route('local-form-questions.index') }}">
                    <img src="{{ asset('icons/Settings.png') }}" alt="Local Form Qs"> Local Form Qs
                </a>
                <a href="{{ route('Overseas-form-questions.index') }}">
                    <img src="{{ asset('icons/Settings.png') }}" alt="Overseas Form Qs"> Overseas Form Qs
                </a>
            </div>
        </div>

        <!-- 📄 Main Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSettings() {
            const menu = document.getElementById('settingsMenu');
            menu.style.display = (menu.style.display === 'none') ? 'block' : 'none';
        }
    </script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Community Member Portal')</title>

    <!-- ✅ CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ Bootstrap & Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            padding-top: 60px;
            display: flex;
        }

        /* ✅ Top Bar */
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

        /* ✅ Notification Bell - Smaller & Beside Logout */
        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px; /* ✅ Creates space between bell and logout */
        }

        .notif-container {
            display: flex;
            align-items: center;
            position: relative;
        }

        .notif-bell {
            width: 25px; /* ✅ Made the icon even smaller */
            height: 25px;
            cursor: pointer;
        }

        /* ✅ Notification Badge */
        .notif-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background-color: red;
            color: white;
            font-size: 9px; /* ✅ Reduced text size */
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 50%;
        }

        /* ✅ Logout Button */
        .logout-btn {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
        }

        /* ✅ Sidebar */
        .sidebar {
            width: 255px;
            background-color: #17224D;
            color: white;
            padding: 20px 10px;
            position: fixed;
            left: 0;
            top: 60px;
            height: calc(100vh - 60px);
            transition: width 0.3s ease;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        /* ✅ Sidebar Links */
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px;
            gap: 10px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed a {
            justify-content: center;
        }

        /* ✅ Keep Icons Visible */
        .sidebar a img {
            width: 35px;
            height: 35px;
            transition: all 0.3s ease;
        }

        /* ✅ Hide Text Labels When Collapsed */
        .sidebar.collapsed a span {
            display: none;
        }

        .sidebar a:hover {
            background-color: #2980b9;
        }

        /* ✅ Content */
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

        /* ✅ Toggle Button */
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

    <!-- ✅ Top Bar -->
    <div class="topbar">
        <!-- 📌 Toggle Sidebar Button -->
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

        <div class="topbar-right">
            <!-- 🔔 Notification Bell -->
            <div class="notif-container">
                <img src="{{ asset('icons/Bell2.png') }}" alt="Notifications" class="notif-bell">
                <span class="notif-badge">3</span>
            </div>

            <!-- 🚪 Logout Button -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <!-- ✅ Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('icons/Dashboard.png') }}" alt="Dashboard Icon">
            <span>Dashboard</span>
        </a>

        <a href="{{ route('account.show') }}">
            <img src="{{ asset('icons/Profile.png') }}" alt="Profile Icon">
            <span>My Account</span>
        </a>

        <a href="{{ route('travel-requests.create') }}">
            <img src="{{ asset('icons/TR.png') }}" alt="Travel Request Icon">
            <span>Create Travel Request</span>
        </a>

        <a href="{{ route('member.Overseas-forms.all') }}">
            <img src="{{ asset('icons/OTF.png') }}" alt="Overseas Forms Icon">
            <span>View All Overseas Forms</span>
        </a>

        <a href="#">
            <img src="{{ asset('icons/Settings.png') }}" alt="Settings Icon">
            <span>Settings</span>
        </a>
    </div>

    <!-- ✅ Main Content -->
    <div class="content" id="content">
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <!-- ✅ Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const content = document.getElementById("content");

            sidebar.classList.toggle("collapsed");
            content.classList.toggle("full");
        }
    </script>

    @yield('scripts')

</body>
</html>
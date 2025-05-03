<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>

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

        .notif-container {
            display: flex;
            align-items: center;
            position: relative;
        }

        .notif-bell {
            width: 25px;
            height: 25px;
            cursor: pointer;
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
            text-decoration: none;
            cursor: pointer;
            padding: 5px 10px;
            transition: color 0.3s ease;
        }

        .logout-btn:hover {
            color: #f8f9fa;
        }

        
        .sidebar {
            width: 240px;
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

        .sidebar a img {
            width: 35px;
            height: 35px;
            transition: all 0.3s ease;
        }

    
        .sidebar.collapsed a span {
            display: none;
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
            
            <div class="notif-container">
                <img src="{{ asset('icons/Bell2.png') }}" alt="Notifications" class="notif-bell" onerror="this.style.display='none'">
                <span class="notif-badge">3</span>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="sidebar" id="sidebar">
        <a href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('icons/Dashboard.png') }}" alt="Dashboard">
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.upload.signature.form') }}">
            <img src="{{ asset('icons/Upload.png') }}" alt="Uploads">
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

            sidebar.classList.toggle("collapsed");
            content.classList.toggle("full");
        }
    </script>

    @yield('scripts')

</body>
</html>
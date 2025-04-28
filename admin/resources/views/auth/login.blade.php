<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p style="color:red">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <p>TEST</p>

    <form method="POST" action="{{ route("admin.login") }}">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" required />
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required />
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>
</body>
</html>

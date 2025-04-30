<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: 
                linear-gradient(to right, rgba(255, 255, 255, 0.2), #17224D),
                url('/images/bg.jpeg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-form {
            width: 320px;
            color: white;
            text-align: left;
        }

        .login-form h2 {
            font-size: 36px;
            margin-bottom: 5px;
            text-align: center;
        }

        .login-form p {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-size: 15px;
            letter-spacing: 1px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .button-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 5px;
        }

        button {
            padding: 10px 30px;
            background-color: white;
            color: #17224D;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #f0f0f0;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        a {
            color: #ffffff;
            text-decoration: underline;
            font-size: 14px;
        }

        a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>

        @if ($errors->any())
            <div class="error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
            <input type="password" name="password" placeholder="Password" required>

            <div class="button-wrapper">
                <button type="submit">Login</button>
            </div>
        </form>

        <p>
            DO NOT HAVE AN ACCOUNT? <a href="{{ route('register') }}">REGISTER HERE</a>
        </p>
    </div>
</body>
</html>

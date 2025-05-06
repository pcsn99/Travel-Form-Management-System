<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Member Login</title>
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

        .login-container {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .login-form {
            color: #17224D;
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
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            background-color: #f9f9f9;
        }

        .button-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        button {
            padding: 10px 30px;
            background-color: #17224D;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #1f2f5f;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
        }

        a {
            color: #17224D;
            text-decoration: underline;
            font-size: 14px;
        }

        a:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('error'))
                <div class="error">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label for="email">EMAIL</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}">

                <label for="password">PASSWORD</label>
                <input type="password" name="password" id="password" required>

                <div class="button-wrapper">
                    <button type="submit">Login</button>
                </div>
            </form>

            <p>
                DON'T HAVE AN ACCOUNT? <a href="{{ route('register') }}">REGISTER HERE</a>
            </p>
        </div>
    </div>
</body>
</html>

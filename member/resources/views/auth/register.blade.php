<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
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

        .register-container {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
        }

        .register-form {
            color: #17224D;
            text-align: left;
        }

        .register-form h2 {
            font-size: 36px;
            margin-bottom: 5px;
            text-align: center;
        }

        .register-form p {
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
        }

        input[type="text"],
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
            margin-top: 5px;
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
    <div class="register-container">
        <div class="register-form">
            <h2>Register</h2>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="text" name="name" placeholder="Full Name" required value="{{ old('name') }}">
                <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>

                <div class="button-wrapper">
                    <button type="submit">Register</button>
                </div>
            </form>

            <p>
                Already have an account? <a href="{{ route('login') }}">Login</a>
            </p>
        </div>
    </div>
</body>
</html>

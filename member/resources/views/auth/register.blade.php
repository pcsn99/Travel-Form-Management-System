<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>

    <!-- Bootstrap (optional for modal use or future scaling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 420px;
        }

        .register-form {
            color: #17224D;
        }

        .register-form h2 {
            font-size: 32px;
            margin-bottom: 10px;
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
            padding: 14px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
            background-color: #f9f9f9;
        }

        .button-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #17224D;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
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

        @media (max-width: 700px) {
            .register-container {
                padding: 30px 20px;
                margin: 0 15px;
            }

            .register-form h2 {
                font-size: 24px;
            }

            input,
            button,
            a {
                font-size: 16px !important;
            }

            input {
                padding: 16px;
            }

            button {
                padding: 16px;
            }
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

            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</body>
</html>

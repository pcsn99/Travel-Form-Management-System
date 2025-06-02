<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Form Management System</title>

    <!-- Bootstrap CSS -->
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

        .card-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            width: 100%;
            max-width: 400px;
        }

        .title-card {
            background: rgba(23, 34, 77, 0.9);
            color: white;
            padding: 20px;
            border-radius: 12px;
            width: 100%;
            text-align: center;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
        }

        .login-container {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.3);
            width: 100%;
        }

        .login-form {
            color: #17224D;
            text-align: left;
        }

        .login-form h2 {
            font-size: 28px;
            margin-bottom: 10px;
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


    @media (max-width: 700px) {
        .title-card h1 {
            font-size: 24px;
        }

        .title-card p,
        .login-form p,
        label,
        input,
        button,
        a {
            font-size: 17px !important;
        }

        .login-container {
            padding: 30px;
        }

        .login-form h2 {
            font-size: 26px;
        }

        input[type="email"],
        input[type="password"] {
            padding: 16px;
            font-size: 18px;
        }

        .button-wrapper button {
            width: 100%;
            padding: 14px;
            font-size: 18px;
        }

        .modal-content {
            font-size: 17px;
        }

        .modal-body input {
            padding: 14px;
            font-size: 16px;
        }

        .modal-footer button {
            width: 100%;
            padding: 14px;
            font-size: 16px;
        }
    }
    </style>
</head>
<body>
    <div class="card-wrapper">
        <div class="title-card">
            <h1>Welcome to the Community Portal</h1>
            <p>Please log in to access your account</p>
        </div>

        <div class="login-container">
            <div class="login-form">

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

                    <p style="margin-top: 10px;">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
                    </p>
            </div>
        </div>
    </div>





    <!-- Forgot Password Modal -->
    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('member.password.reset') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Your Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="resetEmail" class="form-label">Enter your email address:</label>
                        <input type="email" name="email" id="resetEmail" class="form-control" required placeholder="your@email.com">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Send Temporary PIN</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-success">
        <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="successModalLabel">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            {{ session('success') }}
        </div>
        </div>
    </div>
    </div>
    @endif

    @if(session('error'))
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="errorModalLabel">Error</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            {{ session('error') }}
        </div>
        </div>
    </div>
    </div>
    @endif


    <!-- Bootstrap JS (requires Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('success'))
                new bootstrap.Modal(document.getElementById('successModal')).show();
            @endif

            @if(session('error'))
                new bootstrap.Modal(document.getElementById('errorModal')).show();
            @endif
        });
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NTC Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
       
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(to right, #002f6c, #5b92cc);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 420px;
        }

        /* Glass card */
        .login-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            color: #fff;
        }

        .subtitle {
            color: #6b7280;
            /* soft gray */
            font-size: 14px;
            margin-bottom: 25px;
        }

        .logo-container {
            background-color: #ffffff;
            display: inline-block;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .logo {
            width: 120px;
        }

        .login-box {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 24px;
        }

        input,
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            background: linear-gradient(135deg, #fbc531, #e1a900);
            color: #000;
            transition: all 0.3s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            opacity: 0.7;
        }
        .back-link { display: inline-block; margin-top: 15px; color: #072b42; }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-box">
            @if(file_exists(public_path('logo.jpg')))
                <div class="logo-container">
                    <img src="{{ asset('logo.jpg') }}" alt="NTC Logo" class="logo">
                </div>
            @endif
            <h1>Welcome Back</h1>
            <p class="subtitle">Login to NTC Admin Panel</p>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <div class="footer-text">© {{ date('Y') }} NTC • Secure Admin Access</div>
            <a href="{{ route('form.index') }}" class="back-link">← Back to SIP Application Form</a>
        </div>
    </div>
</body>
</html>

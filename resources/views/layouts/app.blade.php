<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nepal Telecom SIP System')</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        a { color: #072b42; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 100%; margin: 0; padding: 0; }
        .message-success { color: #28a745; padding: 10px; margin-bottom: 15px; background: #d4edda; border-radius: 6px; }
        .message-error { color: #721c24; padding: 10px; margin-bottom: 15px; background: #f8d7da; border-radius: 6px; }
   
   </style>
    @stack('styles')
</head>
<body>
    <div class="container">
        @if(session('message'))
            <p class="message-success">{{ session('message') }}</p>
        @endif
        @if(session('error'))
            <p class="message-error">{{ session('error') }}</p>
        @endif
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecommerce System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 32px; color: #17202a; background: #f7f9fb; }
        main { max-width: 980px; margin: 0 auto; }
        nav { margin-bottom: 24px; display: flex; gap: 16px; align-items: center; }
        a { color: #0b5cad; }
        .panel { background: white; border: 1px solid #d7dee8; border-radius: 8px; padding: 20px; margin-bottom: 18px; }
        label { display: block; margin-top: 12px; font-weight: 700; }
        input, textarea, select { width: 100%; padding: 10px; border: 1px solid #b8c2cc; border-radius: 6px; margin-top: 4px; box-sizing: border-box; }
        button { margin-top: 14px; padding: 10px 14px; border: 0; border-radius: 6px; background: #154c79; color: white; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #d7dee8; padding: 10px; text-align: left; vertical-align: top; }
        .notice { padding: 12px; border-radius: 6px; margin-bottom: 16px; background: #e8f4ff; }
        .error { background: #ffecec; }
        .warning { color: #8a4b00; font-weight: 700; }
    </style>
</head>
<body>
<main>
    <nav>
        <strong>Ecommerce System</strong>
        <a href="/register">Register</a>
        <a href="/login">Login</a>
        <a href="/transactions">Transactions</a>
        <a href="/logout">Logout</a>
    </nav>

    @if (session('message'))
        <div class="notice">{{ session('message') }}</div>
    @endif

    @if (session('error'))
        <div class="notice error">{{ session('error') }}</div>
    @endif

    @yield('content')
</main>
</body>
</html>

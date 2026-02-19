<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>500 - Server Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link href="{{ asset('css/vendors.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <style>
        .error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); }
        .error-box { text-align: center; padding: 2rem; }
        .error-code { font-size: 6rem; font-weight: 700; color: #6c757d; line-height: 1; }
        .error-title { font-size: 1.5rem; color: #495057; margin: 1rem 0; }
        .error-text { color: #6c757d; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-box">
            <div class="error-code">500</div>
            <h1 class="error-title">Server Error</h1>
            <p class="error-text">Something went wrong. Please try again later.</p>
            <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
        </div>
    </div>
</body>
</html>

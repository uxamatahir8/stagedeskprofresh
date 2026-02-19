<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>404 - Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link href="{{ asset('css/vendors.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
    <style>
        .error-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); }
        .error-box { text-align: center; padding: 2rem; }
        .error-code { font-size: 6rem; font-weight: 700; color: #43054E; line-height: 1; }
        .error-title { font-size: 1.5rem; color: #495057; margin: 1rem 0; }
        .error-text { color: #6c757d; margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-box">
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-text">The page you are looking for does not exist or has been moved.</p>
            <a href="{{ url()->previous() ?: url('/') }}" class="btn btn-primary">Go Back</a>
        </div>
    </div>
</body>
</html>

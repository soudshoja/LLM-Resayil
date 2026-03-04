<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error - LLM Resayil</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0f1115;
            color: #e0e5ec;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .error-container {
            text-align: center;
            max-width: 600px;
            animation: fadeIn 0.5s ease;
        }
        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #ef4444, #fca5a5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #e0e5ec;
        }
        .error-message {
            color: #a0a8b5;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-gold {
            background: linear-gradient(135deg, #d4af37, #ffd700);
            color: #0f1115;
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.3);
        }
        .btn-outline {
            border: 1px solid #8a702a;
            color: #d4af37;
            background: transparent;
            margin-left: 1rem;
        }
        .btn-outline:hover {
            background: rgba(212, 175, 55, 0.1);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .warning-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ef4444;
        }
        .debug-info {
            background: #13161d;
            border: 1px solid #1e2230;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            color: #a0a8b5;
            text-align: left;
        }
        .debug-info h3 {
            color: #d4af37;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="warning-icon">⚠️</div>
        <div class="error-code">500</div>
        <h1 class="error-title">Something went wrong</h1>
        <p class="error-message">
            We're experiencing technical difficulties on our end. Our team has been notified
            and we're working to fix the issue. Please try again in a few moments.
        </p>
        <div>
            <a href="/dashboard" class="btn btn-gold">Return to Dashboard</a>
            <button onclick="window.location.reload()" class="btn btn-outline">Try Again</button>
        </div>

        @if(app()->environment('local') || app()->environment('debug'))
        <div class="debug-info">
            <h3>Debug Information (Local Only)</h3>
            <p>Error: {{ $exception->getMessage() }}</p>
            <p>File: {{ $exception->getFile() }}:{{ $exception->getLine() }}</p>
        </div>
        @endif
    </div>
</body>
</html>

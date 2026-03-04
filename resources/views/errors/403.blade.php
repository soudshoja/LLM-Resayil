<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden - LLM Resayil</title>
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
        .security-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="security-icon">🚫</div>
        <div class="error-code">403</div>
        <h1 class="error-title">Forbidden</h1>
        <p class="error-message">
            You don't have permission to access this resource. Please contact support if you believe this is an error.
        </p>
        <div>
            <a href="/dashboard" class="btn btn-gold">Go to Dashboard</a>
        </div>
    </div>
</body>
</html>

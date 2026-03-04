<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #333; padding: 20px;">
    <h2>New User Registration — LLM Resayil</h2>
    <p>A new user has just registered on the LLM Resayil portal.</p>
    <p><strong>Name:</strong> {{ $name ?: '(not provided)' }}</p>
    <p><strong>Email:</strong> {{ $email ?: '(not provided)' }}</p>
    <p><strong>Phone:</strong> {{ $phone }}</p>
    <p><strong>Registered At:</strong> {{ $registeredAt }}</p>
    <hr>
    <p><em>This notification was sent automatically from llm.resayil.io</em></p>
</body>
</html>

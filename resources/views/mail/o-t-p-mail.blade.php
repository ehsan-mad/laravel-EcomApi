<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .otp-code {
            background-color: #f8fafc;
            border: 2px dashed #e2e8f0;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
            border-radius: 8px;
        }
        .otp-number {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: 8px;
            margin: 10px 0;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name', 'Your App') }}</div>
            <h1>Verification Code</h1>
        </div>

        <p>Hello {{ $user->name ?? 'User' }},</p>
        
        <p>You have requested a verification code. Please use the following OTP to complete your verification:</p>

        <div class="otp-code">
            <p style="margin: 0; font-size: 14px; color: #6b7280;">Your verification code is:</p>
            <div class="otp-number">{{ $details['code'] }}</div>
            <p style="margin: 0; font-size: 12px; color: #9ca3af;">This code will expire in {{ $expiryMinutes ?? 10 }} minutes</p>
        </div>

        <div class="warning">
            <strong>Security Notice:</strong> Never share this code with anyone. Our team will never ask for your verification code.
        </div>

        <p>If you didn't request this code, please ignore this email or contact our support team if you have concerns.</p>

        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

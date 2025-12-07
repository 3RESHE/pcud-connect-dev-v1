<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #16a34a;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }
        .body {
            padding: 20px;
            background-color: #f9fafb;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 0 0 5px 5px;
        }
        .success {
            background-color: #dcfce7;
            border-left: 4px solid #16a34a;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background-color: #e0e7ff;
            border-left: 4px solid #4f46e5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #16a34a;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }
        strong {
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Activated ✓</h1>
        </div>

        <div class="body">
            <p>Dear <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,</p>

            <div class="success">
                <p><strong>✅ Welcome back! Your account has been reactivated.</strong></p>
            </div>

            <p>Your account access to <strong>PCU-DASMA Connect</strong> has been restored. You can now log in and use all platform features.</p>

            <div class="info-box">
                <p><strong>Account Details:</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Email: {{ $user->email }}</li>
                    <li>Role: <strong>{{ ucfirst($user->role) }}</strong></li>
                    <li>Activation Date: <strong>{{ now()->format('F d, Y \a\t g:i A') }}</strong></li>
                </ul>
            </div>

            <p><strong>You can now:</strong></p>
            <ul>
                <li>✓ Log in to your account</li>
                <li>✓ View and apply for job opportunities</li>
                <li>✓ Register for events</li>
                <li>✓ Update your profile information</li>
                <li>✓ Access all features on PCU-DASMA Connect</li>
            </ul>

            <center>
                <a href="{{ url('/') }}" class="button">Go to PCU-DASMA Connect</a>
            </center>

            <p><strong>Need Help?</strong></p>
            <p>If you encounter any issues logging in, please contact support at <strong>support@pcudasma.edu.ph</strong></p>

            <p>Best regards,<br><strong>PCU-DASMA Connect Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ now()->year }} PCU-DASMA. All rights reserved.</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>

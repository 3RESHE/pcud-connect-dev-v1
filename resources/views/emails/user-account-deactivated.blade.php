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
            background-color: #dc2626;
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
        .alert {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
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
        strong {
            color: #1f2937;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Account Deactivated</h1>
        </div>

        <div class="body">
            <p>Dear <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,</p>

            <div class="alert">
                <p><strong>⚠️ Your account has been deactivated by an administrator.</strong></p>
            </div>

            <p>Your account access to <strong>PCU-DASMA Connect</strong> has been temporarily deactivated. This means you will no longer be able to log in or access any platform features.</p>

            <div class="info-box">
                <p><strong>Account Details:</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Email: {{ $user->email }}</li>
                    <li>Role: <strong>{{ ucfirst($user->role) }}</strong></li>
                    <li>Deactivation Date: <strong>{{ now()->format('F d, Y \a\t g:i A') }}</strong></li>
                </ul>
            </div>

            <p><strong>What does this mean?</strong></p>
            <ul>
                <li>You cannot log in to your account</li>
                <li>You cannot view or apply for jobs</li>
                <li>You cannot register for events</li>
                <li>All your profile information remains safe and will be preserved</li>
            </ul>

            <p><strong>Need Help?</strong></p>
            <p>If you believe this is an error or have questions about your account status, please contact the support team at <strong>support@pcudasma.edu.ph</strong></p>

            <p>Best regards,<br><strong>PCU-DASMA Connect Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ now()->year }} PCU-DASMA. All rights reserved.</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>

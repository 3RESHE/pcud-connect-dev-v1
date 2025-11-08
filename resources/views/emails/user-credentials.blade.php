<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Account Credentials</title>
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
        }
        .header {
            background-color: #003B73;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .credentials-box {
            background-color: #fff;
            border: 2px solid #003B73;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .credential-item {
            margin: 10px 0;
        }
        .credential-label {
            font-weight: bold;
            color: #003B73;
        }
        .credential-value {
            font-family: monospace;
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 3px;
            display: inline-block;
        }
        .button {
            display: inline-block;
            background-color: #003B73;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to PCU-DASMA Connect</h1>
        </div>

        <div class="content">
            <p>Dear {{ $user->first_name }} {{ $user->last_name }},</p>

            <p>Your account has been successfully created on the PCU-DASMA Connect platform.</p>

            <div class="credentials-box">
                <h3 style="margin-top: 0; color: #003B73;">Your Login Credentials</h3>

                <div class="credential-item">
                    <span class="credential-label">Email:</span><br>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Temporary Password:</span><br>
                    <span class="credential-value">{{ $password }}</span>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Account Type:</span><br>
                    <span class="credential-value">{{ ucfirst($user->role) }}</span>
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ Important Security Notice:</strong><br>
                You will be required to change this temporary password upon your first login for security purposes.
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Login to Your Account</a>
            </div>

            <p><strong>Next Steps:</strong></p>
            <ol>
                <li>Click the button above or visit: <a href="{{ url('/login') }}">{{ url('/login') }}</a></li>
                <li>Enter your email and temporary password</li>
                <li>Create a new secure password</li>
                <li>Complete your profile information</li>
            </ol>

            <p><strong>Need Help?</strong><br>
            If you have any questions or need assistance, please contact the support team at: <a href="mailto:support@pcu.edu.ph">support@pcu.edu.ph</a></p>
        </div>

        <div class="footer">
            <p>This is an automated message from PCU-DASMA Connect. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Philippine Christian University - Dasmariñas Campus. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

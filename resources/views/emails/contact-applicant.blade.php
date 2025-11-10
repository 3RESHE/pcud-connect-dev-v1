<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Helvetica Neue", sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            background: white;
            padding: 30px 20px;
            border: 1px solid #e5e7eb;
        }
        .message-box {
            background: #f3f4f6;
            padding: 20px;
            border-left: 4px solid #2563eb;
            margin: 20px 0;
            border-radius: 4px;
        }
        .job-details {
            background: #f0fdf4;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .job-details p {
            margin: 8px 0;
            font-size: 14px;
        }
        .job-details strong {
            color: #15803d;
        }
        .footer {
            background: #f9fafb;
            padding: 20px;
            border-top: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin: 20px 0;
        }
        .divider {
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hello {{ $applicant->name }}!</h1>
        </div>

        <div class="content">
            <p>We're reaching out regarding your application for the <strong>{{ $jobPosting->title }}</strong> position at <strong>{{ $partner->name }}</strong>.</p>

            <div class="message-box">
                {{ $message }}
            </div>

            <div class="divider"></div>

            <h3 style="color: #1f2937; margin-top: 20px;">Position Details</h3>
            <div class="job-details">
                <p><strong>Position:</strong> {{ $jobPosting->title }}</p>
                <p><strong>Company:</strong> {{ $partner->name }}</p>
                <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $jobPosting->job_type)) }}</p>
                <p><strong>Location:</strong> {{ $jobPosting->location ?? 'Remote' }}</p>
            </div>

            <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
                If you have any questions or need more information, feel free to reply to this email.
            </p>

            <p style="margin-top: 20px; color: #6b7280;">
                Best regards,<br>
                <strong>{{ $partner->name }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>This email was sent to you because you applied for a position. If you didn't apply, please disregard this email.</p>
            <p style="margin-top: 10px;">&copy; {{ date('Y') }} PCUD-Connect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

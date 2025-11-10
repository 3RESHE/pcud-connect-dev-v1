<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Message from {{ $partner->name }}</title>
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
        .message-box {
            background-color: #fff;
            border-left: 4px solid #003B73;
            padding: 20px;
            margin: 20px 0;
            line-height: 1.8;
        }
        .job-details {
            background-color: #ecfdf5;
            border: 1px solid #d1fae5;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .job-details p {
            margin: 8px 0;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Message from {{ $partner->name }}</h1>
        </div>

        <div class="content">
            <p>Dear {{ $applicant->name }},</p>

            <p>We're reaching out regarding your application for the <strong>{{ $jobPosting->title }}</strong> position.</p>

            <div class="message-box">
                {!! nl2br(e($messageContent)) !!}
            </div>

            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">

            <h3 style="color: #003B73; margin-top: 20px;">Position Details</h3>
            <div class="job-details">
                <p><strong>Position:</strong> {{ $jobPosting->title }}</p>
                <p><strong>Company:</strong> {{ $partner->name }}</p>
                <p><strong>Type:</strong> {{ ucfirst(str_replace('_', ' ', $jobPosting->job_type ?? 'N/A')) }}</p>
                <p><strong>Location:</strong> {{ $jobPosting->location ?? 'Remote' }}</p>
            </div>

            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                If you have any questions or need more information, please feel free to reply to this email.
            </p>

            <p style="margin-top: 20px;">
                Best regards,<br>
                <strong>{{ $partner->name }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>This email was sent to {{ $applicant->email }} because you applied for a position.</p>
            <p style="margin-top: 10px;">&copy; {{ date('Y') }} PCUD-Connect. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

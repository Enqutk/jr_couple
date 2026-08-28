<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
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
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 30px -30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            border-radius: 4px;
        }
        .field-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            display: block;
        }
        .field-value {
            color: #555;
        }
        .message-box {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 4px;
            border: 1px solid #bdc3c7;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }
        .company-info {
            background-color: #3498db;
            color: white;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>📧 New Contact Form Submission</h1>
        </div>

        <p>You have received a new contact form submission from your website. Here are the details:</p>

        <div class="field">
            <span class="field-label">👤 Name:</span>
            <div class="field-value">{{ $name }}</div>
        </div>

        <div class="field">
            <span class="field-label">📧 Email:</span>
            <div class="field-value">{{ $email }}</div>
        </div>

        <div class="field">
            <span class="field-label">📞 Phone:</span>
            <div class="field-value">{{ $phone }}</div>
        </div>

        <div class="field">
            <span class="field-label">📝 Subject:</span>
            <div class="field-value">{{ $subject }}</div>
        </div>

        <div class="field">
            <span class="field-label">💬 Message:</span>
            <div class="message-box">{{ $userMessage }}</div>
        </div>

        <div class="company-info">
            <strong>{{ $data['siteName'] ?? config('app.name') }}</strong><br>
            @if(!empty($data['tagline']))
                {{ $data['tagline'] }}<br>
            @endif
            <small>This email was sent from your website contact form</small>
        </div>

        <div class="footer">
            <p>Please respond to this inquiry promptly to maintain excellent customer service.</p>
            <p>You can reply directly to this email to respond to {{ $name }}.</p>
        </div>
    </div>
</body>
</html>

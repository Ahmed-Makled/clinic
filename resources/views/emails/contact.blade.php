<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة من نموذج الاتصال</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            direction: rtl;
        }
        .container mt-5 py-5{
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>رسالة جديدة من نموذج الاتصال</h2>
        </div>

        <div class="content">
            <p><strong>الاسم:</strong> {{ $name }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $email }}</p>
            <p><strong>الموضوع:</strong> {{ $subject }}</p>
            <p><strong>الرسالة:</strong></p>
            <p>{{ $message }}</p>
        </div>

        <div class="footer">
            <p>تم إرسال هذه الرسالة تلقائياً من نموذج الاتصال في موقع عيادتي</p>
        </div>
    </div>
</body>
</html>

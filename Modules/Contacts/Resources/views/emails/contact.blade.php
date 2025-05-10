<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة من نموذج الاتصال</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            direction: rtl;
            text-align: right;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4e73df;
            color: white;
            padding: 15px 20px;
            border-radius: 5px 5px 0 0;
            margin: -20px -20px 20px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
            color: #777;
            text-align: center;
        }
        h2 {
            color: #4e73df;
            margin-top: 0;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        table th {
            text-align: right;
            padding: 10px;
            border-bottom: 1px solid #eee;
            background-color: #f8f9fc;
            width: 30%;
        }
        .message-content {
            background-color: #f8f9fc;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>رسالة جديدة من نموذج الاتصال</h1>
        </div>

        <h2>تفاصيل الرسالة</h2>

        <table>
            <tr>
                <th>الاسم</th>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <th>البريد الإلكتروني</th>
                <td>{{ $email }}</td>
            </tr>
            <tr>
                <th>الموضوع</th>
                <td>{{ $subject }}</td>
            </tr>
        </table>

        <h3>نص الرسالة:</h3>
        <div class="message-content">
            {{ $message }}
        </div>

        <div class="footer">
            <p>تم إرسال هذه الرسالة من نموذج الاتصال في موقع عيادتي - {{ date('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</body>
</html>

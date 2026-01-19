<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            direction: rtl;
            text-align: right;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        .otp-code {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 8px;
            text-align: center;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .info-box {
            background-color: #f8f9fa;
            border-right: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 رمز التحقق</h1>
            <p>منصة التطوع والتدريب</p>
        </div>

        <div class="content">
            @if($userName)
                <p>مرحباً <strong>{{ $userName }}</strong>،</p>
            @else
                <p>مرحباً،</p>
            @endif

            <p>شكراً لتسجيلك في منصة التطوع. للمتابعة، يرجى استخدام رمز التحقق التالي:</p>

            <div class="otp-code">
                {{ $otpCode }}
            </div>

            <div class="info-box">
                <p style="margin: 0;"><strong>⏰ مهم:</strong></p>
                <ul style="margin: 10px 0;">
                    <li>هذا الرمز صالح لمدة <strong>5 دقائق</strong> فقط</li>
                    <li>لا تشارك هذا الرمز مع أي شخص</li>
                    <li>إذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذه الرسالة</li>
                </ul>
            </div>

            <p>في حال انتهاء صلاحية الرمز، يمكنك طلب رمز جديد من خلال صفحة التحقق.</p>

            <p>مع أطيب التمنيات،<br><strong>فريق منصة التطوع</strong></p>
        </div>

        <div class="footer">
            <p>هذه رسالة تلقائية، الرجاء عدم الرد عليها.</p>
            <p>© {{ date('Y') }} منصة التطوع والتدريب. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>

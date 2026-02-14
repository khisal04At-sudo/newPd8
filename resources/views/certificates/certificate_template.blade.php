<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificate of Attendance</title>
    <style>
        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            margin: 0;
            padding: 10px;
            background-color: #ffffff;
            direction: ltr;
        }
        .certificate-container {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            position: relative;
            background: white;
            border: 12px solid #1e293b;
            box-sizing: border-box;
            padding: 25px;
            min-height: 450px;
        }
        .inner-border {
            border: 3px solid #3b82f6;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        .header {
            margin-bottom: 20px;
            text-align: center;
        }
        .cert-info {
            text-align: right;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 15px;
        }
        .platform-name {
            font-size: 18px;
            font-weight: bold;
            color: #1e293b;
            margin: 8px 0;
        }
        .badge {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 15px;
            display: inline-block;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
        }
        .certificate-title {
            font-size: 34px;
            font-weight: bold;
            color: #3b82f6;
            margin: 15px 0;
            letter-spacing: 1px;
        }
        .content {
            margin-top: 15px;
            font-size: 14px;
            color: #475569;
            line-height: 1.4;
            text-align: center;
        }
        .recipient-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e293b;
            margin: 15px 0;
            border-bottom: 2px solid #e2e8f0;
            display: inline-block;
            padding: 5px 25px;
        }
        .organization-name {
            font-size: 22px;
            color: #1e293b;
            font-weight: bold;
            margin: 10px 0;
        }
        .opportunity-title {
            color: #2563eb;
            font-weight: bold;
            font-size: 20px;
            display: inline-block;
            margin: 10px 0;
        }
        .stats {
            font-size: 16px;
            margin: 15px 0;
            color: #1e293b;
        }
        .footer {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            text-align: center;
            width: 33%;
            vertical-align: top;
        }
        .signature-line {
            border-top: 2px solid #94a3b8;
            margin-top: 30px;
            padding-top: 5px;
            font-weight: bold;
            font-size: 11px;
            color: #475569;
        }
        .qr-box {
            display: table-cell;
            text-align: center;
            width: 34%;
            vertical-align: top;
        }
        .qr-code {
            width: 70px;
            height: 70px;
            background: #f1f5f9;
            display: inline-block;
            text-align: center;
            line-height: 70px;
            font-size: 9px;
            border: 2px solid #cbd5e1;
            margin: 0 auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="inner-border">
            <div class="badge">Official Certification</div>
            
            <div class="cert-info">
                Certificate No: {{ $certificateNumber }}<br>
                Issue Date: {{ $issueDate }}
            </div>
            
            <div class="header">
                <div class="platform-name">Atheera Platform for Volunteering & Training</div>
            </div>

            <h1 class="certificate-title">CERTIFICATE OF ATTENDANCE</h1>
            
            <div class="content">
                <p style="margin: 5px 0;">
                    This certifies that Atheera Platform in collaboration with <span class="organization-name" style="font-size: 18px;">{{ $organization->name }}</span>
                    hereby acknowledges that <span class="recipient-name" style="font-size: 24px; margin: 5px 0; border-bottom: 2px solid #e2e8f0; padding: 0 15px;">{{ $recipientName }}</span>
                    has successfully attended and participated in <span class="opportunity-title" style="font-size: 18px;">{{ $opportunity->title }}</span>
                </p>
                <div class="stats" style="margin: 10px 0;">
                    Completing <strong>{{ $application->attended_hours }}</strong> volunteer hours with an attendance rate of <strong>{{ number_format($percentage, 0) }}%</strong>
                </div>
            </div>

            <div class="footer">
                <div class="signature-box">
                    <div class="signature-line">Organization Seal & Signature</div>
                </div>
                
                <div class="qr-box">
                    <div class="qr-code">
                        QR Code
                    </div>
                </div>

                <div class="signature-box">
                    <div class="signature-line">Atheera Platform Management</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التحقق من الشهادة - أثيرا</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 50%, #fdf2f8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            animation: float 25s infinite alternate ease-in-out;
            z-index: 0;
        }
        .blob-1 { width: 500px; height: 500px; background: #10b981; top: -10%; left: -10%; }
        .blob-2 { width: 400px; height: 400px; background: #3b82f6; bottom: -10%; right: -10%; }
        @keyframes float {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(80px, 80px) scale(1.15); }
        }
        .card {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08);
            max-width: 620px;
            width: 100%;
            padding: 3rem;
            position: relative;
            z-index: 10;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="card">
        @if($certificate)
            {{-- ✅ Valid Certificate --}}
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #3b82f6); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 15px 30px rgba(16,185,129,0.25);">
                <i class="fas fa-certificate" style="color: white; font-size: 2rem;"></i>
            </div>

            <div style="background: #ecfdf5; color: #059669; padding: 0.6rem 1.5rem; border-radius: 2rem; font-weight: 800; font-size: 0.9rem; display: inline-block; margin-bottom: 1.5rem; border: 1px solid #d1fae5;">
                <i class="fas fa-check-circle" style="margin-left: 0.4rem;"></i>
                شهادة موثّقة وصالحة
            </div>

            <h1 style="font-size: 1.5rem; font-weight: 900; color: #1e293b; margin-bottom: 2rem;">التحقق من الشهادة</h1>

            <div style="background: #f8fafc; border-radius: 1.5rem; padding: 2rem; text-align: right; margin-bottom: 2rem; border: 1px solid #e2e8f0;">
                <div style="display: flex; flex-direction: column; gap: 1.1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.9rem; border-bottom: 1px solid #f1f5f9;">
                        <span style="color: #64748b; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-hashtag" style="margin-left: 0.4rem; color: #10b981;"></i>رقم الشهادة</span>
                        <span style="font-weight: 800; color: #1e293b; font-size: 0.9rem; direction: ltr;">{{ $certificate->certificate_number }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.9rem; border-bottom: 1px solid #f1f5f9;">
                        <span style="color: #64748b; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-user" style="margin-left: 0.4rem; color: #10b981;"></i>الحاصل على الشهادة</span>
                        <span style="font-weight: 800; color: #1e293b;">{{ $certificate->user->name ?? '—' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.9rem; border-bottom: 1px solid #f1f5f9;">
                        <span style="color: #64748b; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-building" style="margin-left: 0.4rem; color: #10b981;"></i>الجهة المانحة</span>
                        <span style="font-weight: 800; color: #1e293b;">{{ $certificate->organization_name }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.9rem; border-bottom: 1px solid #f1f5f9;">
                        <span style="color: #64748b; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-briefcase" style="margin-left: 0.4rem; color: #10b981;"></i>البرنامج / الفرصة</span>
                        <span style="font-weight: 800; color: #1e293b; max-width: 200px; text-align: left;">{{ $certificate->opportunity_title }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.9rem; border-bottom: 1px solid #f1f5f9;">
                        <span style="color: #64748b; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-clock" style="margin-left: 0.4rem; color: #10b981;"></i>الساعات المعتمدة</span>
                        <span style="font-weight: 800; color: #1e293b;">{{ $certificate->attended_hours }} / {{ $certificate->total_hours }} ساعة ({{ number_format($certificate->attendance_percentage, 0) }}%)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: #64748b; font-weight: 600; font-size: 0.9rem;"><i class="fas fa-calendar-check" style="margin-left: 0.4rem; color: #10b981;"></i>تاريخ الإصدار</span>
                        <span style="font-weight: 800; color: #1e293b;">{{ \Carbon\Carbon::parse($certificate->issue_date)->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>

            <p style="color: #94a3b8; font-size: 0.85rem; margin: 0;">
                <i class="fas fa-shield-alt" style="color: #10b981; margin-left: 0.4rem;"></i>
                هذه الشهادة صادرة وموثّقة من منصة أثيرا للتطوع والتدريب
            </p>

        @else
            {{-- ❌ Invalid --}}
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: 0 15px 30px rgba(239,68,68,0.25);">
                <i class="fas fa-times" style="color: white; font-size: 2rem;"></i>
            </div>

            <div style="background: #fef2f2; color: #dc2626; padding: 0.6rem 1.5rem; border-radius: 2rem; font-weight: 800; font-size: 0.9rem; display: inline-block; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
                <i class="fas fa-times-circle" style="margin-left: 0.4rem;"></i>
                شهادة غير موجودة أو غير صالحة
            </div>

            <h1 style="font-size: 1.5rem; font-weight: 900; color: #1e293b; margin-bottom: 1rem;">فشل التحقق</h1>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 2rem;">
                رقم الشهادة <strong style="color: #1e293b;">{{ $number }}</strong> غير موجود في قاعدة بياناتنا.<br>
                يُرجى التأكد من صحة الرابط أو QR Code.
            </p>
        @endif

        <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #64748b; text-decoration: none; font-weight: 700; font-size: 0.9rem; transition: color 0.2s;" onmouseover="this.style.color='#10b981'" onmouseout="this.style.color='#64748b'">
            <i class="fas fa-arrow-right"></i>
            العودة للصفحة الرئيسية
        </a>
    </div>
</body>
</html>

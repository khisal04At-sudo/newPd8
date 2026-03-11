<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>التحقق من الحساب</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap');
        body {
            background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 50%, #fdf2f8 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cairo', 'Segoe UI', sans-serif;
            position: relative;
            overflow: hidden;
        }
        .abstract-shape {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.5;
            animation: float-slow 25s infinite alternate ease-in-out;
        }
        @keyframes float-slow {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 100px) scale(1.2); }
        }
        .shape-1 { width: 500px; height: 500px; background: #10b981; top: -10%;  left: -10%;  }
        .shape-2 { width: 400px; height: 400px; background: #3b82f6; bottom: -10%; right: -10%; }
        .verify-container {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 2.5rem;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            max-width: 500px;
            width: 90%;
            position: relative;
            z-index: 10;
        }
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 30px 0;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .otp-input:focus {
            outline: none;
            border-color: #10b981;
            transform: scale(1.05);
        }
        .resend-timer {
            color: #666;
            font-size: 14px;
            margin-top: 15px;
        }
        .resend-btn {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
            font-size: 16px;
            font-family: inherit;
        }
        .resend-btn:hover:not(:disabled) {
            transform: translateY(-2px);
        }
        .resend-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .submit-btn {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
            margin-top: 20px;
            font-family: inherit;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
        }
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="abstract-shape shape-1"></div>
    <div class="abstract-shape shape-2"></div>
    <div class="verify-container">
        <div class="icon" style="text-align: center;">🔐</div>
        <h1 style="text-align: center; color: #333; margin-bottom: 10px;">التحقق من حسابك</h1>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">
            أدخل رمز التحقق المرسل إلى<br>
            <strong>{{ $email }}</strong>
        </p>

        @if($errors->any())
            <div class="alert alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verify-otp.submit') }}" id="otpForm">
            @csrf
            
            <div class="otp-inputs">
                <input type="text" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" data-index="0">
                <input type="text" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" data-index="1">
                <input type="text" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" data-index="2">
                <input type="text" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" data-index="3">
                <input type="text" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" data-index="4">
                <input type="text" class="otp-input" maxlength="1" pattern="\d" inputmode="numeric" data-index="5">
            </div>

            <input type="hidden" name="otp_code" id="otpCode">

            <button type="submit" class="submit-btn">تأكيد الرمز</button>
        </form>

        <div style="text-align: center; margin-top: 30px;">
            <p class="resend-timer" id="resendTimer" style="display: none;">
                يمكنك إعادة الإرسال بعد <span id="countdown">60</span> ثانية
            </p>
            <button type="button" class="resend-btn" id="resendBtn" onclick="resendOtp()">
                إعادة إرسال الرمز
            </button>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <p style="font-size: 14px; color: #666;">⏰ صلاحية الرمز: 5 دقائق</p>
        </div>
    </div>

    <script>
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpCodeField = document.getElementById('otpCode');
        const resendBtn = document.getElementById('resendBtn');
        const resendTimer = document.getElementById('resendTimer');
        const countdownSpan = document.getElementById('countdown');

        let countdown = 60;
        let timerInterval = null;

        // Auto-focus first input
        otpInputs[0].focus();

        // Handle OTP input
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                // Only allow numbers
                if (!/^\d$/.test(value)) {
                    e.target.value = '';
                    return;
                }

                // Move to next input
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }

                // Update hidden field
                updateOtpCode();
            });

            input.addEventListener('keydown', (e) => {
                // Handle backspace
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }

                // Handle paste
                if (e.key === 'v' && (e.ctrlKey || e.metaKey)) {
                    e.preventDefault();
                }
            });

            // Handle paste on entire form
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
                
                if (pastedData.length === 6) {
                    pastedData.split('').forEach((char, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = char;
                        }
                    });
                    updateOtpCode();
                    otpInputs[5].focus();
                }
            });
        });

        function updateOtpCode() {
            const code = Array.from(otpInputs).map(input => input.value).join('');
            otpCodeField.value = code;
        }

        function startCountdown() {
            countdown = 60;
            resendBtn.disabled = true;
            resendTimer.style.display = 'block';
            
            timerInterval = setInterval(() => {
                countdown--;
                countdownSpan.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timerInterval);
                    resendBtn.disabled = false;
                    resendTimer.style.display = 'none';
                }
            }, 1000);
        }

        async function resendOtp() {
            try {
                resendBtn.disabled = true;
                resendBtn.textContent = 'جاري الإرسال...';

                const response = await fetch('{{ route("verify-otp.resend") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    startCountdown();
                    
                    // Clear inputs
                    otpInputs.forEach(input => input.value = '');
                    otpInputs[0].focus();
                } else {
                    alert(data.message);
                    if (!data.seconds_remaining) {
                        resendBtn.disabled = false;
                    }
                }
            } catch (error) {
                alert('حدث خطأ. الرجاء المحاولة مرة أخرى.');
                resendBtn.disabled = false;
            } finally {
                resendBtn.textContent = 'إعادة إرسال الرمز';
            }
        }

        // Start countdown if page just loaded from resend
        @if(!$canResend)
            startCountdown();
        @endif
    </script>
</body>
</html>

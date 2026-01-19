{{-- resources/views/auth/choose-type.blade.php --}}
<!doctype html>
<html lang="ar">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>اختر نوع الحساب</title>
  <style>
    body{font-family: Arial, sans-serif; direction: rtl; padding:40px;}
    .container{max-width:700px;margin:0 auto;text-align:center;}
    .btn{display:inline-block;padding:14px 24px;margin:12px;border-radius:8px;text-decoration:none;font-weight:600;}
    .btn-vol{background:#2d8cff;color:#fff;}
    .btn-org{background:#22c55e;color:#fff;}
  </style>
</head>
<body>
  <div class="container">
    <h1>اختر نوع الحساب</h1>
    <p>هل تريد التسجيل كمتطوع أم كمؤسسة؟</p>


    <a class="btn btn-vol" href="{{ route('register.volunteer') }}">تسجيل كمتطوع</a>
    <a class="btn btn-org" href="{{ route('register.organization') }}">تسجيل كمؤسسة</a>

    <div style="margin-top:20px;">
      <a href="{{ url('/') }}">العودة للصفحة الرئيسية</a>
    </div>
  </div>
</body>
</html>

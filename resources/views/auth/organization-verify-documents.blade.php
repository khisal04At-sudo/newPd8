<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع مستندات التحقق - أثيرا</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
        }
        body {
            background: #f1f5f9;
            font-family: 'Inter', 'Noto Sans Arabic', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            margin: 0;
        }
        .upload-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
            padding: 2.5rem;
        }
        .header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .header i {
            color: var(--primary);
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .header h2 {
            font-size: 1.5rem;
            color: #1e293b;
        }
        .drop-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f8fafc;
        }
        .drop-zone:hover {
            border-color: var(--primary);
            background: #f0f9ff;
        }
        .drop-zone i {
            font-size: 2.5rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }
        .file-list {
            margin-top: 1.5rem;
            text-align: right;
        }
        .btn-submit {
            background: var(--primary);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 0.75rem;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 2rem;
        }
        .btn-submit:hover {
            background: var(--primary-dark);
        }
        .alert {
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        .alert-info {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body>

    <div class="upload-card">
        <div class="header">
            <i class="fas fa-file-shield"></i>
            <h2>رفع مستندات التحقق</h2>
            <p style="color: #64748b;">بصفتك مؤسسة، نحتاج لمراجعة مستنداتك الرسمية لتفعيل حسابك</p>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            يرجى رفع صور من السجل التجاري أو الترخيص الرسمي للمؤسسة أو أي مستند يثبت صفتكم الرسمية.
        </div>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-right: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('organization.verify.documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="drop-zone" onclick="document.getElementById('documents').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>اسحب وأفلت الملفات هنا أو اضغط للاختيار</p>
                <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.5rem;">الصيغ المدعومة: PDF, JPG, PNG (بحد أقصى 5MB لكل ملف)</p>
                <input type="file" name="documents[]" id="documents" multiple hidden onchange="updateFileList(this)">
            </div>

            <div id="file-list" class="file-list"></div>

            <button type="submit" class="btn-submit">حفظ وإرسال للمراجعة</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem;">
            <a href="{{ route('dashboard') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem;">إكمال لاحقاً</a>
        </div>
    </div>

    <script>
        function updateFileList(input) {
            const list = document.getElementById('file-list');
            list.innerHTML = '<strong>الملفات المختارة:</strong><br>';
            for (let i = 0; i < input.files.length; i++) {
                list.innerHTML += `<div style="padding: 5px 0;"><i class="fas fa-file"></i> ${input.files[i].name}</div>`;
            }
        }
    </script>
</body>
</html>

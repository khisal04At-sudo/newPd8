<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إكمال الملف الشخصي</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .form-container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            color: #1e293b;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #475569;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: border-color 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .skills-container {
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 8px;
            background: #f1f5f9;
        }
        .skill-row {
            display: flex;
            gap: 15px;
            margin-bottom: 10px;
        }
        .btn-add-skill {
            background: #6366f1;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e2e8f0;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header">
            <h1>إكمال معلومات المتطوع</h1>
            <p>أخبرنا المزيد عن مهاراتك وخبراتك لتجد أفضل الفرص</p>
        </div>

        @if($errors->any())
            <div style="background: #fee; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('profile.complete.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">الصورة الشخصية</label>
                <img src="{{ asset('images/default-avatar.png') }}" id="avatarPreview" class="avatar-preview">
                <input type="file" name="avatar" class="form-control" accept="image/*" onchange="previewImage(event)">
            </div>

            <div class="form-group">
                <label class="form-label">النبذة التعريفية (Bio)</label>
                <textarea name="bio" class="form-control" rows="4" placeholder="اكتب نبذة قصيرة عنك وعن اهتماماتك..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">السيرة الذاتية (CV)</label>
                <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                <small style="color: #64748b;">أنواع الملفات المسموحة: PDF, DOC, DOCX (بحد أقصى 5 ميجابايت)</small>
            </div>

            <div class="form-group">
                <label class="form-label">المهارات والخبرة</label>
                <div class="skills-container" id="skillsList">
                    <div class="skill-row">
                        <input type="text" name="skills[0][name]" class="form-control" placeholder="اسم المهارة (مثال: التصميم)">
                        <select name="skills[0][level]" class="form-control" style="width: 200px;">
                            <option value="beginner">مبتدئ</option>
                            <option value="intermediate">متوسط</option>
                            <option value="advanced">متقدم</option>
                            <option value="expert">خبير</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn-add-skill" onclick="addSkill()">+ إضافة مهارة أخرى</button>
            </div>

            <div style="margin-top: 40px; display: flex; gap: 15px;">
                <button type="submit" class="btn-submit">حفظ وإكمال الملف</button>
                <a href="{{ route('dashboard') }}" style="display: block; text-align: center; width: 200px; padding: 15px; background: #e2e8f0; color: #475569; border-radius: 10px; text-decoration: none; font-weight: bold;">الإكمال لاحقاً</a>
            </div>
        </form>
    </div>

    <script>
        let skillIndex = 1;

        function addSkill() {
            const container = document.getElementById('skillsList');
            const row = document.createElement('div');
            row.className = 'skill-row';
            row.innerHTML = `
                <input type="text" name="skills[${skillIndex}][name]" class="form-control" placeholder="اسم المهارة">
                <select name="skills[${skillIndex}][level]" class="form-control" style="width: 200px;">
                    <option value="beginner">مبتدئ</option>
                    <option value="intermediate">متوسط</option>
                    <option value="advanced">متقدم</option>
                    <option value="expert">خبير</option>
                </select>
                <button type="button" style="background:none; border:none; color:#ef4444; font-size:20px; cursor:pointer;" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(row);
            skillIndex++;
        }

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>

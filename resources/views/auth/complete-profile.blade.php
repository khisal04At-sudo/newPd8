<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إكمال الملف الشخصي</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            min-height: 100vh;
            font-family: 'Cairo', 'Segoe UI', sans-serif;
            display: flex; align-items: center; justify-content: center;
        }
        .form-container {
            max-width: 820px; width: 95%;
            background: white;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            margin: 30px auto;
        }
        .header { text-align: center; margin-bottom: 2rem; }
        .header h1 { color: #1e293b; font-size: 1.75rem; font-weight: 800; }
        .header p { color: #64748b; font-size: 0.95rem; }
        .form-group { margin-bottom: 1.75rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 700; color: #334155; font-size: 0.95rem; }
        .form-control {
            width: 100%; padding: 0.75rem 1rem;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-family: inherit; font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-control:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }

        /* Interests Grid */
        .interests-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 0.85rem;
        }
        .interest-card {
            position: relative;
        }
        .interest-card input[type="checkbox"] { display: none; }
        .interest-card label {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 0.5rem; padding: 1rem 0.5rem;
            border: 2px solid #e2e8f0; border-radius: 14px;
            cursor: pointer; transition: all 0.2s;
            background: #f8fafc; font-size: 0.9rem; font-weight: 700; color: #475569;
            text-align: center;
        }
        .interest-card label .icon { font-size: 1.75rem; }
        .interest-card input:checked + label {
            border-color: var(--interest-color);
            background: color-mix(in srgb, var(--interest-color) 10%, white);
            color: var(--interest-color);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--interest-color) 20%, transparent);
        }
        .interest-card label:hover { border-color: #a5b4fc; background: #f0f4ff; }

        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white; width: 100%; padding: 1rem;
            border: none; border-radius: 12px; font-size: 1.1rem; font-weight: 700;
            cursor: pointer; transition: transform 0.2s;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(79,70,229,0.3); }
        .avatar-preview { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; margin-bottom: 0.75rem; display: block; }
        .hint { font-size: 0.8rem; color: #94a3b8; margin-top: 0.35rem; }
        .skip-link { display: block; text-align: center; margin-top: 1rem; color: #94a3b8; font-size: 0.9rem; text-decoration: none; }
        .skip-link:hover { color: #64748b; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="header">
            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">👤</div>
            <h1>إكمال الملف الشخصي</h1>
            <p>أخبرنا عن نفسك واخترِ اهتماماتك لنقترح عليك الفرص المناسبة</p>
        </div>

        @if($errors->any())
            <div style="background: #fef2f2; color: #b91c1c; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #fecaca;">
                @foreach ($errors->all() as $error)
                    <p style="margin: 0.25rem 0;">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('profile.complete.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group" style="text-align: center;">
                <img src="{{ asset('images/default-avatar.png') }}" id="avatarPreview" class="avatar-preview" style="margin: 0 auto;">
                <label class="form-label" style="display: inline-block; margin-top: 0.5rem; cursor: pointer; color: #6366f1;">
                    <i class="fas fa-camera"></i> رفع صورة شخصية
                    <input type="file" name="avatar" accept="image/*" onchange="previewImage(event)" style="display:none;">
                </label>
                <p class="hint">JPG / PNG - بحد أقصى 2 ميجابايت</p>
            </div>

            <div class="form-group">
                <label class="form-label">النبذة التعريفية</label>
                <textarea name="bio" class="form-control" rows="3" placeholder="اكتب نبذة قصيرة عنك..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">السيرة الذاتية <span style="color: #94a3b8; font-weight: 400;">(اختياري)</span></label>
                <input type="file" name="cv" class="form-control" accept=".pdf,.doc,.docx">
                <p class="hint">PDF, DOC, DOCX - بحد أقصى 5 ميجابايت</p>
            </div>

            <div class="form-group">
                <label class="form-label">🎯 اهتماماتك <span style="color: #94a3b8; font-weight: 400;">(اختر ما يناسبك)</span></label>
                <p class="hint" style="margin-bottom: 1rem;">ستصلك إشعارات عند نشر فرص في هذه المجالات</p>
                <div class="interests-grid">
                    @foreach($categories as $name => $info)
                        <div class="interest-card" style="--interest-color: {{ $info['color'] }}">
                            <input type="checkbox" name="interests[]" value="{{ $name }}"
                                   id="interest_{{ $loop->index }}"
                                   {{ in_array($name, $userInterests) ? 'checked' : '' }}>
                            <label for="interest_{{ $loop->index }}">
                                <span class="icon">
                                    @if(str_contains($info['icon'], '/'))
                                        <img src="{{ asset($info['icon']) }}" style="width: 1.75rem; height: 1.75rem; object-fit: contain;">
                                    @else
                                        <i class="{{ $info['icon'] }}"></i>
                                    @endif
                                </span>
                                {{ $name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn-submit">حفظ وإكمال الملف الشخصي</button>
                <a href="{{ route('dashboard') }}" class="skip-link">إكمال لاحقاً</a>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                document.getElementById('avatarPreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>

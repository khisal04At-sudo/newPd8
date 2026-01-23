@extends('layouts.app')

@section('title', 'التقديم على: ' . $opportunity->title)

@section('content')
<div style="background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%); min-height: 100vh; padding: 3rem 0;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Back -->
        <a href="{{ route('opportunities.show', $opportunity) }}" style="display: inline-flex; align-items: center; gap: 0.75rem; text-decoration: none; color: #64748b; font-weight: 700; margin-bottom: 2rem; transition: all 0.3s;" onmouseover="this.style.color='var(--brand-blue)'" onmouseout="this.style.color='#64748b'">
            <i class="fas fa-arrow-right"></i> العودة لتفاصيل الفرصة
        </a>

        <div class="card" style="padding: 3rem; border-radius: 2rem; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.05);">
            <div style="text-align: center; margin-bottom: 3rem;">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 20px rgba(59,130,246,0.3);">
                    <i class="fas fa-file-alt" style="font-size: 2rem; color: white;"></i>
                </div>
                <h1 style="font-size: 2rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">التقديم على الفرصة</h1>
                <p style="color: #64748b; font-size: 1.1rem;">{{ $opportunity->title }}</p>
            </div>

            <!-- Opp Info Summary -->
            <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1rem; margin-bottom: 3rem; border: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <img src="{{ url($opportunity->organization->logo_url ?? 'assets/default-logo.png') }}" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover;">
                    <div>
                        <div style="font-weight: 700; color: #1e293b;">{{ $opportunity->organization->name }}</div>
                        <div style="font-size: 0.85rem; color: #64748b;">{{ $opportunity->city->name ?? 'عن بعد' }} • {{ $opportunity->total_hours }} ساعة</div>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div style="background: #fee2e2; color: #b91c1c; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem; border: 1px solid #fecaca;">
                    <div style="font-weight: 700; margin-bottom: 0.5rem;"><i class="fas fa-exclamation-circle"></i> يرجى تصحيح الأخطاء التالية:</div>
                    <ul style="margin: 0; padding-right: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('applications.store', $opportunity) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- CV Upload -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; font-size: 1.05rem;">
                        <i class="fas fa-file-upload" style="color: var(--brand-blue); margin-left: 0.5rem;"></i>
                        السيرة الذاتية (CV) <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="border: 2px dashed #cbd5e1; border-radius: 1rem; padding: 2rem; text-align: center; background: #f8fafc; transition: all 0.2s;" id="upload-area">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <div style="color: #64748b; margin-bottom: 1rem;">
                            اسحب وأفلت ملف السيرة الذاتية أو انقر للاختيار
                        </div>
                        <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" required style="display: none;">
                        <button type="button" onclick="document.getElementById('resume').click()" style="padding: 0.75rem 2rem; background: var(--brand-blue); color: white; border: none; border-radius: 0.75rem; font-weight: 700; cursor: pointer;">
                            اختر ملف
                        </button>
                        <div id="file-name" style="margin-top: 1rem; color: var(--volunteer-green); font-weight: 600;"></div>
                        <div style="font-size: 0.85rem; color: #94a3b8; margin-top: 0.75rem;">
                            الصيغ المدعومة: PDF, DOC, DOCX (الحد الأقصى: 5 ميجابايت)
                        </div>
                    </div>
                </div>

                <!-- Cover Letter (if required) -->
                @if($opportunity->requires_cover_letter == 'yes')
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; font-size: 1.05rem;">
                        <i class="fas fa-envelope" style="color: var(--volunteer-green); margin-left: 0.5rem;"></i>
                        خطاب التغطية <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea name="cover_letter" rows="6" required style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 1rem; font-family: 'Cairo', sans-serif; font-size: 1rem; outline: none; resize: vertical; transition: all 0.2s;" placeholder="اكتب خطاب تغطية يوضح دافعك للتقديم على هذه الفرصة..." onfocus="this.style.borderColor='var(--brand-blue)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">{{ old('cover_letter') }}</textarea>
                    <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.5rem;">يوضح هذا لماذا أنت مناسب لهذه الفرصة (الحد الأقصى 2000 حرف)</div>
                </div>
                @else
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem; font-size: 1.05rem;">
                        <i class="fas fa-envelope" style="color: #94a3b8; margin-left: 0.5rem;"></i>
                        خطاب التغطية (اختياري)
                    </label>
                    <textarea name="cover_letter" rows="5" style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 1rem; font-family: 'Cairo', sans-serif; font-size: 1rem; outline: none; resize: vertical; transition: all 0.2s;" placeholder="يمكنك إضافة خطاب تغطية اختياري..." onfocus="this.style.borderColor='var(--brand-blue)'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">{{ old('cover_letter') }}</textarea>
                </div>
                @endif

                <!-- Info Notice -->
                <div style="background: #eff6ff; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem; border-right: 4px solid var(--brand-blue);">
                    <div style="color: #1e40af; font-weight: 600; display: flex; gap: 0.75rem;">
                        <i class="fas fa-info-circle" style="font-size: 1.2rem; flex-shrink: 0;"></i>
                        <div style="font-size: 0.95rem; line-height: 1.6;">
                            بمجرد تقديم الطلب، سيتم إرسال اشعار للمؤسسة لمراجعة طلبك. ستتلقى رداً في أقرب وقت ممكن. تأكد من صحة جميع البيانات قبل التقديم.
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('opportunities.show', $opportunity) }}" style="flex: 1; padding: 1rem; background: #f1f5f9; color: #64748b; border: none; border-radius: 1rem; font-weight: 700; text-align: center; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                        إلغاء
                    </a>
                    <button type="submit" style="flex: 2; padding: 1rem; background: linear-gradient(135deg, var(--volunteer-green), #059669); color: white; border: none; border-radius: 1rem; font-weight: 800; font-size: 1.1rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 24px rgba(16, 185, 129, 0.4)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 8px 16px rgba(16, 185, 129, 0.3)'">
                        <i class="fas fa-paper-plane"></i> تأكيد التقديم
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // File upload preview
    document.getElementById('resume').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        const fileNameDiv = document.getElementById('file-name');
        const uploadArea = document.getElementById('upload-area');
        
        if (fileName) {
            fileNameDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + fileName;
            uploadArea.style.borderColor = 'var(--volunteer-green)';
            uploadArea.style.background = '#f0fdf4';
        }
    });
</script>
@endsection

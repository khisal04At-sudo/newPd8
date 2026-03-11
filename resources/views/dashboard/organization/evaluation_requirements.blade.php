@extends('layouts.dashboard')

@section('title', 'شروط التقييم والشهادات')

@section('content')
<div style="font-family: 'Cairo', sans-serif; max-width: 900px; margin: 0 auto;">
    <div style="background: white; border-radius: 2rem; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 2.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: white; font-size: 3rem; box-shadow: 0 20px 40px rgba(59, 130, 246, 0.25);">
                <i class="fas fa-file-contract"></i>
            </div>
            <h1 style="font-weight: 950; color: #1e293b; font-size: 2.25rem; margin-bottom: 1rem;">شروط تقييم المستخدمين</h1>
            <p style="color: #64748b; font-size: 1.1rem; line-height: 1.6;">يرجى قراءة الشروط التالية بعناية لضمان إصدار الشهادات بشكل صحيح للمتطوعين والمتدربين.</p>
        </div>

        <div style="display: grid; gap: 2rem;">
            <!-- Condition 1 -->
            <div style="display: flex; gap: 1.5rem; background: #f8fafc; padding: 2rem; border-radius: 1.5rem; border-right: 6px solid #3b82f6; transition: transform 0.2s;" onmouseover="this.style.transform='translateX(-5px)'" onmouseout="this.style.transform='none'">
                <div style="background: white; width: 50px; height: 50px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">1</div>
                <div>
                    <h3 style="font-weight: 850; color: #1e293b; font-size: 1.25rem; margin-bottom: 0.5rem;">وقت التقييم</h3>
                    <p style="color: #475569; line-height: 1.7;">يجب القيام بتقييم المستخدم عند اكتمال الفرصة أو البرنامج التدريبي. يظهر زر التقييم في لوحة التحكم بجانب اسم كل متقدم مقبول.</p>
                </div>
            </div>

            <!-- Condition 2 -->
            <div style="display: flex; gap: 1.5rem; background: #f8fafc; padding: 2rem; border-radius: 1.5rem; border-right: 6px solid #10b981; transition: transform 0.2s;" onmouseover="this.style.transform='translateX(-5px)'" onmouseout="this.style.transform='none'">
                <div style="background: white; width: 50px; height: 50px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">2</div>
                <div>
                    <h3 style="font-weight: 850; color: #1e293b; font-size: 1.25rem; margin-bottom: 0.5rem;">شرط ساعات الحضور</h3>
                    <p style="color: #475569; line-height: 1.7;">للحصول على شهادة معتمدة تلقائياً، يجب أن يحقق المستخدم نسبة حضور لا تقل عن <strong style="color: #10b981;">70%</strong> من إجمالي الساعات التي تم تحديدها للفرصة.</p>
                </div>
            </div>

            <!-- Condition 3 -->
            <div style="display: flex; gap: 1.5rem; background: #f8fafc; padding: 2rem; border-radius: 1.5rem; border-right: 6px solid #f59e0b; transition: transform 0.2s;" onmouseover="this.style.transform='translateX(-5px)'" onmouseout="this.style.transform='none'">
                <div style="background: white; width: 50px; height: 50px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 1.5rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">3</div>
                <div>
                    <h3 style="font-weight: 850; color: #1e293b; font-size: 1.25rem; margin-bottom: 0.5rem;">شرط الالتزام</h3>
                    <p style="color: #475569; line-height: 1.7;">يجب أن يكون تقييم الالتزام الممنوح من قبلكم لا يقل عن <strong style="color: #f59e0b;">3 من أصل 5</strong> لإصدار الشهادة.</p>
                </div>
            </div>
        </div>

        <div style="margin-top: 3rem; background: linear-gradient(135deg, #1e293b, #0f172a); border-radius: 1.5rem; padding: 2rem; color: white; display: flex; align-items: center; gap: 2rem;">
            <div style="flex: 1;">
                <h4 style="font-weight: 800; margin-bottom: 0.5rem; font-size: 1.1rem;">تنبيه هام</h4>
                <p style="margin: 0; font-size: 0.95rem; opacity: 0.8; line-height: 1.6;">بمجرد حفظ التقييم وتحقق الشروط، سيتم توليد شهادة بصيغة PDF بشكل آلي وتظهر في الملف الشخصي للمتطوع.</p>
            </div>
            <a href="{{ route('organization.opportunities.index') }}" style="background: white; color: #1e293b; text-decoration: none; padding: 1rem 2rem; border-radius: 1rem; font-weight: 900; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                اذهب لإدارة الفرص
            </a>
        </div>
    </div>
</div>
@endsection

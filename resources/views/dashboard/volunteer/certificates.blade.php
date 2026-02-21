@extends('layouts.dashboard')

@section('title', 'شهاداتي')

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0;">شهادات الإنجاز</h2>
            <p style="color: #64748b; margin-top: 0.5rem;">هنا تجد جميع الشهادات التي حصلت عليها من خلال مشاركاتك.</p>
        </div>
    </div>

    @if($certificates->isEmpty())
        <div style="background: white; border-radius: 2rem; padding: 5rem 2rem; text-align: center; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="width: 120px; height: 120px; background: #f8fafc; border-radius: 3rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; color: #cbd5e1; font-size: 4rem;">
                <i class="fas fa-certificate"></i>
            </div>
            <h3 style="font-weight: 850; color: #1e293b; font-size: 1.5rem; margin-bottom: 1rem;">لا توجد شهادات حالياً</h3>
            <p style="color: #64748b; max-width: 400px; margin: 0 auto 2rem; line-height: 1.6;">بمجرد إتمامك لفرصة تطوعية أو تدريبية وتقييمك من قبل المؤسسة، ستظهر شهادتك هنا تلقائياً.</p>
            <a href="{{ url('/') }}" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; text-decoration: none; padding: 1rem 2.5rem; border-radius: 1rem; font-weight: 900; display: inline-block; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                تصفح الفرص المتاحة
            </a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
            @foreach($certificates as $cert)
                <div style="background: white; border-radius: 1.5rem; overflow: hidden; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.03); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 12px 30px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.03)'">
                    <div style="height: 10px; background: linear-gradient(90deg, #3b82f6, #10b981);"></div>
                    <div style="padding: 2rem;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                            <div style="width: 60px; height: 60px; background: #f0f9ff; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 1.75rem;">
                                <i class="fas fa-medal"></i>
                            </div>
                            <span style="background: #f1f5f9; color: #64748b; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.8rem; font-weight: 700;">
                                #{{ $cert->certificate_number }}
                            </span>
                        </div>

                        <h3 style="font-weight: 900; color: #1e293b; font-size: 1.25rem; margin-bottom: 0.5rem; line-height: 1.4;">{{ $cert->title }}</h3>
                        <p style="color: #64748b; font-weight: 700; margin-bottom: 1.5rem;">{{ $cert->opportunity_title }}</p>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 2rem; background: #f8fafc; padding: 1rem; border-radius: 1rem;">
                            <div>
                                <span style="display: block; font-size: 0.75rem; color: #94a3b8; font-weight: 800; text-transform: uppercase;">عدد الساعات</span>
                                <span style="font-weight: 800; color: #1e293b;">{{ $cert->attended_hours }} ساعة</span>
                            </div>
                            <div>
                                <span style="display: block; font-size: 0.75rem; color: #94a3b8; font-weight: 800; text-transform: uppercase;">التاريخ</span>
                                <span style="font-weight: 800; color: #1e293b;">{{ $cert->issue_date->format('Y/m/d') }}</span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <a href="{{ route('certificates.download', $cert->id) }}" style="flex: 1; text-align: center; padding: 0.85rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 0.85rem; font-weight: 800; font-size: 0.9rem; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                                <i class="fas fa-download" style="margin-left: 0.5rem;"></i> تحميل الشهادة
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

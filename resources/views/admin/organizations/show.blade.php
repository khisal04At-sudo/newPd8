@extends('layouts.admin')

@section('title', 'تفاصيل المؤسسة')
@section('header', 'مراجعة بيانات المؤسسة')

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Info -->
    <div>
        <div style="background: white; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; margin-bottom: 2rem;">
            <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem;">
                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #94a3b8;">
                    <i class="fas fa-building"></i>
                </div>
                <div>
                    <h2 style="margin: 0; color: #1e293b;">{{ $organization->name }}</h2>
                    <p style="color: #64748b; margin: 0;">{{ $organization->user->email }}</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; color: #64748b; margin-bottom: 0.25rem;">القطاع</label>
                    <div style="font-weight: 600;">{{ $organization->sector }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; color: #64748b; margin-bottom: 0.25rem;">النوع</label>
                    <div style="font-weight: 600;">{{ $organization->organization_type }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; color: #64748b; margin-bottom: 0.25rem;">المدينة</label>
                    <div style="font-weight: 600;">{{ $organization->city->name ?? '-' }}</div>
                </div>
                <div>
                    <label style="display: block; font-size: 0.8rem; color: #64748b; margin-bottom: 0.25rem;">رقم الهاتف</label>
                    <div style="font-weight: 600;">{{ $organization->phone }}</div>
                </div>
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.8rem; color: #64748b; margin-bottom: 0.25rem;">رقم التسجيل</label>
                    <div style="font-weight: 600;">{{ $organization->registration_number ?? 'غير مسجل' }}</div>
                </div>
            </div>
        </div>

        <div style="background: white; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: #1e293b;"><i class="fas fa-file-pdf"></i> المستندات المرفقة</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                @forelse($organization->verificationDocuments as $doc)
                    <a href="{{ asset($doc->file_url) }}" target="_blank" style="display: block; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; text-decoration: none; color: #1e293b; transition: all 0.2s;">
                        <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.5rem;">{{ $doc->file_type }}</div>
                        <div style="font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $doc->file_name }}</div>
                        <div style="margin-top: 1rem; color: #4f46e5; font-size: 0.85rem;">عرض المستند <i class="fas fa-external-link-alt"></i></div>
                    </a>
                @empty
                    <p style="color: #64748b;">لا توجد مستندات مرفقة.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div>
        <div style="background: white; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem; position: sticky; top: 2rem;">
            <h4 style="margin-top: 0; margin-bottom: 1.5rem;">اتخاذ قرار</h4>
            
            @if(session('success'))
                <div style="padding: 1rem; background: #dcfce7; color: #16a34a; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.9rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="padding: 1rem; background: #fee2e2; color: #dc2626; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.9rem;">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Status Badge -->
            @if($organization->status)
                <div style="margin-bottom: 1.5rem; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.9rem;
                    @if($organization->status == 'approved') background: #dcfce7; color: #16a34a;
                    @elseif($organization->status == 'rejected') background: #fee2e2; color: #dc2626;
                    @elseif($organization->status == 'needs_documents') background: #fef3c7; color: #d97706;
                    @else background: #e0f2fe; color: #0369a1; @endif">
                    <strong>الحالة:</strong> 
                    @if($organization->status == 'pending') معلقة
                    @elseif($organization->status == 'approved') معتمدة
                    @elseif($organization->status == 'rejected') مرفوضة
                    @elseif($organization->status == 'needs_documents') تحتاج مستندات إضافية
                    @endif
                </div>
            @endif

            @if(!$organization->verified)
                <!-- Approve -->
                <form action="{{ route('admin.organizations.approve', $organization) }}" method="POST" style="margin-bottom: 1rem;">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 1rem; background: #16a34a; color: white; border: none; border-radius: 0.75rem; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-check-circle"></i> اعتماد المؤسسة
                    </button>
                </form>

                <!-- Request Documents -->
                <details style="margin-bottom: 1rem;">
                    <summary style="padding: 1rem; background: #f59e0b; color: white; border-radius: 0.75rem; font-weight: 700; cursor: pointer; list-style: none; text-align: center;">
                        <i class="fas fa-file-upload"></i> طلب مستندات إضافية
                    </summary>
                    <form action="{{ route('admin.organizations.request-documents', $organization) }}" method="POST" style="padding: 1rem 0;">
                        @csrf
                        <textarea name="requested_documents" placeholder="حدد المستندات المطلوبة بالتفصيل..." style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; margin-bottom: 0.5rem; resize: vertical; min-height: 100px; font-family: inherit;" required></textarea>
                        @error('requested_documents')
                            <p style="color: #dc2626; font-size: 0.85rem; margin-bottom: 0.5rem;">{{ $message }}</p>
                        @enderror
                        <button type="submit" style="width: 100%; padding: 0.75rem; background: #f59e0b; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
                            إرسال الطلب
                        </button>
                    </form>
                </details>

                <!-- Reject -->
                <details>
                    <summary style="padding: 1rem; background: white; color: #dc2626; border: 1px solid #dc2626; border-radius: 0.75rem; font-weight: 700; cursor: pointer; list-style: none; text-align: center;">
                        <i class="fas fa-times-circle"></i> رفض الطلب
                    </summary>
                    <form action="{{ route('admin.organizations.reject', $organization) }}" method="POST" style="padding: 1rem 0;">
                        @csrf
                        <textarea name="reason" placeholder="سبب الرفض..." style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; margin-bottom: 0.5rem; resize: vertical; min-height: 80px; font-family: inherit;" required></textarea>
                        @error('reason')
                            <p style="color: #dc2626; font-size: 0.85rem; margin-bottom: 0.5rem;">{{ $message }}</p>
                        @enderror
                        <button type="submit" style="width: 100%; padding: 0.75rem; background: #dc2626; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
                            تأكيد الرفض
                        </button>
                    </form>
                </details>
            @else
                <div style="padding: 1.5rem; background: #dcfce7; color: #16a34a; border-radius: 0.75rem; text-align: center; margin-bottom: 1.5rem;">
                    <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <p style="margin: 0; font-weight: 600;">تم اعتماد هذه المؤسسة</p>
                </div>

                <!-- Auto Publish Toggle -->
                <div style="padding: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.75rem; margin-bottom: 1.5rem;">
                    <form action="{{ route('admin.organizations.toggle-auto-publish', $organization) }}" method="POST">
                        @csrf
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h5 style="margin: 0; color: #1e293b;">النشر التلقائي</h5>
                                <p style="margin: 0.25rem 0 0; font-size: 0.75rem; color: #64748b;">تجاوز مراجعة الأدمن عند نشر الفرص</p>
                            </div>
                            <button type="submit" style="padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; font-weight: 700; cursor: pointer;
                                {{ $organization->auto_publish_opportunities ? 'background: #dc2626; color: white;' : 'background: #4f46e5; color: white;' }}">
                                {{ $organization->auto_publish_opportunities ? 'إيقاف' : 'تفعيل' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('admin.organizations.index') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem;">
                    <i class="fas fa-arrow-right"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

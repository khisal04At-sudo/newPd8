@extends('layouts.dashboard')
@section('title', 'إدارة الفرص')

@section('content')
<style>
:root{--accent:#6366f1;--accent2:#8b5cf6}
.page-header{background:linear-gradient(135deg, #064e3b 0%, #065f46 55%, #0f766e 100%);border-radius:2rem;padding:2rem 2.5rem;margin-bottom:2rem;position:relative;overflow:hidden;box-shadow: 0 20px 40px -15px rgba(6, 78, 59, 0.3);}
.page-header::before{content:'';position:absolute;top:-50px;right:-50px;width:220px;height:220px;background:radial-gradient(circle,rgba(16, 185, 129, 0.2),transparent 70%);border-radius:50%}
.page-header::after{content:'';position:absolute;bottom:-30px;left:60px;width:150px;height:150px;background:radial-gradient(circle,rgba(20, 184, 166, 0.15),transparent 70%);border-radius:50%}
.glass-card{background:rgba(255,255,255,.97);border:1px solid rgba(255,255,255,.8);border-radius:1.5rem;box-shadow:0 8px 32px rgba(0,0,0,.05);overflow:hidden}
.add-btn{display:inline-flex;align-items:center;gap:.6rem;padding:.8rem 1.6rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;border-radius:1rem;text-decoration:none;font-weight:800;font-size:.88rem;box-shadow:0 8px 20px rgba(99,102,241,.35);transition:all .25s;border:none}
.add-btn:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(99,102,241,.42)}
.opp-row{border-bottom:1px solid #f1f5f9;transition:background .18s;cursor:pointer}
.opp-row:hover td{background:#f5f3ff}
.opp-row td{padding:1.35rem 1.5rem;vertical-align:middle}
.status-pill{display:inline-flex;align-items:center;gap:.45rem;padding:.35rem .95rem;border-radius:2rem;font-weight:800;font-size:.78rem}
.icon-btn{width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;border-radius:.75rem;text-decoration:none;border:none;cursor:pointer;transition:all .2s;font-size:.8rem}
</style>

{{-- Header --}}
<div class="page-header">
    <div style="position:relative;z-index:1;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
        <div>
            <p style="margin:0 0 .3rem;color:rgba(255,255,255,.5);font-size:.8rem;font-weight:700;letter-spacing:.06em">لوحة التحكم</p>
            <h1 style="margin:0;color:white;font-size:1.75rem;font-weight:850">إدارة الفرص</h1>
            <p style="margin:.4rem 0 0;color:rgba(255,255,255,.55);font-size:.88rem">تتبع دورة حياة فرصك التطوعية والتدريبية</p>
        </div>
        @if(auth()->user()->organization->status=='approved')
            <a href="{{ route('organization.opportunities.create') }}" class="add-btn"><i class="fas fa-plus"></i> فرصة جديدة</a>
        @else
            <div style="display:inline-flex;align-items:center;gap:.6rem;padding:.8rem 1.6rem;background:rgba(255,255,255,.1);color:rgba(255,255,255,.5);border-radius:1rem;font-weight:800;font-size:.88rem;border:1px solid rgba(255,255,255,.15);cursor:not-allowed" title="يجب اعتماد المؤسسة أولاً">
                <i class="fas fa-lock"></i> فرصة جديدة (بانتظار الاعتماد)
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div style="background:#ecfdf5;color:#059669;padding:1rem 1.4rem;border-radius:1.25rem;margin-bottom:1.5rem;font-weight:800;border:1px solid #a7f3d0;border-right:4px solid #10b981;display:flex;align-items:center;gap:.75rem">
        <i class="fas fa-check-circle"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fef2f2;color:#dc2626;padding:1rem 1.4rem;border-radius:1.25rem;margin-bottom:1.5rem;font-weight:800;border:1px solid #fecaca;border-right:4px solid #ef4444;display:flex;align-items:center;gap:.75rem">
        <i class="fas fa-exclamation-circle"></i>{{ session('error') }}
    </div>
@endif

<div class="glass-card">
    <table style="width:100%;border-collapse:collapse;text-align:right">
        <thead>
            <tr style="background:#f8fafc;border-bottom:2px solid #f1f5f9">
                <th style="padding:1.25rem 1.5rem;color:#64748b;font-weight:800;font-size:.8rem">الفرصة</th>
                <th style="padding:1.25rem 1.5rem;color:#64748b;font-weight:800;font-size:.8rem">النوع</th>
                <th style="padding:1.25rem 1.5rem;color:#64748b;font-weight:800;font-size:.8rem;text-align:center">المقاعد</th>
                <th style="padding:1.25rem 1.5rem;color:#64748b;font-weight:800;font-size:.8rem">الحالة</th>
                <th style="padding:1.25rem 1.5rem;color:#64748b;font-weight:800;font-size:.8rem">إجراءات</th>
            </tr>
        </thead>
        <tbody>
        @forelse($opportunities as $opp)
            @php
                $accepted = $opp->acceptedApplicationsCount();
                $canEdit  = in_array($opp->status,[\App\Models\Opportunity::STATUS_REVIEW,\App\Models\Opportunity::STATUS_NEEDS_CHANGES]) && $accepted===0;
                $canCancel= in_array($opp->status,[\App\Models\Opportunity::STATUS_PUBLISHED,\App\Models\Opportunity::STATUS_UNDER_IMPLEMENTATION]);
                $detailUrl= route('organization.opportunities.show',$opp);
                $sMap=[0=>['قيد المراجعة','#f59e0b','rgba(245,158,11,.12)','fa-clock'],1=>['منشورة','#10b981','rgba(16,185,129,.12)','fa-check-circle'],2=>['تعديلات','#3b82f6','rgba(59,130,246,.12)','fa-edit'],3=>['مرفوضة','#ef4444','rgba(239,68,68,.12)','fa-times-circle'],4=>['قيد التنفيذ','#8b5cf6','rgba(139,92,246,.12)','fa-rocket'],5=>['مكتملة','#06b6d4','rgba(6,182,212,.12)','fa-check-double'],8=>['ملغاة','#94a3b8','rgba(148,163,184,.12)','fa-ban']];
                $si=$sMap[(int)$opp->status]??$sMap[0];
            @endphp
            <tr class="opp-row" onclick="window.location='{{ $detailUrl }}'">
                <td>
                    <div style="font-weight:850;color:#1e293b;font-size:.95rem;margin-bottom:.25rem">{{ $opp->title }}</div>
                    <div style="font-size:.78rem;color:#94a3b8;display:flex;align-items:center;gap:.4rem">
                        <i class="far fa-calendar-alt"></i>
                        {{ $opp->start_date->format('Y/m/d') }} – {{ $opp->end_date->format('Y/m/d') }}
                    </div>
                </td>
                <td onclick="event.stopPropagation()">
                    <div style="display:flex;flex-direction:column;gap:.35rem">
                        <span style="display:inline-flex;width:fit-content;align-items:center;gap:.4rem;background:{{ $opp->type=='volunteering'?'#ecfdf5':'#eff6ff' }};color:{{ $opp->type=='volunteering'?'#059669':'#2563eb' }};padding:.2rem .7rem;border-radius:.5rem;font-size:.72rem;font-weight:800">
                            <i class="fas {{ $opp->type=='volunteering'?'fa-hand-holding-heart':'fa-graduation-cap' }}" style="font-size:.65rem"></i>
                            {{ $opp->type=='volunteering'?'تطوع':'تدريب' }}
                        </span>
                        <span style="font-size:.75rem;color:#64748b;font-weight:600">{{ $opp->category }}</span>
                    </div>
                </td>
                <td style="text-align:center">
                    @php $pct = $opp->seats > 0 ? min(100,round(($accepted/$opp->seats)*100)) : 0; @endphp
                    <div style="display:inline-flex;flex-direction:column;align-items:center;gap:.35rem;min-width:80px">
                        <span style="font-weight:900;color:#1e293b;font-size:.9rem">{{ $accepted }}<span style="color:#94a3b8;font-weight:700"> / {{ $opp->seats }}</span></span>
                        <div style="width:70px;height:6px;background:#f1f5f9;border-radius:6px;overflow:hidden">
                            <div style="width:{{ $pct }}%;height:100%;background:{{ $pct>=100?'linear-gradient(90deg,#ef4444,#dc2626)':'linear-gradient(90deg,#6366f1,#8b5cf6)' }};border-radius:6px"></div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="status-pill" style="background:{{ $si[2] }};color:{{ $si[1] }}">
                        <i class="fas {{ $si[3] }}" style="font-size:.65rem"></i> {{ $si[0] }}
                    </span>
                </td>
                <td onclick="event.stopPropagation()">
                    <div style="display:flex;gap:.4rem;align-items:center">
                        <a href="{{ $detailUrl }}" class="icon-btn" style="background:#eff6ff;color:#6366f1;border:1px solid rgba(99,102,241,.15)" title="عرض التفاصيل"
                           onmouseover="this.style.background='rgba(99,102,241,.15)'" onmouseout="this.style.background='#eff6ff'">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if($canEdit)
                            <a href="{{ route('organization.opportunities.edit',$opp) }}" class="icon-btn" style="background:white;color:#475569;border:1px solid #e2e8f0" title="تعديل"
                               onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                                <i class="fas fa-edit"></i>
                            </a>
                        @else
                            <div class="icon-btn" style="background:#f8fafc;color:#cbd5e1;border:1px solid #f1f5f9;cursor:not-allowed"
                                 title="{{ $opp->status>2?'الفرصة جارية أو مكتملة':'تم قبول مشاركين' }}">
                                <i class="fas fa-edit"></i>
                            </div>
                        @endif
                        @if($canCancel)
                            <button onclick="openCancelModal({{ $opp->id }},'{{ addslashes($opp->title) }}')"
                                    class="icon-btn" style="background:white;color:#ef4444;border:1px solid #fee2e2" title="إلغاء"
                                    onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                                <i class="fas fa-ban"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="padding:6rem;text-align:center">
                    <div style="width:72px;height:72px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem">
                        <i class="fas fa-folder-open" style="color:#94a3b8;font-size:1.6rem"></i>
                    </div>
                    <h3 style="color:#1e293b;font-weight:850;margin-bottom:.5rem">لا توجد فرص بعد</h3>
                    <p style="color:#64748b;font-size:.9rem;margin-bottom:1.5rem">أضف فرصتك الأولى لتصل إلى المتطوعين</p>
                    @if(auth()->user()->organization->status=='approved')
                        <a href="{{ route('organization.opportunities.create') }}" style="display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;padding:.75rem 1.5rem;border-radius:1rem;text-decoration:none;font-weight:800">
                            <i class="fas fa-plus"></i> إضافة فرصة
                        </a>
                    @endif
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($opportunities->hasPages())
    <div style="margin-top:1.75rem;display:flex;justify-content:center">
        {{ $opportunities->links() }}
    </div>
@endif

{{-- Cancel Modal --}}
<div id="cancelModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:9999;justify-content:center;align-items:center;backdrop-filter:blur(4px)">
    <div style="background:white;padding:2.5rem;border-radius:2rem;max-width:500px;width:90%;box-shadow:0 30px 60px rgba(0,0,0,.3)">
        <div style="width:52px;height:52px;background:linear-gradient(135deg,#ef4444,#dc2626);border-radius:.9rem;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;box-shadow:0 8px 20px rgba(239,68,68,.3)">
            <i class="fas fa-ban" style="color:white;font-size:1.2rem"></i>
        </div>
        <h3 style="margin:0 0 .3rem;color:#1e293b;font-weight:850">إلغاء الفرصة</h3>
        <p style="margin:0 0 1.5rem;color:#64748b;font-size:.88rem" id="cancelTitle"></p>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-right:4px solid #ef4444;border-radius:.75rem;padding:.9rem 1.1rem;margin-bottom:1.5rem">
            <p style="margin:0;color:#991b1b;font-size:.83rem;font-weight:600"><i class="fas fa-exclamation-triangle" style="margin-left:.4rem"></i>سيتم إشعار جميع المتقدمين تلقائياً ولا يمكن التراجع عن هذا الإجراء.</p>
        </div>
        <form id="cancelForm" method="POST">@csrf
            <label style="display:block;margin-bottom:.5rem;color:#1e293b;font-weight:800;font-size:.88rem">سبب الإلغاء <span style="color:#ef4444">*</span></label>
            <textarea name="cancellation_reason" required minlength="10" maxlength="500" rows="4"
                      placeholder="وضّح سبب الإلغاء (10 أحرف على الأقل)..."
                      style="width:100%;border:1.5px solid #e2e8f0;border-radius:.9rem;padding:.8rem;font-family:'Cairo',sans-serif;font-size:.88rem;resize:vertical;outline:none;transition:border .2s;box-sizing:border-box"
                      onfocus="this.style.borderColor='#ef4444'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
            <div style="display:flex;gap:.7rem;justify-content:flex-end;margin-top:1.4rem">
                <button type="button" onclick="document.getElementById('cancelModal').style.display='none'"
                        style="padding:.7rem 1.4rem;background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-radius:.9rem;font-family:'Cairo',sans-serif;font-weight:700;cursor:pointer">
                    تراجع
                </button>
                <button type="submit" style="padding:.7rem 1.4rem;background:linear-gradient(135deg,#ef4444,#dc2626);color:white;border:none;border-radius:.9rem;font-family:'Cairo',sans-serif;font-weight:800;cursor:pointer;box-shadow:0 6px 16px rgba(239,68,68,.3)">
                    <i class="fas fa-ban" style="margin-left:.4rem"></i> تأكيد الإلغاء
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCancelModal(id, title){
    document.getElementById('cancelForm').action = `/organization/opportunities/${id}/cancel`;
    document.getElementById('cancelTitle').textContent = title;
    document.getElementById('cancelModal').style.display = 'flex';
}
document.getElementById('cancelModal')?.addEventListener('click',function(e){if(e.target===this)this.style.display='none';});
document.addEventListener('keydown',e=>{ if(e.key==='Escape') document.getElementById('cancelModal').style.display='none'; });
</script>
@endsection

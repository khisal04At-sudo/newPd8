@extends('layouts.dashboard')
@section('title', $opportunity->title)

@section('content')
@php
    use App\Models\Opportunity;
    $statusMap = [
        0=>['label'=>'قيد المراجعة','color'=>'#f59e0b','bg'=>'rgba(245,158,11,.12)','glow'=>'rgba(245,158,11,.3)','icon'=>'fa-clock'],
        1=>['label'=>'منشورة','color'=>'#10b981','bg'=>'rgba(16,185,129,.12)','glow'=>'rgba(16,185,129,.3)','icon'=>'fa-check-circle'],
        2=>['label'=>'تعديلات مطلوبة','color'=>'#3b82f6','bg'=>'rgba(59,130,246,.12)','glow'=>'rgba(59,130,246,.3)','icon'=>'fa-edit'],
        3=>['label'=>'مرفوضة','color'=>'#ef4444','bg'=>'rgba(239,68,68,.12)','glow'=>'rgba(239,68,68,.3)','icon'=>'fa-times-circle'],
        4=>['label'=>'قيد التنفيذ','color'=>'#8b5cf6','bg'=>'rgba(139,92,246,.12)','glow'=>'rgba(139,92,246,.3)','icon'=>'fa-rocket'],
        5=>['label'=>'مكتملة','color'=>'#06b6d4','bg'=>'rgba(6,182,212,.12)','glow'=>'rgba(6,182,212,.3)','icon'=>'fa-check-double'],
        8=>['label'=>'ملغاة','color'=>'#94a3b8','bg'=>'rgba(148,163,184,.12)','glow'=>'rgba(148,163,184,.3)','icon'=>'fa-ban'],
    ];
    $s        = $statusMap[(int)$opportunity->status] ?? $statusMap[0];
    $isFull   = $opportunity->isFull();
    $canEdit  = in_array($opportunity->status,[Opportunity::STATUS_REVIEW,Opportunity::STATUS_NEEDS_CHANGES]) && $acceptedCount===0;
    $canCancel= in_array($opportunity->status,[Opportunity::STATUS_PUBLISHED,Opportunity::STATUS_UNDER_IMPLEMENTATION]);
    $canPause = $opportunity->status==Opportunity::STATUS_UNDER_IMPLEMENTATION;
    $showEval = $opportunity->status==Opportunity::STATUS_COMPLETED;
@endphp

<style>
:root{--accent:#6366f1;--accent2:#8b5cf6}
*{box-sizing:border-box}
.hero-banner{background:linear-gradient(135deg, #050e0aff 0%, #3a653fff 45%, #335440ff 100%);border-radius:2rem;padding:2.5rem;margin-bottom:2rem;position:relative;overflow:hidden;box-shadow: 0 20px 40px -15px rgba(6, 78, 59, 0.35);}
.hero-banner::before{content:'';position:absolute;top:-60px;right:-60px;width:260px;height:260px;background:radial-gradient(circle,rgba(16, 185, 129, 0.25),transparent 70%);border-radius:50%;}
.hero-banner::after{content:'';position:absolute;bottom:-40px;left:80px;width:180px;height:180px;background:radial-gradient(circle,rgba(20, 184, 166, 0.2),transparent 70%);border-radius:50%;}
.glass-card{background:rgba(255,255,255,.97);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.8);border-radius:1.5rem;box-shadow:0 8px 32px rgba(0,0,0,.06);transition:box-shadow .3s}
.glass-card:hover{box-shadow:0 12px 40px rgba(0,0,0,.1)}
.tab-pill{padding:.7rem 1.4rem;border:none;cursor:pointer;border-radius:12px;font-family:'Cairo',sans-serif;font-weight:800;font-size:.85rem;transition:all .25s;white-space:nowrap;display:inline-flex;align-items:center;gap:.5rem;color:#64748b;background:transparent}
.tab-pill.active{background:white;color:var(--accent);box-shadow:0 4px 16px rgba(99,102,241,.18)}
.tab-pill:hover:not(.active){background:rgba(255,255,255,.6);color:#475569}
.stat-chip{border-radius:1rem;padding:.6rem 1.2rem;display:inline-flex;align-items:center;gap:.5rem;font-weight:800;font-size:.85rem}
.action-btn{display:inline-flex;align-items:center;gap:.55rem;padding:.7rem 1.4rem;border-radius:1rem;font-family:'Cairo',sans-serif;font-weight:800;font-size:.85rem;cursor:pointer;border:none;transition:all .25s;text-decoration:none}
.action-btn:hover{transform:translateY(-2px)}
.btn-primary{background:linear-gradient(135deg,#6366f1,#8b5cf6);color:white;box-shadow:0 6px 20px rgba(99,102,241,.35)}
.btn-primary:hover{box-shadow:0 8px 28px rgba(99,102,241,.45)}
.btn-warn{background:linear-gradient(135deg,#f59e0b,#d97706);color:white;box-shadow:0 6px 20px rgba(245,158,11,.3)}
.btn-danger{background:linear-gradient(135deg,#ef4444,#dc2626);color:white;box-shadow:0 6px 20px rgba(239,68,68,.3)}
.btn-ghost{background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(8px)}
.btn-ghost:hover{background:rgba(255,255,255,.25)}
.info-row{display:flex;justify-content:space-between;align-items:center;padding:.75rem 0;border-bottom:1px solid #f1f5f9}
.info-row:last-child{border-bottom:none}
.progress-bar{height:10px;background:#f1f5f9;border-radius:10px;overflow:hidden}
.progress-fill{height:100%;border-radius:10px;transition:width .8s cubic-bezier(.4,0,.2,1)}
.user-avatar{width:44px;height:44px;border-radius:12px;object-fit:cover;border:2.5px solid white;box-shadow:0 4px 12px rgba(0,0,0,.1)}
.table-modern{width:100%;border-collapse:collapse;text-align:right}
.table-modern th{padding:1.1rem 1.4rem;color:#64748b;font-weight:800;font-size:.8rem;background:#f8fafc;border-bottom:2px solid #f1f5f9}
.table-modern td{padding:1.1rem 1.4rem;border-bottom:1px solid #f8fafc;vertical-align:middle}
.table-modern tr:last-child td{border-bottom:none}
.table-modern tbody tr{transition:background .2s}
.table-modern tbody tr:hover{background:#f8fafc}
.mini-btn{border:none;padding:.4rem .9rem;border-radius:.6rem;font-size:.78rem;font-weight:800;cursor:pointer;font-family:'Cairo',sans-serif;display:inline-flex;align-items:center;gap:.35rem;transition:all .2s}
.badge{display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .8rem;border-radius:2rem;font-size:.75rem;font-weight:800}
.eval-inp{border:1.5px solid #e2e8f0;border-radius:.6rem;padding:.4rem .6rem;font-family:'Cairo',sans-serif;font-weight:700;font-size:.85rem;width:100%;outline:none;transition:border .2s}
.eval-inp:focus{border-color:var(--accent)}
@keyframes fadeUp{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}
.tab-panel{animation:fadeUp .3s ease-out}
.notif-banner{border-radius:1.25rem;padding:1rem 1.4rem;display:flex;align-items:center;gap:.75rem;font-weight:700;margin-bottom:1.25rem;font-size:.88rem}
</style>

{{-- =================== HERO BANNER =================== --}}
<div class="hero-banner">
    <div style="position:relative;z-index:1">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem">
            <div style="display:flex;align-items:center;gap:1rem">
                <a href="{{ route('organization.opportunities.index') }}"
                   style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:12px;color:white;text-decoration:none;backdrop-filter:blur(8px);transition:all .2s"
                   onmouseover="this.style.background='rgba(255,255,255,.22)'" onmouseout="this.style.background='rgba(255,255,255,.12)'">
                    <i class="fas fa-arrow-right"></i>
                </a>
                <div>
                    <p style="margin:0 0 .35rem;color:rgba(255,255,255,.55);font-size:.8rem;font-weight:700;letter-spacing:.05em">إدارة الفرص</p>
                    <h1 style="margin:0;color:white;font-size:1.65rem;font-weight:850;line-height:1.3">{{ $opportunity->title }}</h1>
                </div>
            </div>
            <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-top:.25rem">
                @if($canEdit)
                    <a href="{{ route('organization.opportunities.edit',$opportunity) }}" class="action-btn btn-ghost">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                @endif
                @if($canPause)
                    <form action="{{ route('organization.opportunities.pause',$opportunity) }}" method="POST" onsubmit="return confirm('إيقاف الفرصة مؤقتاً؟')">@csrf
                        <button type="submit" class="action-btn btn-warn"><i class="fas fa-pause-circle"></i> إيقاف مؤقت</button>
                    </form>
                @endif
                @if($canCancel)
                    <button onclick="document.getElementById('cancelModal').style.display='flex'" class="action-btn btn-danger">
                        <i class="fas fa-ban"></i> إلغاء الفرصة
                    </button>
                @endif
            </div>
        </div>

        {{-- Status + stats row --}}
        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:.75rem;margin-top:1.75rem">
            <span class="stat-chip" style="background:{{ $s['bg'] }};color:{{ $s['color'] }};box-shadow:0 0 20px {{ $s['glow'] }}">
                <i class="fas {{ $s['icon'] }}"></i> {{ $s['label'] }}
            </span>
            <span class="stat-chip" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.85)">
                <i class="far fa-calendar-alt"></i> {{ $opportunity->start_date->format('Y/m/d') }} – {{ $opportunity->end_date->format('Y/m/d') }}
            </span>
            <span class="stat-chip" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.85)">
                <i class="fas fa-chair"></i> {{ $acceptedCount }} / {{ $opportunity->seats }} مقعد
            </span>
            <span class="stat-chip" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.85)">
                <i class="far fa-clock"></i> {{ $opportunity->total_hours }} ساعة
            </span>
            <span class="stat-chip" style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.85)">
                <i class="fas {{ $opportunity->type=='volunteering' ? 'fa-hand-holding-heart' : 'fa-graduation-cap' }}"></i>
                {{ $opportunity->type=='volunteering' ? 'تطوع' : 'تدريب' }}
            </span>
        </div>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="notif-banner" style="background:#ecfdf5;color:#059669;border:1px solid #a7f3d0;border-right:4px solid #10b981">
        <i class="fas fa-check-circle" style="font-size:1.1rem"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="notif-banner" style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-right:4px solid #ef4444">
        <i class="fas fa-exclamation-circle" style="font-size:1.1rem"></i>{{ session('error') }}
    </div>
@endif

{{-- =================== TABS =================== --}}
<div style="background:#f1f5f9;padding:.4rem;border-radius:1.25rem;display:flex;gap:.35rem;overflow-x:auto;margin-bottom:1.75rem">
    @foreach(['info'=>['معلومات الفرصة','fa-info-circle'],'applicants'=>['المتقدمون','fa-inbox'],'participants'=>['المشاركون','fa-users'],'evaluation'=>['التقييم','fa-clipboard-check'],'preview'=>['معاينة','fa-eye']] as $key=>[$label,$icon])
        @if($key==='evaluation' && !$showEval) @continue @endif
        <button onclick="switchTab('{{ $key }}')" id="tbtn-{{ $key }}" class="tab-pill">
            <i class="fas {{ $icon }}"></i> {{ $label }}
            @if($key==='applicants' && $pendingApps->count()>0)<span style="background:rgba(99,102,241,.15);color:var(--accent);padding:.05rem .45rem;border-radius:8px;font-size:.7rem">{{ $pendingApps->count() }}</span>@endif
            @if($key==='participants' && $acceptedCount>0)<span style="background:rgba(16,185,129,.15);color:#10b981;padding:.05rem .45rem;border-radius:8px;font-size:.7rem">{{ $acceptedCount }}</span>@endif
        </button>
    @endforeach
</div>

{{-- =================== TAB: INFO =================== --}}
<div id="tab-info" class="tab-panel" style="display:none">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
        <div class="glass-card" style="padding:2rem">
            <h3 style="margin:0 0 1.25rem;color:#1e293b;font-weight:850;font-size:1.05rem;display:flex;align-items:center;gap:.6rem">
                <span style="width:32px;height:32px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(99,102,241,.35)"><i class="fas fa-list" style="color:white;font-size:.75rem"></i></span>
                معلومات أساسية
            </h3>
            @foreach([
                ['النوع',$opportunity->type=='volunteering'?'تطوع 🫶':'تدريب 🎓'],
                ['التصنيف',$opportunity->category.($opportunity->subcategory?' — '.$opportunity->subcategory:'')],
                ['التنفيذ',$opportunity->execution_method=='in_person'?'حضوري 🏢':'عن بُعد 🌐'],
                ['الساعات',$opportunity->total_hours.' ساعة'],
                ['المقاعد',$acceptedCount.' / '.$opportunity->seats],
                ['تاريخ البداية',$opportunity->start_date->format('Y/m/d')],
                ['تاريخ النهاية',$opportunity->end_date->format('Y/m/d')],
                ['آخر تقديم',$opportunity->application_deadline->format('Y/m/d')],
            ] as [$lbl,$val])
                <div class="info-row">
                    <span style="color:#64748b;font-size:.85rem;font-weight:700">{{ $lbl }}</span>
                    <span style="color:#1e293b;font-size:.85rem;font-weight:800">{{ $val }}</span>
                </div>
            @endforeach
        </div>
        <div style="display:flex;flex-direction:column;gap:1.25rem">
            <div class="glass-card" style="padding:1.75rem;flex:1">
                <h3 style="margin:0 0 1rem;color:#1e293b;font-weight:850;font-size:1rem;display:flex;align-items:center;gap:.6rem">
                    <span style="width:30px;height:30px;background:linear-gradient(135deg,#10b981,#059669);border-radius:9px;display:flex;align-items:center;justify-content:center"><i class="fas fa-align-right" style="color:white;font-size:.7rem"></i></span>
                    الوصف
                </h3>
                <p style="color:#475569;font-size:.9rem;line-height:2;margin:0">{{ $opportunity->description }}</p>
            </div>
            @if($opportunity->objectives)
            <div class="glass-card" style="padding:1.75rem">
                <h3 style="margin:0 0 1rem;color:#1e293b;font-weight:850;font-size:1rem;display:flex;align-items:center;gap:.6rem">
                    <span style="width:30px;height:30px;background:linear-gradient(135deg,#f59e0b,#d97706);border-radius:9px;display:flex;align-items:center;justify-content:center"><i class="fas fa-bullseye" style="color:white;font-size:.7rem"></i></span>
                    الأهداف
                </h3>
                <p style="color:#475569;font-size:.9rem;line-height:2;margin:0">{{ $opportunity->objectives }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- =================== TAB: APPLICANTS =================== --}}
<div id="tab-applicants" class="tab-panel">
    {{-- Seat progress card --}}
    <div class="glass-card" style="padding:1.5rem 2rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:2rem;flex-wrap:wrap">
        <div style="flex:1;min-width:200px">
            <div style="display:flex;justify-content:space-between;margin-bottom:.75rem">
                <span style="font-weight:800;color:#1e293b;font-size:.9rem;display:flex;align-items:center;gap:.5rem"><i class="fas fa-chart-pie" style="color:var(--accent)"></i> إشغال المقاعد</span>
                <span style="font-weight:900;font-size:.95rem;color:{{ $seatsPercentage>=100?'#ef4444':'var(--accent)' }}">{{ $seatsPercentage }}%</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $seatsPercentage }}%;background:{{ $seatsPercentage>=100?'linear-gradient(90deg,#ef4444,#dc2626)':'linear-gradient(90deg,#6366f1,#8b5cf6)' }}"></div>
            </div>
        </div>
        <div style="display:flex;gap:2rem">
            @foreach([['المتقدمون',$pendingApps->count(),'#6366f1'],['مقبولون',$acceptedCount,'#10b981'],['متبقية',$remainingSeats,$remainingSeats==0?'#ef4444':'#f59e0b']] as [$lbl,$num,$clr])
            <div style="text-align:center">
                <div style="font-size:1.4rem;font-weight:900;color:{{ $clr }}">{{ $num }}</div>
                <div style="font-size:.72rem;color:#94a3b8;font-weight:700;margin-top:.1rem">{{ $lbl }}</div>
            </div>
            @endforeach
        </div>
    </div>

    @if($isFull)
        <div class="notif-banner" style="background:linear-gradient(135deg,#fef3c7,#fef9ee);color:#92400e;border:1px solid #fde68a;border-right:4px solid #f59e0b">
            <i class="fas fa-exclamation-triangle"></i>
            <span>اكتملت المقاعد — يمكنك إضافة المتقدمين الإضافيين لقائمة الانتظار</span>
        </div>
    @endif

    <div class="glass-card" style="overflow:hidden">
        <table class="table-modern">
            <thead><tr>
                <th>المتقدم</th><th>تاريخ التقديم</th><th>المرفقات</th><th style="text-align:center">الإجراءات</th>
            </tr></thead>
            <tbody>
            @forelse($pendingApps as $app)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.85rem">
                            <img src="{{ $app->user->avatar_url ?? asset('assets/default-avatar.png') }}" class="user-avatar">
                            <div>
                                <a href="{{ route('users.profile',$app->user->id) }}" style="font-weight:800;color:#1e293b;font-size:.9rem;text-decoration:none;transition:color .2s" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#1e293b'">{{ $app->user->name }}</a>
                                <div style="font-size:.75rem;color:#94a3b8">{{ $app->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.82rem;color:#64748b;font-weight:700">{{ $app->created_at->format('Y/m/d') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            @if($app->resumFile)<a href="{{ route('files.view',$app->resumFile) }}" target="_blank" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#eff6ff;color:#3b82f6;border-radius:8px;border:1px solid #dbeafe" title="السيرة"><i class="fas fa-file-pdf" style="font-size:.78rem"></i></a>@endif
                            @if($app->cover_letter)<button onclick="openCL('{{ $app->user->name }}',{{ json_encode($app->cover_letter) }})" style="width:30px;height:30px;display:flex;align-items:center;justify-content:center;background:#fdf4ff;color:#9333ea;border-radius:8px;border:1px solid #e9d5ff;cursor:pointer" title="رسالة التغطية"><i class="fas fa-envelope-open-text" style="font-size:.78rem"></i></button>@endif
                        </div>
                    </td>
                    <td style="text-align:center">
                        <div style="display:flex;gap:.4rem;justify-content:center">
                        @if(!$isFull)
                            <form action="{{ route('organization.applications.updateStatus',$app) }}" method="POST">@csrf<input type="hidden" name="status" value="accepted">
                                <button type="submit" class="mini-btn" style="background:linear-gradient(135deg,#10b981,#059669);color:white;box-shadow:0 4px 12px rgba(16,185,129,.3)"><i class="fas fa-check"></i> قبول</button>
                            </form>
                        @else
                            <button disabled class="mini-btn" style="background:#f1f5f9;color:#94a3b8;cursor:not-allowed" title="المقاعد ممتلئة">قبول</button>
                            <form action="{{ route('organization.applications.waitlist',$app) }}" method="POST">@csrf
                                <button type="submit" class="mini-btn" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;box-shadow:0 4px 12px rgba(245,158,11,.25)"><i class="fas fa-clock"></i> انتظار</button>
                            </form>
                        @endif
                            <form action="{{ route('organization.applications.updateStatus',$app) }}" method="POST">@csrf<input type="hidden" name="status" value="rejected">
                                <button type="submit" class="mini-btn" style="background:#fef2f2;color:#ef4444;border:1px solid #fee2e2"><i class="fas fa-times"></i> رفض</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="padding:4rem;text-align:center">
                    <div style="width:56px;height:56px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem"><i class="fas fa-inbox" style="color:#94a3b8;font-size:1.25rem"></i></div>
                    <div style="color:#64748b;font-weight:700;font-size:.9rem">لا توجد طلبات بانتظار المراجعة</div>
                </td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- =================== TAB: PARTICIPANTS =================== --}}
<div id="tab-participants" class="tab-panel" style="display:none">
    <div class="glass-card" style="overflow:hidden;margin-bottom:1.5rem">
        <div style="padding:1.25rem 1.75rem;background:linear-gradient(135deg,#ecfdf5,#f0fdf4);border-bottom:1px solid #d1fae5;display:flex;align-items:center;gap:.6rem">
            <i class="fas fa-user-check" style="color:#059669;font-size:1rem"></i>
            <span style="font-weight:850;color:#065f46;font-size:.95rem">المشاركون المقبولون ({{ $acceptedCount }})</span>
        </div>
        <table class="table-modern">
            <tbody>
            @forelse($acceptedApps as $app)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.85rem">
                            <img src="{{ $app->user->avatar_url ?? asset('assets/default-avatar.png') }}" class="user-avatar">
                            <div>
                                <a href="{{ route('users.profile',$app->user->id) }}" style="font-weight:800;color:#1e293b;text-decoration:none;font-size:.9rem" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='#1e293b'">{{ $app->user->name }}</a>
                                <div style="font-size:.75rem;color:#94a3b8">{{ $app->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge" style="background:#ecfdf5;color:#059669"><i class="fas fa-check-circle"></i> مقبول</span></td>
                    <td style="font-size:.8rem;color:#94a3b8;font-weight:700">{{ $app->decision_at?->format('Y/m/d') }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="padding:3rem;text-align:center;color:#94a3b8;font-weight:700">لا يوجد مشاركون مقبولون بعد</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($waitlistedApps->count()>0)
    <div class="glass-card" style="overflow:hidden;border:1.5px solid #fde68a">
        <div style="padding:1.25rem 1.75rem;background:linear-gradient(135deg,#fef3c7,#fffbeb);border-bottom:1px solid #fde68a;display:flex;align-items:center;gap:.6rem">
            <i class="fas fa-hourglass-half" style="color:#d97706;font-size:1rem"></i>
            <span style="font-weight:850;color:#92400e;font-size:.95rem">قائمة الانتظار ({{ $waitlistedApps->count() }})</span>
        </div>
        <table class="table-modern">
            <tbody>
            @foreach($waitlistedApps as $app)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.85rem">
                            <img src="{{ $app->user->avatar_url ?? asset('assets/default-avatar.png') }}" class="user-avatar">
                            <div>
                                <div style="font-weight:800;color:#1e293b;font-size:.9rem">{{ $app->user->name }}</div>
                                <div style="font-size:.75rem;color:#94a3b8">{{ $app->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge" style="background:#fef3c7;color:#d97706"><i class="fas fa-clock"></i> انتظار</span></td>
                    <td>
                        <form action="{{ route('organization.applications.promote',$app) }}" method="POST">@csrf
                            <button type="submit" {{ $isFull?'disabled':'' }} class="mini-btn"
                                    style="background:{{ $isFull?'#f1f5f9':'linear-gradient(135deg,#10b981,#059669)' }};color:{{ $isFull?'#94a3b8':'white' }};{{ $isFull?'cursor:not-allowed':'' }};box-shadow:{{ $isFull?'none':'0 4px 12px rgba(16,185,129,.3)' }}"
                                    title="{{ $isFull?'المقاعد ممتلئة':'' }}">
                                <i class="fas fa-arrow-up"></i> ترقية
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

{{-- =================== TAB: EVALUATION =================== --}}
@if($showEval)
<div id="tab-evaluation" class="tab-panel" style="display:none">
    <div class="glass-card" style="overflow:hidden">
        <table class="table-modern">
            <thead><tr>
                <th>المشارك</th><th>الساعات</th><th>الالتزام</th><th>الاسم في الشهادة</th><th>الشهادة</th><th style="text-align:center">إجراءات</th>
            </tr></thead>
            <tbody>
            @forelse($evaluationApps as $app)
                <tr>
                    <td>
                        <form id="ef-{{ $app->id }}" action="{{ route('organization.applications.updateTracking',$app) }}" method="POST">@csrf</form>
                        <div style="display:flex;align-items:center;gap:.7rem">
                            <div style="width:38px;height:38px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:.9rem">{{ mb_substr($app->user->name,0,1) }}</div>
                            <div>
                                <div style="font-weight:800;color:#1e293b;font-size:.88rem">{{ $app->user->name }}</div>
                                <span class="badge" style="background:{{ $app->status=='completed'?'#eff6ff':'#f5f3ff' }};color:{{ $app->status=='completed'?'#3b82f6':'#7c3aed' }};font-size:.68rem;padding:.15rem .55rem">{{ $app->status=='completed'?'مكتمل':'جارٍ' }}</span>
                            </div>
                        </div>
                    </td>
                    <td><div style="display:flex;align-items:center;gap:.4rem"><input type="number" name="attended_hours" form="ef-{{ $app->id }}" value="{{ $app->attended_hours }}" min="0" max="{{ $opportunity->total_hours }}" class="eval-inp" style="width:65px"><span style="font-size:.75rem;color:#94a3b8">/{{ $opportunity->total_hours }}</span></div></td>
                    <td><div style="display:flex;align-items:center;gap:.4rem"><input type="number" name="commitment_score" form="ef-{{ $app->id }}" value="{{ $app->commitment_score }}" min="0" max="100" class="eval-inp" style="width:65px"><span style="font-size:.75rem;color:#94a3b8">%</span></div></td>
                    <td><input type="text" name="certificate_name" form="ef-{{ $app->id }}" value="{{ $app->certificate_name ?: $app->user->name }}" class="eval-inp" style="min-width:140px;color:var(--accent)"></td>
                    <td>
                        @php $cs=$app->certificate_status??'draft';$csM=['draft'=>['قيد الإعداد','#64748b','#f1f5f9'],'under_review'=>['قيد المراجعة','#3b82f6','#eff6ff'],'approved'=>['معتمدة ✅','#16a34a','#f0fdf4'],'rejected'=>['مرفوضة','#dc2626','#fef2f2']]; $csi=$csM[$cs]??$csM['draft']; @endphp
                        <span id="cs-{{ $app->id }}" class="badge" style="background:{{ $csi[2] }};color:{{ $csi[1] }}"><i class="fas fa-circle" style="font-size:.4rem"></i>{{ $csi[0] }}</span>
                        <div id="saved-{{ $app->id }}" style="display:none;color:#16a34a;font-size:.72rem;font-weight:700;margin-top:.2rem"><i class="fas fa-cloud-upload-alt"></i> محفوظ</div>
                    </td>
                    <td style="text-align:center">
                        <div style="display:flex;flex-direction:column;gap:.4rem;align-items:center">
                            <a href="{{ route('organization.applications.certificate.preview',$app) }}" target="_blank" class="mini-btn" style="background:#eff6ff;color:#3b82f6;border:1px solid #dbeafe;text-decoration:none"><i class="fas fa-eye"></i> معاينة</a>
                            @if($cs!=='approved')
                            <div style="display:flex;gap:.3rem">
                                <form action="{{ route('organization.applications.certificate.issue',$app) }}" method="POST">@csrf<button type="submit" class="mini-btn" style="background:linear-gradient(135deg,#10b981,#059669);color:white;box-shadow:0 3px 8px rgba(16,185,129,.3)"><i class="fas fa-check"></i></button></form>
                                <form action="{{ route('organization.applications.certificate.reject',$app) }}" method="POST">@csrf<button type="submit" class="mini-btn" style="background:linear-gradient(135deg,#ef4444,#dc2626);color:white;box-shadow:0 3px 8px rgba(239,68,68,.3)"><i class="fas fa-times"></i></button></form>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="padding:4rem;text-align:center;color:#94a3b8;font-weight:700">لا يوجد مشاركون للتقييم</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- =================== TAB: PREVIEW =================== --}}
<div id="tab-preview" class="tab-panel" style="display:none">
    <div class="glass-card" style="padding:2rem">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
            <h3 style="margin:0;font-weight:850;color:#1e293b">معاينة كما يراها المتطوعون</h3>
            <a href="{{ route('opportunities.show',$opportunity) }}" target="_blank" class="action-btn btn-primary" style="font-size:.82rem;padding:.6rem 1.2rem"><i class="fas fa-external-link-alt"></i> فتح الصفحة</a>
        </div>
        <div style="background:linear-gradient(135deg,#f8faff,#f1f5f9);border-radius:1.25rem;padding:2rem;border:2px dashed #e2e8f0">
            <h2 style="margin:0 0 1rem;color:#1e293b;font-size:1.5rem;font-weight:850">{{ $opportunity->title }}</h2>
            <div style="display:flex;flex-wrap:wrap;gap:.6rem;margin-bottom:1.25rem">
                @foreach([
                    [$opportunity->type=='volunteering'?'تطوع':'تدريب',$opportunity->type=='volunteering'?'#10b981':'#3b82f6'],
                    [$opportunity->category,'#6366f1'],
                    [$opportunity->total_hours.' ساعة','#f59e0b'],
                    [$opportunity->execution_method=='in_person'?'حضوري':'عن بُعد','#8b5cf6'],
                ] as [$t,$c])
                    <span style="display:inline-flex;padding:.3rem .85rem;border-radius:2rem;font-size:.78rem;font-weight:800;background:color-mix(in srgb,{{ $c }} 15%,white);color:{{ $c }}">{{ $t }}</span>
                @endforeach
            </div>
            <p style="color:#475569;line-height:2;font-size:.9rem;margin-bottom:1.5rem">{{ $opportunity->description }}</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
                @foreach([['📅 تاريخ البداية',$opportunity->start_date->format('Y/m/d')],['🏁 تاريخ النهاية',$opportunity->end_date->format('Y/m/d')],['⏰ آخر تقديم',$opportunity->application_deadline->format('Y/m/d')],['💺 المقاعد المتبقية',$remainingSeats.' من '.$opportunity->seats]] as [$lbl,$val])
                    <div style="background:white;border-radius:1rem;padding:.85rem 1.1rem;border:1px solid #e2e8f0">
                        <span style="color:#64748b;font-size:.82rem;font-weight:700">{{ $lbl }}: </span>
                        <span style="color:#1e293b;font-weight:850;font-size:.9rem">{{ $val }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- =================== CANCEL MODAL =================== --}}
<div id="cancelModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:9999;justify-content:center;align-items:center;backdrop-filter:blur(4px)">
    <div style="background:white;padding:2.5rem;border-radius:2rem;max-width:500px;width:90%;box-shadow:0 30px 60px rgba(0,0,0,.3);animation:fadeUp .3s ease-out">
        <div style="width:56px;height:56px;background:linear-gradient(135deg,#ef4444,#dc2626);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin-bottom:1.25rem;box-shadow:0 8px 20px rgba(239,68,68,.35)">
            <i class="fas fa-ban" style="color:white;font-size:1.3rem"></i>
        </div>
        <h3 style="margin:0 0 .5rem;color:#1e293b;font-weight:850;font-size:1.2rem">إلغاء الفرصة</h3>
        <p style="margin:0 0 1.5rem;color:#64748b;font-size:.9rem">{{ $opportunity->title }}</p>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-right:4px solid #ef4444;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem">
            <p style="margin:0;color:#991b1b;font-size:.85rem;font-weight:600"><i class="fas fa-exclamation-triangle" style="margin-left:.5rem"></i>هذا الإجراء لا يمكن التراجع عنه. سيتم إشعار جميع المتقدمين تلقائياً.</p>
        </div>
        <form action="{{ route('organization.opportunities.cancel',$opportunity) }}" method="POST">@csrf
            <label style="display:block;margin-bottom:.5rem;color:#1e293b;font-weight:800;font-size:.9rem">سبب الإلغاء <span style="color:#ef4444">*</span></label>
            <textarea name="cancellation_reason" required minlength="10" maxlength="500" rows="4"
                      placeholder="أدخل سبب الإلغاء بوضوح (10 أحرف على الأقل)..."
                      style="width:100%;border:1.5px solid #e2e8f0;border-radius:.9rem;padding:.85rem;font-family:'Cairo',sans-serif;font-size:.88rem;resize:vertical;outline:none;transition:border .2s"
                      onfocus="this.style.borderColor='#ef4444'" onblur="this.style.borderColor='#e2e8f0'"></textarea>
            <div style="display:flex;gap:.75rem;justify-content:flex-end;margin-top:1.5rem">
                <button type="button" onclick="document.getElementById('cancelModal').style.display='none'"
                        style="padding:.75rem 1.5rem;background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;border-radius:.9rem;font-family:'Cairo',sans-serif;font-weight:700;cursor:pointer">إلغاء</button>
                <button type="submit" class="action-btn btn-danger" style="padding:.75rem 1.5rem"><i class="fas fa-ban"></i> تأكيد الإلغاء</button>
            </div>
        </form>
    </div>
</div>

{{-- Cover Letter Modal --}}
<div id="clModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.6);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px)" onclick="this.style.display='none'">
    <div style="background:white;border-radius:2rem;width:90%;max-width:580px;overflow:hidden;box-shadow:0 30px 60px rgba(0,0,0,.3)" onclick="event.stopPropagation()">
        <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);padding:1.5rem 2rem;display:flex;align-items:center;justify-content:space-between">
            <div style="color:white;font-weight:850;display:flex;align-items:center;gap:.7rem"><i class="fas fa-envelope-open-text"></i> رسالة التغطية — <span id="cl-name" style="font-weight:600;font-size:.9rem"></span></div>
            <button onclick="document.getElementById('clModal').style.display='none'" style="background:rgba(255,255,255,.15);border:none;color:white;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:.95rem"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:2rem;max-height:55vh;overflow-y:auto">
            <div id="cl-content" style="background:#faf5ff;border:1px solid #e9d5ff;border-right:4px solid #8b5cf6;border-radius:1rem;padding:1.5rem;color:#1e293b;font-size:.9rem;line-height:2;white-space:pre-wrap"></div>
        </div>
    </div>
</div>

<script>
const allTabs=['info','applicants','participants','evaluation','preview'];
function switchTab(key){
    allTabs.forEach(t=>{
        const p=document.getElementById('tab-'+t);
        const b=document.getElementById('tbtn-'+t);
        if(p) p.style.display = t===key ? 'block':'none';
        if(b){ b.classList.toggle('active',t===key); }
    });
    localStorage.setItem('lastOppTab', key);
}
function openCL(name,content){
    document.getElementById('cl-name').textContent=name;
    document.getElementById('cl-content').textContent=content;
    document.getElementById('clModal').style.display='flex';
}
document.addEventListener('keydown',e=>{ if(e.key==='Escape'){ document.getElementById('cancelModal').style.display='none'; document.getElementById('clModal').style.display='none'; } });

// Auto-save evaluation
document.addEventListener('DOMContentLoaded',()=>{
    const timers={};
    document.querySelectorAll('.eval-inp').forEach(inp=>{
        inp.addEventListener('input',function(){
            const fid=this.getAttribute('form');
            const aid=fid.split('-')[1];
            clearTimeout(timers[aid]);
            timers[aid]=setTimeout(()=>autoSave(aid),1200);
        });
    });
    async function autoSave(aid){
        const form=document.getElementById('ef-'+aid);
        const fd=new FormData(form);
        document.querySelectorAll(`[form="ef-${aid}"]`).forEach(i=>{if(i.name)fd.set(i.name,i.value);});
        try{
            const r=await fetch(form.action,{method:'POST',body:fd,headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
            const d=await r.json();
            if(d.success){const el=document.getElementById('saved-'+aid);el.style.display='block';setTimeout(()=>el.style.display='none',2500);}
        }catch(e){console.error(e);}
    }
    // Restore last tab or set default
    const lastTab = localStorage.getItem('lastOppTab') || 'applicants';
    if(allTabs.includes(lastTab)){
        switchTab(lastTab);
    } else {
        switchTab('applicants');
    }
});
</script>
@endsection

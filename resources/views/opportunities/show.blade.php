@extends('layouts.app')

@section('title', $opportunity->title)

@section('content')
<div style="background: #f8fafc; min-height: 100vh; padding: 3rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Breadcrumb / Back -->
        <a href="{{ route('opportunities.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 600; margin-bottom: 2rem; transition: color 0.2s;" onmouseover="this.style.color='#4f46e5'" onmouseout="this.style.color='#64748b'">
            <i class="fas fa-arrow-right"></i> العودة لتصفح الفرص
        </a>

        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem;">
            
            <!-- Main Content -->
            <div>
                <div style="background: white; border-radius: 2rem; padding: 3rem; box-shadow: 0 4px 25px rgba(0,0,0,0.03); margin-bottom: 2.5rem; border: 1px solid #f1f5f9;">
                    
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        <span style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; padding: 0.5rem 1.25rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 700;">
                            {{ $opportunity->type == 'volunteering' ? 'فرصة تطوعية' : 'فرصة تدريبية' }}
                        </span>
                        <span style="background: #f1f5f9; color: #475569; padding: 0.5rem 1.25rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 700;">
                            {{ $opportunity->category }}
                        </span>
                    </div>

                    <h1 style="font-size: 2.5rem; font-weight: 850; color: #1e293b; margin-bottom: 2rem; line-height: 1.2;">{{ $opportunity->title }}</h1>

                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 3rem; padding-bottom: 2.5rem; border-bottom: 1px solid #f1f5f9;">
                        <img src="{{ url($opportunity->organization->logo_url ?? 'assets/default-logo.png') }}" style="width: 3.5rem; height: 3.5rem; border-radius: 0.75rem; object-fit: cover;">
                        <div>
                            <div style="font-weight: 700; color: #1e293b;">تنظيم: {{ $opportunity->organization->name }}</div>
                            <div style="font-size: 0.9rem; color: #64748b;">مؤسسة معتمدة في المنصة</div>
                        </div>
                    </div>

                    <!-- Long Description & Details -->
                    <div style="margin-bottom: 3rem;">
                        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1e293b;">حول هذه الفرصة</h2>
                        <div style="color: #475569; line-height: 1.8; font-size: 1.1rem; white-space: pre-line;">
                            {{ $opportunity->description }}
                        </div>
                    </div>

                    @if($opportunity->objectives)
                    <div style="margin-bottom: 3rem;">
                        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1e293b;">أهداف البرنامج</h2>
                        <div style="color: #475569; line-height: 1.8; font-size: 1.1rem; white-space: pre-line;">{{ $opportunity->objectives }}</div>
                    </div>
                    @endif

                    @if($opportunity->tasks)
                    <div style="margin-bottom: 3rem;">
                        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1e293b;">المهام والمسؤوليات</h2>
                        <div style="color: #475569; line-height: 1.8; font-size: 1.1rem; white-space: pre-line;">{{ $opportunity->tasks }}</div>
                    </div>
                    @endif

                    @if($opportunity->training_outcomes && $opportunity->type == 'training')
                    <div style="margin-bottom: 3rem;">
                        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: #1e293b;">مخرجات التدريب</h2>
                        <div style="color: #475569; line-height: 1.8; font-size: 1.1rem; white-space: pre-line;">{{ $opportunity->training_outcomes }}</div>
                    </div>
                    @endif

                    <!-- Applicant List (Visible ONLY to the Org Owner of this specific opportunity) -->
                    @auth
                        @if(auth()->user()->organization && auth()->user()->organization->id === $opportunity->organization_id)
                            <div style="margin-top: 5rem; padding-top: 3rem; border-top: 2px dashed #e2e8f0;">
                                <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 2rem;">
                                    <i class="fas fa-users-cog" style="color: #4f46e5; margin-left: 0.5rem;"></i> قائمة المتقدمين (إدارة المؤسسة)
                                </h2>
                                
                                @if($opportunity->applications->count() > 0)
                                    <div style="background: #f8fafc; border-radius: 1rem; overflow: hidden; border: 1px solid #e2e8f0;">
                                        <table style="width: 100%; border-collapse: collapse; text-align: right;">
                                            <thead>
                                                <tr style="background: #f1f5f9; color: #475569; font-weight: 700;">
                                                    <th style="padding: 1rem;">المتطوع</th>
                                                    <th style="padding: 1rem;">تاريخ التقديم</th>
                                                    <th style="padding: 1rem;">الحالة</th>
                                                    <th style="padding: 1rem;">الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($opportunity->applications as $app)
                                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                                    <td style="padding: 1rem;">{{ $app->user->name }}</td>
                                                    <td style="padding: 1rem;">{{ $app->created_at->format('Y/m/d') }}</td>
                                                    <td style="padding: 1rem;">
                                                        <span style="padding: 0.3rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 700; 
                                                                    {{ $app->status == 'pending' ? 'background:#fef3c7;color:#d97706;' : '' }}
                                                                    {{ $app->status == 'accepted' ? 'background:#dcfce7;color:#16a34a;' : '' }}
                                                                    {{ $app->status == 'rejected' ? 'background:#fee2e2;color:#dc2626;' : '' }}">
                                                            {{ $app->status }}
                                                        </span>
                                                    </td>
                                                    <td style="padding: 1rem;">
                                                        <a href="{{ route('organization.applications.index') }}" style="color: #4f46e5; font-weight: 600; text-decoration: none;">إدارة</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p style="color: #64748b; font-style: italic;">لا يوجد متقدمون لهذه الفرصة حتى الآن.</p>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Sidebar Actions & Stats -->
            <div style="position: sticky; top: 6rem; height: fit-content;">
                
                @if(session('success'))
                    <div style="background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-weight: 600;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 1rem; margin-bottom: 1.5rem; font-weight: 600;">
                        {{ session('error') }}
                    </div>
                @endif

                <div style="background: white; border-radius: 2rem; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; margin-bottom: 2rem;">
                    
                    <!-- Main Action -->
                    <form action="{{ route('opportunities.apply', $opportunity) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 1.25rem; background: #4f46e5; color: white; border: none; border-radius: 1rem; font-size: 1.1rem; font-weight: 700; cursor: pointer; margin-bottom: 1rem; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);">
                            تقديم الطلب الآن
                        </button>
                    </form>
                    
                    <div style="display: flex; gap: 1rem;">
                        <form action="{{ route('opportunities.save', $opportunity) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 1rem; background: white; border: 1px solid #e2e8f0; color: #475569; border-radius: 1rem; font-weight: 650; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#4f46e5';this.style.color='#4f46e5'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                                <i class="far fa-bookmark" style="margin-left: 0.5rem;"></i> حفظ
                            </button>
                        </form>
                        <form action="{{ route('opportunities.share', $opportunity) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 1rem; background: white; border: 1px solid #e2e8f0; color: #475569; border-radius: 1rem; font-weight: 650; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#4f46e5';this.style.color='#4f46e5'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                                <i class="fas fa-share-alt" style="margin-left: 0.5rem;"></i> مشاركة
                            </button>
                        </form>
                    </div>

                    <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid #f1f5f9;">
                        <h3 style="font-size: 1.1rem; font-weight: 750; color: #1e293b; margin-bottom: 1.5rem;">تفاصيل لوجستية</h3>
                        
                        <div style="display: grid; gap: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                                <span style="color: #64748b;">تاريخ البداية:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->start_date->format('Y/m/d') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                                <span style="color: #64748b;">تاريخ النهاية:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->end_date->format('Y/m/d') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                                <span style="color: #64748b;">ساعات العمل اليومية:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->daily_hours ?? '--' }} ساعات</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                                <span style="color: #64748b;">المدينة:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->city->name }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                                <span style="color: #64748b;">المقاعد المتاحة:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->seats }}</span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid #f1f5f9;">
                        <h3 style="font-size: 1.1rem; font-weight: 750; color: #1e293b; margin-bottom: 1.5rem;">المميزات والمتطلبات</h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            @if($opportunity->requires_certification)
                                <span style="background: #dcfce7; color: #16a34a; padding: 0.3rem 0.6rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700;">شهادة معتمدة</span>
                            @endif
                            @if($opportunity->has_stipend)
                                <span style="background: #e0f2fe; color: #0369a1; padding: 0.3rem 0.6rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700;">بدل مالي</span>
                            @endif
                            @if($opportunity->is_practical)
                                <span style="background: #fef3c7; color: #d97706; padding: 0.3rem 0.6rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 700;">تدريب عملي</span>
                            @endif
                        </div>
                    </div>

                    @if($opportunity->contact_name)
                    <div style="margin-top: 2.5rem; padding: 1.5rem; background: #f1f5f9; border-radius: 1rem;">
                        <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.75rem;">مسؤول التواصل</h4>
                        <div style="font-size: 0.95rem; color: #475569;">{{ $opportunity->contact_name }}</div>
                        <div style="font-size: 0.9rem; color: #64748b;">{{ $opportunity->contact_info }}</div>
                    </div>
                    @endif
                </div>

                <!-- Related/Simlar -->
                <div>
                   <h3 style="font-size: 1.25rem; font-weight: 750; color: #1e293b; margin-bottom: 1.5rem;">فرص مشابهة</h3>
                   @foreach($relatedOpportunities as $rel)
                    <div style="background: white; border-radius: 1rem; padding: 1rem; margin-bottom: 1rem; border: 1px solid #f1f5f9;">
                        <a href="{{ route('opportunities.show', $rel) }}" style="text-decoration: none; color: #1e293b; font-weight: 650; font-size: 0.95rem;">{{ $rel->title }}</a>
                    </div>
                   @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

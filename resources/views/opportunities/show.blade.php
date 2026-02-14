@extends('layouts.app')

@section('title', $opportunity->title)

@section('content')
<div style="background: #f8fafc; min-height: 100vh; padding: 3rem 0; font-family: 'Cairo', sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Breadcrumb / Back -->
        <a href="{{ route('opportunities.index') }}" style="display: inline-flex; align-items: center; gap: 0.75rem; text-decoration: none; color: #64748b; font-weight: 700; margin-bottom: 2rem; transition: all 0.3s; padding: 0.5rem 1rem; border-radius: 1rem; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.02);" onmouseover="this.style.color='#3b82f6'; this.style.transform='translateX(5px)'" onmouseout="this.style.color='#64748b'; this.style.transform='none'">
            <i class="fas fa-arrow-right"></i> العودة لتصفح الفرص
        </a>

        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem;">
            
            <!-- Main Content -->
            <div>
                <div style="background: white; border-radius: 2rem; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 2.5rem; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
                    <!-- Decorative Background -->
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #3b82f6, #10b981);"></div>
                    
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
                        @php
                            $statusLabel = match($opportunity->status) {
                                1 => 'متاحة للتقديم',
                                4 => 'قيد التنفيذ',
                                5 => 'مكتملة',
                                8 => 'ملغاة',
                                9 => 'مغلقة',
                                default => 'غير معروف'
                            };
                            $statusColor = match($opportunity->status) {
                                1 => '#16a34a',
                                4 => '#2563eb',
                                5 => '#475569',
                                8 => '#dc2626',
                                9 => '#d97706',
                                default => '#64748b'
                            };
                        @endphp
                        <span style="background: {{ $statusColor }}10; color: {{ $statusColor }}; padding: 0.6rem 1.5rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 800; border: 1px solid {{ $statusColor }}30;">
                            <i class="fas fa-circle" style="margin-left: 0.5rem; font-size: 0.6rem;"></i>
                            {{ $statusLabel }}
                        </span>
                        <span style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 0.6rem 1.5rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 800; border: 1px solid rgba(59, 130, 246, 0.1);">
                            <i class="{{ $opportunity->type == 'volunteering' ? 'fas fa-hand-holding-heart' : 'fas fa-graduation-cap' }}" style="margin-left: 0.5rem;"></i>
                            {{ $opportunity->type == 'volunteering' ? 'فرصة تطوعية' : 'فرصة تدريبية' }}
                        </span>
                        <span style="background: #f1f5f9; color: #475569; padding: 0.6rem 1.5rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 800;">
                            <i class="fas fa-tag" style="margin-left: 0.5rem;"></i>
                            {{ $opportunity->category }} @if($opportunity->subcategory) - {{ $opportunity->subcategory }} @endif
                        </span>
                        @if($opportunity->execution_method == 'remote')
                        <span style="background: #ecfdf5; color: #10b981; padding: 0.6rem 1.5rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 800; border: 1px solid #d1fae5;">
                            <i class="fas fa-laptop-house" style="margin-left: 0.5rem;"></i>
                            عن بُعد
                        </span>
                        @endif
                    </div>

                    <h1 style="font-size: 2.75rem; font-weight: 900; color: #1e293b; margin-bottom: 2rem; line-height: 1.3; letter-spacing: -0.5px;">{{ $opportunity->title }}</h1>

                    <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 3rem; padding-bottom: 2.5rem; border-bottom: 1px solid #f1f5f9;">
                        <div style="position: relative;">
                            <img src="{{ url($opportunity->organization->logo_url ?? 'assets/default-logo.png') }}" style="width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 2px solid white;">
                            <div style="position: absolute; bottom: -5px; right: -5px; background: #10b981; color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; border: 2px solid white;">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: #1e293b; font-size: 1.1rem;">
                                تنظيم: <a href="{{ route('organizations.profile', $opportunity->organization) }}" style="color: var(--brand-blue); text-decoration: none; transition: all 0.2s;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">{{ $opportunity->organization->name }}</a>
                            </div>
                            <div style="font-size: 0.9rem; color: #64748b; margin-top: 0.2rem;">مؤسسة معتمدة في المنصة • عضو منذ {{ $opportunity->organization->created_at->format('Y') }}</div>
                        </div>
                    </div>

                    <!-- Quick Overview Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 3.5rem;">
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid #f1f5f9;">
                            <div style="color: #64748b; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">طريقة التنفيذ</div>
                            <div style="color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; font-size: 1.05rem;">
                                <i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i>
                                {{ $opportunity->execution_method == 'in_person' ? 'حضوري' : 'عن بُعد' }}
                            </div>
                        </div>
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid #f1f5f9;">
                            <div style="color: #64748b; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">تاريخ الانتهاء</div>
                            <div style="color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; font-size: 1.05rem;">
                                <i class="fas fa-calendar-alt" style="color: #3b82f6;"></i>
                                {{ $opportunity->end_date->format('Y/m/d') }}
                            </div>
                        </div>
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid #f1f5f9;">
                            <div style="color: #64748b; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">المقاعد المتاحة</div>
                            <div style="color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; font-size: 1.05rem;">
                                <i class="fas fa-users" style="color: #3b82f6;"></i>
                                {{ $opportunity->seats }} مقعد
                            </div>
                        </div>
                        <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1.25rem; border: 1px solid #f1f5f9;">
                            <div style="color: #64748b; font-size: 0.8rem; font-weight: 700; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px;">الساعات المعتمدة</div>
                            <div style="color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 0.5rem; font-size: 1.05rem;">
                                <i class="fas fa-clock" style="color: #3b82f6;"></i>
                                {{ $opportunity->total_hours }} ساعة
                            </div>
                        </div>
                    </div>

                    <!-- Long Description & Details -->
                    <div style="margin-bottom: 3.5rem;">
                        <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="width: 8px; height: 30px; background: #3b82f6; border-radius: 4px;"></span>
                            الوصف الوظيفي
                        </h2>
                        <div style="color: #475569; line-height: 2; font-size: 1.1rem; white-space: pre-line;">
                            {{ $opportunity->description }}
                        </div>
                    </div>

                    @if($opportunity->objectives)
                    <div style="margin-bottom: 3.5rem;">
                        <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="width: 8px; height: 30px; background: #10b981; border-radius: 4px;"></span>
                            أهداف البرنامج
                        </h2>
                        <div style="color: #475569; line-height: 2; font-size: 1.1rem; white-space: pre-line;">{{ $opportunity->objectives }}</div>
                    </div>
                    @endif

                    @if($opportunity->tasks)
                    <div style="margin-bottom: 3.5rem;">
                        <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="width: 8px; height: 30px; background: #f59e0b; border-radius: 4px;"></span>
                            المهام والمسؤوليات
                        </h2>
                        <div style="color: #475569; line-height: 2; font-size: 1.1rem; white-space: pre-line;">{{ $opportunity->tasks }}</div>
                    </div>
                    @endif

                    @if($opportunity->training_outcomes && $opportunity->type == 'training')
                    <div style="margin-bottom: 3.5rem;">
                        <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="width: 8px; height: 30px; background: #8b5cf6; border-radius: 4px;"></span>
                            مخرجات التدريب
                        </h2>
                        <div style="color: #475569; line-height: 2; font-size: 1.1rem; white-space: pre-line;">{{ $opportunity->training_outcomes }}</div>
                    </div>
                    @endif

                    <!-- Applicant List (Visible ONLY to the Org Owner) -->
                    @auth
                        @if(auth()->user()->organization && auth()->user()->organization->id === $opportunity->organization_id)
                            <div style="margin-top: 5rem; padding-top: 4rem; border-top: 2px dashed #e2e8f0;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
                                    <h2 style="font-size: 1.75rem; font-weight: 900; color: #1e293b;">
                                        <i class="fas fa-users-cog" style="color: #3b82f6; margin-left: 0.75rem;"></i> إدارة المتقدمين
                                    </h2>
                                    <span style="background: #3b82f6; color: white; padding: 0.5rem 1.25rem; border-radius: 2rem; font-weight: 800; font-size: 0.9rem;">
                                        إجمالي الطلبات: {{ $opportunity->applications->count() }}
                                    </span>
                                </div>
                                
                                @if($opportunity->applications->count() > 0)
                                    <div style="background: #f8fafc; border-radius: 1.5rem; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                                        <table style="width: 100%; border-collapse: collapse; text-align: right;">
                                            <thead>
                                                <tr style="background: #f1f5f9; color: #475569; font-weight: 800;">
                                                    <th style="padding: 1.25rem;">المتطوع</th>
                                                    <th style="padding: 1.25rem;">تاريخ التقديم</th>
                                                    <th style="padding: 1.25rem;">الحالة</th>
                                                    <th style="padding: 1.25rem;">الإجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($opportunity->applications as $app)
                                                <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;" onmouseover="this.style.background='white'" onmouseout="this.style.background='transparent'">
                                                    <td style="padding: 1.25rem; font-weight: 700; color: #1e293b;">{{ $app->user->name }}</td>
                                                    <td style="padding: 1.25rem; color: #64748b;">{{ $app->created_at->format('Y/m/d') }}</td>
                                                    <td style="padding: 1.25rem;">
                                                        <span style="padding: 0.5rem 1.2rem; border-radius: 1rem; font-size: 0.85rem; font-weight: 800; 
                                                                    {{ $app->status == 'pending' ? 'background:#fef3c7;color:#d97706;border:1px solid #fcd34d;' : '' }}
                                                                    {{ $app->status == 'accepted' ? 'background:#dcfce7;color:#16a34a;border:1px solid #86efac;' : '' }}
                                                                    {{ $app->status == 'rejected' ? 'background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;' : '' }}">
                                                            @if($app->status == 'pending') <i class="fas fa-clock" style="margin-left:0.3rem"></i> قيد المراجعة 
                                                            @elseif($app->status == 'accepted') <i class="fas fa-check-circle" style="margin-left:0.3rem"></i> مقبول 
                                                            @else <i class="fas fa-times-circle" style="margin-left:0.3rem"></i> مرفوض @endif
                                                        </span>
                                                    </td>
                                                    <td style="padding: 1.25rem;">
                                                        <a href="{{ route('organization.applications.index') }}" style="color: #3b82f6; font-weight: 800; text-decoration: none; border: 1px solid #3b82f6; padding: 0.4rem 1rem; border-radius: 0.75rem; font-size: 0.85rem; transition: all 0.2s;" onmouseover="this.style.background='#3b82f6'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='#3b82f6'">تفاصيل الطلب</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div style="text-align: center; padding: 4rem; background: #f8fafc; border-radius: 1.5rem; border: 2px dashed #e2e8f0;">
                                        <i class="fas fa-user-friends" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1.5rem;"></i>
                                        <p style="color: #64748b; font-weight: 700; font-size: 1.1rem;">لا يوجد متقدمون لهذه الفرصة حتى الآن.</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Sidebar Actions & Stats -->
            <div style="position: sticky; top: 6rem; height: fit-content;">
                
                @if(session('success'))
                    <div style="background: #ecfdf5; color: #10b981; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 1.5rem; font-weight: 800; border: 1px solid #d1fae5; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="background: #fef2f2; color: #dc2626; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 1.5rem; font-weight: 800; border: 1px solid #fee2e2; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <div style="background: white; border-radius: 2rem; padding: 2.5rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; margin-bottom: 2rem;">
                    
                    <!-- Application Logic -->
                    @php
                        $deadline = \Carbon\Carbon::parse($opportunity->application_deadline);
                        $isClosed = $deadline->isPast();
                        $daysLeft = $deadline->diffInDays(now());
                    @endphp

                    @php
                        $isApplicationClosed = $isClosed || $opportunity->status != 1;
                        $closeReason = $isClosed ? 'انتهى موعد التقديم' : ($opportunity->status == 8 ? 'هذه الفرصة تم إلغاؤها' : 'التقديم لهذه الفرصة مغلق حالياً');
                    @endphp

                    @if($isApplicationClosed)
                        <div style="background: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 1rem; text-align: center; font-weight: 800; margin-bottom: 1.5rem;">
                            <i class="fas fa-lock" style="margin-left: 0.5rem;"></i> {{ $closeReason }}
                        </div>
                    @else
                        <div style="background: #fffbeb; color: #b45309; padding: 1rem; border-radius: 1rem; text-align: center; font-weight: 800; margin-bottom: 1.5rem; font-size: 0.9rem;">
                            <i class="fas fa-hourglass-half" style="margin-left: 0.5rem;"></i> متبقي {{ $daysLeft }} أيام للتقديم
                        </div>
                        
                        <a href="{{ route('applications.create', $opportunity) }}" style="display: block; width: 100%; padding: 1.25rem; background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; border: none; border-radius: 1.25rem; font-size: 1.15rem; font-weight: 800; cursor: pointer; margin-bottom: 1rem; transition: all 0.3s; box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4); text-decoration: none; text-align: center;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 25px -5px rgba(59, 130, 246, 0.5)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 20px -5px rgba(59, 130, 246, 0.4)'">
                            تقديم الطلب الآن
                        </a>
                    @endif
                    
                    <div style="display: flex; gap: 1rem;">
                        <form action="{{ route('volunteer.opportunities.save', $opportunity) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 1.25rem; font-weight: 750; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'; this.style.background='white'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#475569'; this.style.background='#f8fafc'">
                                <i class="far fa-bookmark"></i> حفظ
                            </button>
                        </form>
                        <form action="{{ route('opportunities.share', $opportunity) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" style="width: 100%; padding: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; color: #475569; border-radius: 1.25rem; font-weight: 750; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.5rem;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'; this.style.background='white'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#475569'; this.style.background='#f8fafc'">
                                <i class="fas fa-share-alt"></i> مشاركة
                            </button>
                        </form>
                    </div>

                    <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #f1f5f9;">
                        <h3 style="font-size: 1.2rem; font-weight: 850; color: #1e293b; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-info-circle" style="color: #3b82f6;"></i> معلومات إضافية
                        </h3>
                        
                        <div style="display: grid; gap: 1.5rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem;">
                                <span style="color: #64748b; font-weight: 600;">تاريخ البداية</span>
                                <span style="font-weight: 800; color: #1e293b;">{{ $opportunity->start_date->format('Y/m/d') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem;">
                                <span style="color: #64748b; font-weight: 600;">تاريخ النهاية</span>
                                <span style="font-weight: 800; color: #1e293b;">{{ $opportunity->end_date->format('Y/m/d') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem;">
                                <span style="color: #64748b; font-weight: 600;">أخر موعد للتقديم</span>
                                <span style="font-weight: 800; color: #ef4444;">{{ $deadline->format('Y/m/d') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem;">
                                <span style="color: #64748b; font-weight: 600;">المدينة</span>
                                <span style="font-weight: 800; color: #1e293b;">{{ $opportunity->city->name ?? 'عن بُعد' }}</span>
                            </div>
                            @if($opportunity->education_level)
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem;">
                                <span style="color: #64748b; font-weight: 600;">المؤهل المطلوب</span>
                                <span style="font-weight: 800; color: #1e293b;">{{ $opportunity->education_level }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #f1f5f9;">
                        <h3 style="font-size: 1.2rem; font-weight: 850; color: #1e293b; margin-bottom: 1.5rem;">المميزات والمتطلبات</h3>
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                            @if($opportunity->provides_certificate)
                                <span style="background: #ecfdf5; color: #059669; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; border: 1px solid #d1fae5;">شهادة معتمدة</span>
                            @endif
                            @if($opportunity->has_stipend || $opportunity->is_paid == 'yes')
                                <span style="background: #eff6ff; color: #2563eb; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; border: 1px solid #dbeafe;">مقابل مالي</span>
                            @endif
                            @if($opportunity->is_certified == 'yes')
                                <span style="background: #fff7ed; color: #d97706; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; border: 1px solid #ffedd5;">برنامج معتمد</span>
                            @endif
                            @if($opportunity->requires_cover_letter)
                                <span style="background: #fdf2f8; color: #db2777; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; border: 1px solid #fce7f3;">رسالة تغطية</span>
                            @endif
                        </div>
                    </div>

                    @if($opportunity->contact_name)
                    <div style="margin-top: 3rem; padding: 2rem; background: #f8fafc; border-radius: 1.5rem; border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
                        <div style="position: absolute; top:0; left:0; width: 4px; height: 100%; background: #3b82f6;"></div>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">مسؤول التواصل</h4>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div style="width: 40px; height: 40px; background: #e0f2fe; color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b;">{{ $opportunity->contact_name }}</div>
                                <div style="font-size: 0.9rem; color: #64748b;">{{ $opportunity->contact_info }}</div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Similar Opportunities -->
                @if(isset($relatedOpportunities) && $relatedOpportunities->count() > 0)
                <div style="background: white; border-radius: 2rem; padding: 2.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                   <h3 style="font-size: 1.3rem; font-weight: 900; color: #1e293b; margin-bottom: 1.75rem; display: flex; align-items: center; gap: 0.75rem;">
                       <i class="fas fa-lightbulb" style="color: #f59e0b;"></i> قد يهمك أيضاً
                   </h3>
                   @foreach($relatedOpportunities as $rel)
                    <a href="{{ route('opportunities.show', $rel) }}" style="display: flex; gap: 1rem; align-items: center; text-decoration: none; padding: 1rem; border-radius: 1.25rem; transition: background 0.3s; border: 1px solid transparent; margin-bottom: 0.75rem;" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#f1f5f9'" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent'">
                        <img src="{{ url($rel->organization->logo_url ?? 'assets/default-logo.png') }}" style="width: 3rem; height: 3rem; border-radius: 1rem; object-fit: cover;">
                        <div style="overflow: hidden;">
                            <div style="color: #1e293b; font-weight: 800; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $rel->title }}</div>
                            <div style="color: #64748b; font-size: 0.85rem; font-weight: 600;">{{ $rel->organization->name }}</div>
                        </div>
                    </a>
                   @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap');
    
    body {
        font-family: 'Cairo', sans-serif;
    }

    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endsection

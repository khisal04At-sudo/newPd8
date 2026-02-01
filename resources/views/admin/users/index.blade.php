@extends('layouts.admin')

@section('title', 'إدارة المستخدمين')

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; color: #1e293b; font-weight: 850; font-size: 1.75rem;">إدارة المستخدمين</h2>
            <p style="color: #64748b; margin-top: 0.25rem; font-size: 0.95rem;">عرض وإدارة جميع المستخدمين في النظام</p>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #10b981; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 2rem; font-weight: 800; border: 1px solid #d1fae5; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; color: #ef4444; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 2rem; font-weight: 800; border: 1px solid #fee2e2; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="card" style="padding: 1.5rem; margin-bottom: 2rem; border-radius: 1.5rem;">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            {{-- Search --}}
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 البحث بالاسم أو البريد..." style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.95rem; font-family: 'Cairo', sans-serif;">
            </div>

            {{-- Type Filter --}}
            <div>
                <select name="type" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.95rem; font-family: 'Cairo', sans-serif;">
                    <option value="">كل الأنواع</option>
                    <option value="user" {{ request('type') == 'user' ? 'selected' : '' }}>متطوع</option>
                    <option value="organization" {{ request('type') == 'organization' ? 'selected' : '' }}>منظمة</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <select name="status" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.95rem; font-family: 'Cairo', sans-serif;">
                    <option value="">كل الحالات</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>جديد</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>نشط</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>محظور</option>
                </select>
            </div>

            {{-- City Filter --}}
            <div>
                <select name="city_id" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 0.95rem; font-family: 'Cairo', sans-serif;">
                    <option value="">كل المدن</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Submit Button --}}
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" style="flex: 1; background: #3b82f6; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 800; cursor: pointer; transition: all 0.2s; font-family: 'Cairo', sans-serif;">
                    <i class="fas fa-filter"></i> تطبيق
                </button>
                <a href="{{ route('admin.users.index') }}" style="background: #f1f5f9; color: #475569; border: none; padding: 0.75rem 1.5rem; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="card" style="padding: 0; overflow: hidden; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المستخدم</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">النوع</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المدينة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الحالة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">التقييم</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">تاريخ التسجيل</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; border-radius: 0.75rem; background: linear-gradient(135deg, #3b82f6, #10b981); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem;">{{ $user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #94a3b8;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            @php
                                $typeStyles = [
                                    'user' => ['bg' => '#dbeafe', 'color' => '#1e40af', 'label' => 'متطوع', 'icon' => 'fa-user'],
                                    'organization' => ['bg' => '#dcfce7', 'color' => '#166534', 'label' => 'منظمة', 'icon' => 'fa-building'],
                                ];
                                $typeStyle = $typeStyles[$user->user_type] ?? ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => $user->user_type, 'icon' => 'fa-question'];
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: {{ $typeStyle['bg'] }}; color: {{ $typeStyle['color'] }}; padding: 0.4rem 1rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 800;">
                                <i class="fas {{ $typeStyle['icon'] }}"></i>
                                {{ $typeStyle['label'] }}
                            </span>
                        </td>
                        <td style="padding: 1.5rem; color: #475569; font-weight: 600; font-size: 0.9rem;">
                            {{ $user->city->name ?? 'غير محدد' }}
                        </td>
                        <td style="padding: 1.5rem;">
                            @php
                                $statusStyles = [
                                    0 => ['bg' => '#fef3c7', 'color' => '#92400e', 'label' => 'جديد'],
                                    1 => ['bg' => '#d1fae5', 'color' => '#065f46', 'label' => 'نشط'],
                                    2 => ['bg' => '#fee2e2', 'color' => '#991b1b', 'label' => 'محظور'],
                                ];
                                $statusStyle = $statusStyles[$user->status] ?? ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => 'غير معروف'];
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['color'] }}; padding: 0.4rem 1rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 800;">
                                {{ $statusStyle['label'] }}
                            </span>
                        </td>
                        <td style="padding: 1.5rem;">
                            @if($user->admin_rating)
                                <div style="display: flex; align-items: center; gap: 0.25rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i <= $user->admin_rating ? '#f59e0b' : '#e2e8f0' }}; font-size: 0.9rem;"></i>
                                    @endfor
                                    <span style="font-size: 0.85rem; color: #64748b; margin-right: 0.5rem;">({{ $user->admin_rating }})</span>
                                </div>
                            @else
                                <span style="color: #cbd5e1; font-size: 0.85rem; font-style: italic;">غير مقيّم</span>
                            @endif
                        </td>
                        <td style="padding: 1.5rem; color: #64748b; font-size: 0.85rem;">
                            {{ $user->created_at->format('Y/m/d') }}
                        </td>
                        <td style="padding: 1.5rem;">
                            <a href="{{ route('admin.users.show', $user) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #3b82f6; color: white; padding: 0.5rem 1.25rem; border-radius: 0.75rem; text-decoration: none; font-size: 0.85rem; font-weight: 800; transition: all 0.2s;">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 6rem 1.5rem; text-align: center;">
                            <div style="max-width: 300px; margin: 0 auto;">
                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                    <i class="fas fa-users" style="font-size: 2rem; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: #1e293b; font-weight: 800; margin-bottom: 0.5rem;">لا توجد نتائج</h3>
                                <p style="color: #64748b; font-size: 0.9rem;">لم يتم العثور على أي مستخدمين</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <div style="margin-top: 2.5rem; display: flex; justify-content: center;">
            <div style="background: white; padding: 0.75rem; border-radius: 1rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

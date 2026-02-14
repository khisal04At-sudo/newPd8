@extends('layouts.dashboard')

@section('title', 'إدارة المتقدمين')

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="margin-bottom: 2.5rem;">
        <!-- <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;"> -->
            <div>
                <h2 style="margin: 0; color: #1e293b; font-weight: 850; font-size: 1.75rem;">طلبات الانضمام</h2>
                <p style="color: #64748b; margin-top: 0.25rem; font-size: 0.95rem;">راجع طلبات المتطوعين لاتخاذ قرار القبول أو الرفض</p>
            </div><br>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <a href="{{ route('organization.applications.index') }}" 
                   style="padding: 0.6rem 1.25rem; border-radius: 0.75rem; text-decoration: none; font-weight: 800; font-size: 0.85rem; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem; {{ !isset($opportunity) ? 'background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);' : 'background: white; color: #64748b; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.04);' }}"
                   onmouseover="if(!this.classList.contains('active')) { this.style.background='#f8fafc'; this.style.color='#3b82f6'; }"
                   onmouseout="if(!this.classList.contains('active')) { this.style.background='{{ !isset($opportunity) ? 'linear-gradient(135deg, #3b82f6, #2563eb)' : 'white' }}'; this.style.color='{{ !isset($opportunity) ? 'white' : '#64748b' }}'; }">
                    الكل
                    @if(!isset($opportunity))
                        <span style="background: rgba(255,255,255,0.3); padding: 0.15rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">{{ $applications->total() }}</span>
                    @endif
                </a>
                
                <div style="position: relative; display: inline-block;">
                    <button onclick="toggleDropdown()" style="padding: 0.6rem 1.25rem; border-radius: 0.75rem; {{ isset($opportunity) ? 'background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);' : 'background: white; color: #64748b; box-shadow: 0 2px 8px rgba(0,0,0,0.04);' }} border: 1px solid {{ isset($opportunity) ? 'transparent' : '#e2e8f0' }}; cursor: pointer; font-weight: 800; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s; font-family: 'Cairo', sans-serif;"
                            onmouseover="if(!{{ isset($opportunity) ? 'true' : 'false' }}) { this.style.background='#f8fafc'; this.style.color='#3b82f6'; }"
                            onmouseout="if(!{{ isset($opportunity) ? 'true' : 'false' }}) { this.style.background='white'; this.style.color='#64748b'; }">
                        {{ isset($opportunity) ? $opportunity->title : 'اختر فرصة' }}
                        @if(isset($opportunity))
                            <span style="background: rgba(255,255,255,0.3); padding: 0.15rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">{{ $applications->total() }}</span>
                        @endif
                        <i class="fas fa-chevron-down" style="font-size: 0.7rem; transition: transform 0.2s;" id="dropdownIcon"></i>
                    </button>
                    
                    <div id="opportunitiesDropdown" style="display: none; position: absolute; top: calc(100% + 0.5rem); left: 0; background: white; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); min-width: 300px; max-height: 400px; overflow-y: auto; z-index: 1000; animation: dropdownSlide 0.2s ease-out;">
                        @forelse($opportunities as $opp)
                            <a href="{{ route('organization.applications.index', ['opportunity_id' => $opp->id]) }}" 
                               style="display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.25rem; text-decoration: none; color: #1e293b; transition: all 0.2s; border-bottom: 1px solid #f1f5f9; {{ isset($opportunity) && $opportunity->id == $opp->id ? 'background: #eff6ff;' : '' }}"
                               onmouseover="this.style.background='#f8fafc'"
                               onmouseout="this.style.background='{{ isset($opportunity) && $opportunity->id == $opp->id ? '#eff6ff' : 'transparent' }}'">
                                <div style="flex: 1;">
                                    <div style="font-weight: 800; font-size: 0.9rem; color: #1e293b; margin-bottom: 0.25rem;">{{ $opp->title }}</div>
                                    <div style="font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 0.5rem;">
                                        <span>
                                            <i class="fas fa-{{ $opp->type == 'volunteering' ? 'hand-holding-heart' : 'graduation-cap' }}"></i>
                                            {{ $opp->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                                        </span>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <span style="background: #f1f5f9; color: #475569; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.8rem; font-weight: 800;">
                                        <i class="fas fa-users" style="font-size: 0.7rem; margin-left: 0.25rem;"></i>
                                        {{ $opp->applications_count }}
                                    </span>
                                    @if(isset($opportunity) && $opportunity->id == $opp->id)
                                        <i class="fas fa-check-circle" style="color: #3b82f6; font-size: 1.1rem;"></i>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                                <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                                <p style="margin: 0; font-size: 0.9rem;">لا توجد فرص متاحة</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        @if(isset($opportunity))
            <div style="background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.05)); border-right: 4px solid #3b82f6; padding: 1rem 1.25rem; border-radius: 1rem; display: flex; align-items: center; gap: 1rem;">
                <div style="background: white; width: 48px; height: 48px; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <i class="fas fa-filter" style="color: #3b82f6; font-size: 1.2rem;"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem; margin-bottom: 0.15rem;">
                        عرض متقدمي: {{ $opportunity->title }}
                    </div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">
                        <i class="fas fa-users" style="margin-left: 0.35rem;"></i>
                        {{ $applications->total() }} من أصل {{ $opportunity->seats }} مقعد متاح
                    </div>
                </div>
                <a href="{{ route('organization.applications.index') }}" 
                   style="background: white; color: #64748b; padding: 0.5rem 1rem; border-radius: 0.75rem; text-decoration: none; font-weight: 700; font-size: 0.85rem; border: 1px solid #e2e8f0; transition: all 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;"
                   onmouseover="this.style.background='#f8fafc'; this.style.color='#3b82f6'"
                   onmouseout="this.style.background='white'; this.style.color='#64748b'">
                    <i class="fas fa-times"></i>
                    إزالة الفلتر
                </a>
            </div>
        @endif
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #10b981; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 2rem; font-weight: 800; border: 1px solid #d1fae5; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المتطوع</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الفرصة المستهدفة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المرفقات</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الحالة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="position: relative;">
                                    <img src="{{ $app->user->avatar_url ?? asset('assets/default-avatar.png') }}" style="width: 48px; height: 48px; border-radius: 1rem; object-fit: cover; border: 2px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                    @if($app->user->is_verified)
                                    <div style="position: absolute; bottom: -2px; right: -2px; background: #3b82f6; color: white; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; border: 2px solid white;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('users.profile', $app->user->id) }}" style="font-weight: 800; color: #1e293b; font-size: 1rem; text-decoration: none; transition: color 0.2s; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#1e293b'">
                                        {{ $app->user->name }}
                                        <i class="fas fa-external-link-alt" style="font-size: 0.7rem; opacity: 0.6;"></i>
                                    </a>
                                    <div style="font-size: 0.8rem; color: #94a3b8; font-weight: 600;">{{ $app->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <div style="font-weight: 700; color: #475569; font-size: 0.95rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $app->opportunity->title }}">
                                {{ $app->opportunity->title }}
                            </div>
                            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                                <i class="far fa-clock"></i> تم التقديم: {{ $app->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            @if($app->resum_file_id)
                               <a href="{{ asset($app->resumFile->file_url ?? '#') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f1f5f9; color: #475569; padding: 0.4rem 0.8rem; border-radius: 0.75rem; text-decoration: none; font-size: 0.8rem; font-weight: 700; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#3b82f6'" onmouseout="this.style.background='#f1f5f9'; this.style.color='#475569'">
                                   <i class="fas fa-file-pdf"></i> السيرة الذاتية
                               </a>
                            @else
                               <span style="color: #cbd5e1; font-size: 0.85rem; font-style: italic;">لا توجد مرفقات</span>
                            @endif
                        </td>
                        <td style="padding: 1.5rem;">
                            @php
                                $statusStyles = [
                                    'pending' => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'قيد المراجعة', 'icon' => 'fa-clock'],
                                    'accepted' => ['bg' => '#ecfdf5', 'color' => '#059669', 'label' => 'مقبول', 'icon' => 'fa-check-circle'],
                                    'rejected' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'مرفوض', 'icon' => 'fa-times-circle'],
                                ];
                                $style = $statusStyles[$app->status] ?? ['bg' => '#f8fafc', 'color' => '#64748b', 'label' => $app->status, 'icon' => 'fa-question-circle'];
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: {{ $style['bg'] }}; color: {{ $style['color'] }}; padding: 0.4rem 1rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 800; border: 1px solid rgba(0,0,0,0.03);">
                                <i class="fas {{ $style['icon'] }}"></i>
                                {{ $style['label'] }}
                            </span>
                        </td>
                        <td style="padding: 1.5rem;">
                            @if($app->status === 'pending')
                                <div style="display: flex; gap: 0.75rem;">
                                    <form action="{{ route('organization.applications.updateStatus', $app) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" style="background: #10b981; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 12px -1px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(16, 185, 129, 0.2)'">قبول</button>
                                    </form>
                                    <form action="{{ route('organization.applications.updateStatus', $app) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" style="background: white; color: #ef4444; border: 1px solid #fee2e2; padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">رفض</button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('organization.opportunities.tracking', $app->opportunity) }}" 
                                   style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.3); display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none;" 
                                   onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 12px -1px rgba(102, 126, 234, 0.4)'" 
                                   onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(102, 126, 234, 0.3)'">
                                    <i class="fas fa-clipboard-list"></i>
                                    الذهاب للتتبع والتقييم
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 6rem 1.5rem; text-align: center;">
                            <div style="max-width: 300px; margin: 0 auto;">
                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                    <i class="fas fa-user-clock" style="font-size: 2rem; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: #1e293b; font-weight: 800; margin-bottom: 0.5rem;">لا توجد طلبات</h3>
                                <p style="color: #64748b; font-size: 0.9rem;">بمجرد أن يتقدم المتطوعون لفرصك، ستظهر طلباتهم هنا</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
        <div style="margin-top: 2.5rem; display: flex; justify-content: center;">
            <div style="background: white; padding: 0.75rem; border-radius: 1rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                {{ $applications->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>



<style>
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    @keyframes dropdownSlide {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Custom Range Input Styling */
    input[type="range"] {
        -webkit-appearance: none;
        appearance: none;
        height: 8px;
        background: linear-gradient(to left, #667eea, #764ba2);
        border-radius: 10px;
        outline: none;
    }

    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        background: white;
        border: 3px solid #667eea;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: all 0.2s;
    }

    input[type="range"]::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    input[type="range"]::-moz-range-thumb {
        width: 24px;
        height: 24px;
        background: white;
        border: 3px solid #667eea;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: all 0.2s;
    }

    input[type="range"]::-moz-range-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }
</style>

<script>
    // Dropdown functionality
    function toggleDropdown() {
        const dropdown = document.getElementById('opportunitiesDropdown');
        const icon = document.getElementById('dropdownIcon');
        
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            dropdown.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('opportunitiesDropdown');
        const icon = document.getElementById('dropdownIcon');
        const button = e.target.closest('button');
        
        if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
            if (dropdown && dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }
    });
    
    // Close dropdown with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const dropdown = document.getElementById('opportunitiesDropdown');
            const icon = document.getElementById('dropdownIcon');
            if (dropdown && dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        }
    });
    

</script>
@endsection


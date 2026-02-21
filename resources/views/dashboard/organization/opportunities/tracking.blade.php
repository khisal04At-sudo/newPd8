@extends('layouts.dashboard')

@section('title', 'تتبع المشاركين - ' . $opportunity->title)

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
            <a href="{{ route('organization.opportunities.index') }}" style="color: #64748b; text-decoration: none; background: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                <i class="fas fa-arrow-right"></i>
            </a>
            <h2 style="margin: 0; color: #1e293b; font-weight: 850; font-size: 1.75rem;">تتبع المشاركين</h2>
        </div>
        <p style="color: #64748b; margin-right: 3.5rem; font-size: 0.95rem;">توثيق الحضور والالتزام لفرصة: <span style="font-weight: 700; color: #334155;">{{ $opportunity->title }}</span></p>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المشارك</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الساعات/الالتزام</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الاسم في الشهادة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">حالة الشهادة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem; text-align: center;">إجراءات المراجعة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                        <td style="padding: 1.5rem;">
                            <form action="{{ route('organization.applications.updateTracking', $app) }}" method="POST" id="tracking-form-{{ $app->id }}">
                                @csrf
                            </form>
                            
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b; font-weight: 700; font-size: 0.9rem;">
                                    {{ mb_substr($app->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 800; color: #1e293b; font-size: 1rem;">{{ $app->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #94a3b8;">{{ $app->status == 'completed' ? 'مكتمل' : 'قيد التنفيذ' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="number" name="attended_hours" value="{{ $app->attended_hours }}" min="0" max="{{ $opportunity->total_hours }}"
                                           form="tracking-form-{{ $app->id }}" class="auto-save-input"
                                           style="width: 70px; padding: 0.4rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Cairo', sans-serif; font-weight: 700;">
                                    <span style="color: #64748b; font-size: 0.75rem;">/ {{ $opportunity->total_hours }} ساعة</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="number" name="commitment_score" value="{{ $app->commitment_score }}" min="0" max="100"
                                           form="tracking-form-{{ $app->id }}" class="auto-save-input" placeholder="الالتزام 100"
                                           style="width: 80px; padding: 0.4rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Cairo', sans-serif; font-weight: 700; font-size: 0.85rem;">
                                    <span style="color: #64748b; font-size: 0.75rem;">%</span>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <input type="text" name="certificate_name" value="{{ $app->certificate_name ?: $app->user->name }}" 
                                   form="tracking-form-{{ $app->id }}" placeholder="الاسم في الشهادة" class="auto-save-input"
                                   style="width: 100%; min-width: 150px; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Cairo', sans-serif; font-weight: 700; color: #3b82f6;">
                        </td>
                        <td style="padding: 1.5rem;">
                            @php
                                $cStatus = $app->certificate_status;
                                $statusMap = [
                                    'draft' => ['label' => 'مسودة', 'color' => '#64748b', 'bg' => '#f1f5f9'],
                                    'under_review' => ['label' => 'قيد المراجعة', 'color' => '#2563eb', 'bg' => '#eff6ff'],
                                    'approved' => ['label' => 'معتمدة', 'color' => '#16a34a', 'bg' => '#f0fdf4'],
                                    'rejected' => ['label' => 'مرفوضة', 'color' => '#dc2626', 'bg' => '#fef2f2'],
                                ];
                                $current = $statusMap[$cStatus] ?? $statusMap['draft'];
                            @endphp
                            <span id="status-badge-{{ $app->id }}" style="background: {{ $current['bg'] }}; color: {{ $current['color'] }}; padding: 0.4rem 0.8rem; border-radius: 0.5rem; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; border: 1px solid {{ $current['color'] }}20;">
                                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                {{ $current['label'] }}
                            </span>
                        </td>
                        <td style="padding: 1.5rem; text-align: center;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem; align-items: center;">
                                <div style="display: flex; gap: 0.4rem;">
                                    <div id="save-indicator-{{ $app->id }}" style="display: none; color: #16a34a; font-size: 0.8rem; font-weight: 700;">
                                        <i class="fas fa-check-circle"></i> تم الحفظ
                                    </div>
                                    <a href="{{ route('organization.applications.certificate.preview', $app) }}" target="_blank" title="معاينة"
                                       style="background: #ffffff; color: #2563eb; border: 1px solid #dbeafe; padding: 0.5rem; border-radius: 0.5rem; text-decoration: none;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                                <div style="display: flex; gap: 0.4rem;">
                                    @if($cStatus !== 'approved')
                                        <form action="{{ route('organization.applications.certificate.issue', $app) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background: #16a34a; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;">
                                                <i class="fas fa-check-circle"></i> اعتماد
                                            </button>
                                        </form>
                                        <form action="{{ route('organization.applications.certificate.reject', $app) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background: #dc2626; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.8rem; cursor: pointer; display: flex; align-items: center; gap: 0.3rem;">
                                                <i class="fas fa-times-circle"></i> رفض
                                            </button>
                                        </form>
                                    @else
                                        <span style="color: #16a34a; font-weight: 800; font-size: 0.8rem;">تم الإصدار ✅</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem 1.5rem; text-align: center; color: #64748b;">
                            <i class="fas fa-user-clock" style="font-size: 2.5rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i>
                            لا يوجد مشاركين مقبولين حالياً لبدء التتبع.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.auto-save-input');
        let timeouts = {};

        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const appId = this.getAttribute('form').split('-').pop();
                
                clearTimeout(timeouts[appId]);
                
                timeouts[appId] = setTimeout(() => {
                    saveTrackingData(appId);
                }, 1000); // Save after 1 second of inactivity
            });
        });

        async function saveTrackingData(appId) {
            const form = document.getElementById(`tracking-form-${appId}`);
            const formData = new FormData(form);
            const indicator = document.getElementById(`save-indicator-${appId}`);
            const badge = document.getElementById(`status-badge-${appId}`);

            // Gather values from inputs associated with this form
            const relatedInputs = document.querySelectorAll(`[form="tracking-form-${appId}"]`);
            relatedInputs.forEach(input => {
                if (input.name) {
                    formData.append(input.name, input.value);
                }
            });

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    indicator.style.display = 'block';
                    setTimeout(() => {
                        indicator.style.display = 'none';
                    }, 3000);

                    // Update status badge if changed
                    if (data.certificate_status) {
                        updateStatusBadge(appId, data.certificate_status);
                    }
                }
            } catch (error) {
                console.error('Error auto-saving:', error);
            }
        }

        function updateStatusBadge(appId, status) {
            const badge = document.getElementById(`status-badge-${appId}`);
            const statusMap = {
                'draft': { label: 'مسودة', color: '#64748b', bg: '#f1f5f9' },
                'under_review': { label: 'قيد المراجعة', color: '#2563eb', bg: '#eff6ff' },
                'approved': { label: 'معتمدة', color: '#16a34a', bg: '#f0fdf4' },
                'rejected': { label: 'مرفوضة', color: '#dc2626', bg: '#fef2f2' }
            };
            const current = statusMap[status] || statusMap['draft'];
            
            badge.style.background = current.bg;
            badge.style.color = current.color;
            badge.style.borderColor = current.color + '20';
            badge.innerHTML = `<i class="fas fa-circle" style="font-size: 0.5rem;"></i> ${current.label}`;
        }
    });
</script>
@endsection

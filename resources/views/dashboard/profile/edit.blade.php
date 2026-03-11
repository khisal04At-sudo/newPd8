@extends('layouts.dashboard')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div style="max-width: 900px; margin: 0 auto; animation: fadeIn 0.8s ease-out;">
    <div class="glass-card" style="padding: 2.5rem; border-radius: 2rem;">
        </div>

        @if(session('success'))
            <div style="background: #f0fdf4; color: #16a34a; padding: 1rem 1.5rem; border-radius: 1rem; border: 1px solid #bbf7d0; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-weight: 600;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fef2f2; color: #dc2626; padding: 1rem 1.5rem; border-radius: 1rem; border: 1px solid #fecaca; margin-bottom: 2rem; font-weight: 600;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>يرجى تصحيح الأخطاء التالية:</span>
                </div>
                <ul style="margin: 0; padding-right: 2rem; font-size: 0.9rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3.5rem;">
                <!-- Avatar Upload Section -->
                <div style="text-align: center;">
                    <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
                        <img src="{{ $user->avatar_url }}" 
                             id="avatarPreview"
                             onclick="openPhotoModal(this.src, '{{ $user->name }}')"
                             style="width: 180px; height: 180px; border-radius: 2rem; object-fit: cover; border: 4px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: white; cursor: pointer;">
                        <label for="avatarInput" style="position: absolute; bottom: -10px; left: -10px; background: #4f46e5; color: white; width: 44px; height: 44px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 4px solid white; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="avatar" id="avatarInput" style="display: none;" accept="image/*" onchange="previewAvatar(this)">
                    </div>
                    <div style="text-align: right;">
                        <h4 style="margin: 0 0 0.5rem; font-size: 1rem; color: #1e293b;">الصورة الشخصية</h4>
                        <p style="font-size: 0.8rem; color: #94a3b8; line-height: 1.5;">يفضل استخدام صورة مربعة بمقاس 400x400 بكسل بحد أقصى 2 ميجابايت.</p>
                    </div>
                </div>

                <!-- Basic Info Fields -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem; transition: border-color 0.2s;" 
                               onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e2e8f0'" required>
                    </div>

                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">النبذة التعريفية</label>
                        <textarea name="bio" rows="4" 
                                  style="width: 100%; padding: 1rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem; resize: vertical; transition: border-color 0.2s;" 
                                  onfocus="this.style.borderColor='#4f46e5'" onblur="this.style.borderColor='#e2e8f0'"
                        placeholder="أخبرنا قليلاً عن نفسك، خبراتك، وما تطمح لتحقيقه من خلال التطوع...">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">السيرة الذاتية (CV)</label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <input type="file" name="cv" id="cvInput" class="form-control" accept=".pdf,.doc,.docx"
                                   style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #e2e8f0; border-radius: 10px; font-family: inherit; font-size: 0.95rem; transition: border-color 0.2s;">
                            
                            @php
                                $cvFile = $user->files()->where('file_category', 'cv')->first();
                            @endphp
                            
                            @if($cvFile)
                                @php
                                    $cvPath = str_starts_with($cvFile->file_url, 'storage/') ? substr($cvFile->file_url, 8) : $cvFile->file_url;
                                @endphp
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($cvPath) }}" target="_blank" 
                                   style="display: inline-flex; align-items: center; gap: 0.5rem; white-space: nowrap; padding: 0.75rem 1rem; background: #eff6ff; color: #3b82f6; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 0.9rem; transition: all 0.2s;">
                                    <i class="fas fa-file-download"></i> عرض الحالي
                                </a>
                            @endif
                        </div>
                        <p style="font-size: 0.8rem; color: #94a3b8; margin-top: 0.5rem;">PDF, DOC, DOCX - بحد أقصى 5 ميجابايت</p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">المدينة</label>
                            <select name="city_id" style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; appearance: none; background: white url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23667eea%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22/%3E%3C/svg%3E') no-repeat left 1rem center; background-size: 0.6rem auto; font-size: 1rem;" required>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('city_id', $user->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem; direction: ltr; text-align: right;"
                                   placeholder="09XXXXXXXX">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">تاريخ الميلاد</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" 
                                   style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem;">
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">الجنس</label>
                            <div style="display: flex; gap: 1rem;">
                                <label style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 1rem; cursor: pointer; transition: all 0.2s;" id="maleLabel">
                                    <input type="radio" name="gender" value="ذكر" {{ old('gender', $user->gender) == 'ذكر' ? 'checked' : '' }} onchange="updateGenderStyles()">
                                    <i class="fas fa-mars text-blue-500"></i> ذكر
                                </label>
                                <label style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 1rem; cursor: pointer; transition: all 0.2s;" id="femaleLabel">
                                    <input type="radio" name="gender" value="أنثى" {{ old('gender', $user->gender) == 'أنثى' ? 'checked' : '' }} onchange="updateGenderStyles()">
                                    <i class="fas fa-venus text-pink-500"></i> أنثى
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Interests Section --}}
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.4rem; color: #475569; font-weight: 700; font-size: 0.9rem;">🎯 الاهتمامات</label>
                        <p style="font-size: 0.8rem; color: #94a3b8; margin-bottom: 0.85rem;">اختر المجالات التي تهتم بها لتصلك فرص مقترحة</p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 0.6rem;">
                            @foreach($categories as $name => $info)
                                <label style="display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 0.85rem; border: 1.5px solid {{ in_array($name, $userInterests) ? $info['color'] : '#e2e8f0' }}; border-radius: 0.75rem; cursor: pointer; font-size: 0.85rem; font-weight: 700; background: {{ in_array($name, $userInterests) ? 'color-mix(in srgb, '.$info['color'].' 10%, white)' : 'white' }}; color: {{ in_array($name, $userInterests) ? $info['color'] : '#475569' }}; transition: all 0.2s;"
                                      onmouseover="this.style.borderColor='{{ $info['color'] }}'"
                                      onmouseout="if(!this.querySelector('input').checked) { this.style.borderColor='#e2e8f0'; this.style.background='white'; this.style.color='#475569'; }">
                                    <input type="checkbox" name="interests[]" value="{{ $name }}"
                                           {{ in_array($name, $userInterests) ? 'checked' : '' }}
                                           onchange="toggleInterest(this, '{{ $info['color'] }}')"
                                           style="display: none;">
                                    <span>
                                        @if(str_contains($info['icon'], '/'))
                                            <img src="{{ asset($info['icon']) }}" style="width: 1rem; height: 1rem; object-fit: contain;">
                                        @else
                                            <i class="{{ $info['icon'] }}"></i>
                                        @endif
                                    </span>
                                    <span>{{ $name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="reset" style="padding: 1rem 2rem; background: #f1f5f9; color: #475569; border: none; border-radius: 1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">إلغاء</button>
                        <button type="submit" class="btn-brand" style="padding: 1rem 3.5rem; border-radius: 1rem; font-size: 1.1rem; font-weight: 800; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);">حفظ التغييرات</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateGenderStyles() {
        const maleRadio = document.querySelector('input[name="gender"][value="ذكر"]');
        const femaleRadio = document.querySelector('input[name="gender"][value="أنثى"]');
        const maleLabel = document.getElementById('maleLabel');
        const femaleLabel = document.getElementById('femaleLabel');

        if (maleRadio.checked) {
            maleLabel.style.borderColor = '#3b82f6';
            maleLabel.style.background = '#eff6ff';
            femaleLabel.style.borderColor = '#e2e8f0';
            femaleLabel.style.background = 'white';
        } else if (femaleRadio.checked) {
            femaleLabel.style.borderColor = '#ec4899';
            femaleLabel.style.background = '#fdf2f8';
            maleLabel.style.borderColor = '#e2e8f0';
            maleLabel.style.background = 'white';
        }
    }

    // Initial style call
    document.addEventListener('DOMContentLoaded', updateGenderStyles);

    function toggleInterest(checkbox, color) {
        const label = checkbox.closest('label');
        if (checkbox.checked) {
            label.style.borderColor = color;
            label.style.background = `color-mix(in srgb, ${color} 10%, white)`;
            label.style.color = color;
        } else {
            label.style.borderColor = '#e2e8f0';
            label.style.background = 'white';
            label.style.color = '#475569';
        }
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

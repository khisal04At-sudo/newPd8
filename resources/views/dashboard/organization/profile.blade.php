@extends('layouts.dashboard')

@section('title', 'إعدادات الملف المؤسسي')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; animation: fadeIn 0.8s ease-out;">
    <div class="glass-card" style="padding: 2.5rem; border-radius: 2rem;">
        <div style="margin-bottom: 2.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h2 style="margin: 0; color: #1e293b; font-size: 1.75rem; font-weight: 800;">إعدادات المؤسسة</h2>
                <p style="color: #64748b; margin-top: 0.5rem; font-size: 0.95rem;">قم بتحديث معلومات المؤسسة، الشعار، وروابط التواصل</p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <div style="background: {{ $user->organization->verified ? '#f0fdf4' : '#fff7ed' }}; color: {{ $user->organization->verified ? '#16a34a' : '#ea580c' }}; padding: 0.5rem 1.25rem; border-radius: 1rem; font-size: 0.85rem; font-weight: 700; border: 1px solid {{ $user->organization->verified ? '#bbf7d0' : '#ffedd5' }}; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas {{ $user->organization->verified ? 'fa-check-circle' : 'fa-clock' }}"></i>
                    {{ $user->organization->verified ? 'مؤسسة معتمدة' : 'بانتظار الاعتماد' }}
                </div>
                <a href="{{ route('organization.profile.show') }}" style="color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; background: #f1f5f9; padding: 0.6rem 1.2rem; border-radius: 0.8rem;">
                    <i class="fas fa-eye"></i> عرض البروفايل
                </a>
            </div>
        </div>

        <form action="{{ route('organization.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 4rem;">
                <!-- Logo Section -->
                <div style="text-align: center;">
                    <div style="position: relative; display: inline-block; margin-bottom: 1.5rem;">
                        <img src="{{ $user->organization->logo_url ? asset('storage/' . $user->organization->logo_url) : asset('images/default-org.png') }}" 
                             id="logoPreview"
                             style="width: 200px; height: 200px; border-radius: 2rem; object-fit: cover; border: 4px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: white;">
                        <label for="logoInput" style="position: absolute; bottom: -10px; left: -10px; background: #f59e0b; color: white; width: 44px; height: 44px; border-radius: 1rem; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 4px solid white; transition: all 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input type="file" name="logo" id="logoInput" style="display: none;" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div style="text-align: right;">
                        <h4 style="margin: 0 0 0.5rem; font-size: 1rem; color: #1e293b; font-weight: 700;">شعار المؤسسة</h4>
                        <p style="font-size: 0.8rem; color: #94a3b8; line-height: 1.5;">يفضل استخدام شعار واضح بمقاس 512x512 بكسل.</p>
                    </div>
                </div>

                <!-- Fields Section -->
                <div style="display: flex; flex-direction: column; gap: 1.75rem;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">اسم المؤسسة الرسمي</label>
                        <input type="text" name="name" value="{{ old('name', $user->organization->name) }}" 
                               style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem; transition: border-color 0.2s;" 
                               onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'" required>
                    </div>

                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">وصف المؤسسة ورؤيتها</label>
                        <textarea name="description" rows="5" 
                                  style="width: 100%; padding: 1rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem; resize: vertical; transition: border-color 0.2s;" 
                                  onfocus="this.style.borderColor='#f59e0b'" onblur="this.style.borderColor='#e2e8f0'"
                                  placeholder="تحدث عن أهداف المؤسسة، نشاطاتها، وما تقدمه للمجتمع...">{{ old('description', $user->organization->description) }}</textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">رقم هاتف التواصل</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->organization->phone) }}" 
                                   style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem; direction: ltr; text-align: right;"
                                   placeholder="09XXXXXXXX">
                        </div>
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">المدينة</label>
                            <select name="city_id" style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; appearance: none; background: white url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23f59e0b%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22/%3E%3C/svg%3E') no-repeat left 1rem center; background-size: 0.6rem auto; font-size: 1rem;">
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ $user->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="display: block; margin-bottom: 0.6rem; color: #475569; font-weight: 700; font-size: 0.9rem;">العنوان التفصيلي</label>
                        <input type="text" name="address" value="{{ old('address', $user->organization->address) }}" 
                               style="width: 100%; padding: 0.85rem 1.25rem; border: 1px solid #e2e8f0; border-radius: 1rem; outline: none; font-size: 1rem;"
                               placeholder="الشارع، المنطقة، القرب من علامة مميزة...">
                    </div>

                    <div style="margin-top: 1rem;">
                        <h4 style="margin-bottom: 1.25rem; color: #1e293b; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-share-alt text-brand-500"></i> الروابط الاجتماعية
                        </h4>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #1877f2; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-facebook-f"></i>
                                </div>
                                <input type="url" name="social_links[facebook]" value="{{ old('social_links.facebook', $user->organization->social_links['facebook'] ?? '') }}" 
                                       placeholder="Facebook URL" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; font-size: 0.9rem;">
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: #f0f9ff; color: #1da1f2; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-twitter"></i>
                                </div>
                                <input type="url" name="social_links[twitter]" value="{{ old('social_links.twitter', $user->organization->social_links['twitter'] ?? '') }}" 
                                       placeholder="Twitter URL" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; font-size: 0.9rem;">
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: #fdf2f8; color: #e4405f; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-instagram"></i>
                                </div>
                                <input type="url" name="social_links[instagram]" value="{{ old('social_links.instagram', $user->organization->social_links['instagram'] ?? '') }}" 
                                       placeholder="Instagram URL" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; font-size: 0.9rem;">
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <div style="width: 38px; height: 38px; border-radius: 10px; background: #f1f5f9; color: #64748b; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <input type="url" name="social_links[website]" value="{{ old('social_links.website', $user->organization->social_links['website'] ?? '') }}" 
                                       placeholder="Website URL" style="flex: 1; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; font-size: 0.9rem;">
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                        <button type="reset" style="padding: 1rem 2.5rem; background: #f1f5f9; color: #475569; border: none; border-radius: 1rem; font-weight: 700; cursor: pointer;">إلغاء</button>
                        <button type="submit" style="padding: 1rem 4rem; background: #f59e0b; color: white; border: none; border-radius: 1rem; font-weight: 800; font-size: 1.1rem; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3);">حفظ التغييرات</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
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

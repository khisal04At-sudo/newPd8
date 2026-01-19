@extends('layouts.dashboard')

@section('title', 'الملف المؤسسي')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
        <h2 style="margin: 0; color: #1e293b;">إعدادات الملف المؤسسي</h2>
        <div style="background: {{ $user->organization->verified ? '#f0fdf4' : '#fff7ed' }}; color: {{ $user->organization->verified ? '#16a34a' : '#ea580c' }}; padding: 0.5rem 1rem; border-radius: 2rem; font-size: 0.85rem; font-weight: 600; border: 1px solid {{ $user->organization->verified ? '#bbf7d0' : '#ffedd5' }};">
            <i class="fas {{ $user->organization->verified ? 'fa-check-circle' : 'fa-clock' }}"></i>
            {{ $user->organization->verified ? 'مؤسسة معتمدة' : 'بانتظار الاعتماد' }}
        </div>
    </div>

    <form action="{{ route('organization.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem;">
            <!-- Logo Section -->
            <div style="text-align: center;">
                <div style="margin-bottom: 1.5rem;">
                    <img src="{{ $user->organization->logo_url ? asset('storage/' . $user->organization->logo_url) : asset('images/default-org.png') }}" 
                         id="logoPreview"
                         style="width: 200px; height: 200px; border-radius: 1rem; object-fit: cover; border: 4px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                </div>
                <div style="position: relative;">
                    <input type="file" name="logo" id="logoInput" style="display: none;" accept="image/*" onchange="previewImage(this)">
                    <button type="button" onclick="document.getElementById('logoInput').click()" 
                            style="background: #f1f5f9; color: #475569; padding: 0.6rem 1.2rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; cursor: pointer; font-size: 0.9rem; font-weight: 600;">
                        <i class="fas fa-camera"></i> تغيير الشعار
                    </button>
                    <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem;">يفضل مقاس 512x512 بكسل</p>
                </div>
            </div>

            <!-- Fields Section -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #475569; font-weight: 600;">اسم المؤسسة</label>
                    <input type="text" name="name" value="{{ old('name', $user->organization->name) }}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;" required>
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #475569; font-weight: 600;">وصف المؤسسة</label>
                    <textarea name="description" rows="4" 
                              style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; resize: vertical;" 
                              placeholder="تحدث بإيجاز عن رؤية وأهداف المؤسسة...">{{ old('description', $user->organization->description) }}</textarea>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: #475569; font-weight: 600;">رقم الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->organization->phone) }}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: #475569; font-weight: 600;">المدينة</label>
                    <select name="city_id" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; appearance: none; background: white;">
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ $user->city_id == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 0.5rem; color: #475569; font-weight: 600;">العنوان التفصيلي</label>
                    <input type="text" name="address" value="{{ old('address', $user->organization->address) }}" 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>

                <div style="grid-column: span 2; margin-top: 1rem;">
                    <h4 style="margin-bottom: 1rem; color: #1e293b; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">روابط التواصل الإجتماعي</h4>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fab fa-facebook" style="color: #1877f2; font-size: 1.25rem;"></i>
                            <input type="url" name="social_links[facebook]" value="{{ old('social_links.facebook', $user->organization->social_links['facebook'] ?? '') }}" 
                                   placeholder="Facebook URL" style="flex: 1; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fab fa-twitter" style="color: #1da1f2; font-size: 1.25rem;"></i>
                            <input type="url" name="social_links[twitter]" value="{{ old('social_links.twitter', $user->organization->social_links['twitter'] ?? '') }}" 
                                   placeholder="Twitter URL" style="flex: 1; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fab fa-instagram" style="color: #e4405f; font-size: 1.25rem;"></i>
                            <input type="url" name="social_links[instagram]" value="{{ old('social_links.instagram', $user->organization->social_links['instagram'] ?? '') }}" 
                                   placeholder="Instagram URL" style="flex: 1; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-link" style="color: #64748b; font-size: 1.25rem;"></i>
                            <input type="url" name="social_links[website]" value="{{ old('social_links.website', $user->organization->social_links['website'] ?? '') }}" 
                                   placeholder="Website URL" style="flex: 1; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                        </div>
                    </div>
                </div>

                <div style="grid-column: span 2; display: flex; justify-content: flex-end; margin-top: 2rem; gap: 1rem;">
                    <button type="reset" style="padding: 0.75rem 2rem; background: #f1f5f9; color: #475569; border: none; border-radius: 0.75rem; font-weight: 600; cursor: pointer;">إلغاء</button>
                    <button type="submit" style="padding: 0.75rem 3rem; background: #f59e0b; color: white; border: none; border-radius: 0.75rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3);">حفظ التغييرات</button>
                </div>
            </div>
        </div>
    </form>
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
@endsection

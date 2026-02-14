@extends('layouts.dashboard')

@section('title', 'تقييم الفرصة: ' . $opportunity->title)

@section('content')
<div style="font-family: 'Cairo', sans-serif; max-width: 800px; margin: 0 auto;">
    <div style="background: white; border-radius: 2rem; padding: 3rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <div style="width: 80px; height: 80px; background: #fff7ed; border-radius: 2rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: #f59e0b; font-size: 2.5rem;">
                <i class="fas fa-star"></i>
            </div>
            <h1 style="font-weight: 950; color: #1e293b; font-size: 2rem; margin-bottom: 1rem;">كيف كانت تجربتك؟</h1>
            <p style="color: #64748b; font-size: 1.1rem;">رأيك يهمنا ويساعد الآخرين في اختيار الفرص المناسبة.</p>
        </div>

        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1.5rem; margin-bottom: 2.5rem; display: flex; align-items: center; gap: 1.5rem;">
            @if($opportunity->organization->logo)
                <img src="{{ asset($opportunity->organization->logo) }}" style="width: 60px; height: 60px; border-radius: 1rem; object-fit: cover;">
            @else
                <div style="width: 60px; height: 60px; background: #e2e8f0; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 1.5rem;">
                    <i class="fas fa-building"></i>
                </div>
            @endif
            <div>
                <h3 style="font-weight: 850; color: #1e293b; margin: 0; font-size: 1.2rem;">{{ $opportunity->title }}</h3>
                <p style="color: #64748b; margin: 0.25rem 0 0; font-size: 0.95rem;">{{ $opportunity->organization->name }}</p>
            </div>
        </div>

        <form action="{{ route('volunteer.reviews.store', $application) }}" method="POST">
            @csrf
            
            <!-- Rating -->
            <div style="margin-bottom: 2.5rem; text-align: center;">
                <label style="display: block; font-weight: 800; color: #1e293b; margin-bottom: 1.5rem; font-size: 1.1rem;">تقييمك الإجمالي للفرصة</label>
                <div style="display: flex; justify-content: center; gap: 1rem; direction: ltr;">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" style="display: none;" required>
                        <label for="star{{ $i }}" style="font-size: 3rem; color: #e2e8f0; cursor: pointer; transition: all 0.2s;" onmouseover="highlightStars({{ $i }})" onmouseout="resetStars()" onclick="selectStar({{ $i }})">
                            <i class="fas fa-star"></i>
                        </label>
                    @endfor
                </div>
                @error('rating')
                    <p style="color: #ef4444; font-size: 0.85rem; margin-top: 0.5rem; font-weight: 700;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Comment -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 800; color: #1e293b; margin-bottom: 0.75rem; font-size: 1rem;">اكتب تعليقك (اختياري)</label>
                <textarea name="review_comment" rows="4" style="width: 100%; padding: 1.25rem; border: 2px solid #e2e8f0; border-radius: 1.25rem; font-family: 'Cairo', sans-serif; font-size: 1rem; outline: none; transition: all 0.2s; resize: none;" onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'" onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'" placeholder="ما الذي أعجبك؟ وما الذي يمكن تحسينه؟"></textarea>
            </div>

            <!-- Recommendation -->
            <div style="margin-bottom: 3rem; background: #f0f9ff; padding: 1.25rem; border-radius: 1rem; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <i class="fas fa-thumbs-up" style="color: #0369a1; font-size: 1.2rem;"></i>
                    <span style="font-weight: 700; color: #0c4a6e;">هل تنصح الآخرين بالمشاركة في هذه الفرصة؟</span>
                </div>
                <label class="switch" style="position: relative; display: inline-block; width: 60px; height: 34px;">
                    <input type="checkbox" name="would_recommend" checked style="opacity: 0; width: 0; height: 0;">
                    <span class="slider round" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px;"></span>
                </label>
            </div>

            <button type="submit" style="width: 100%; padding: 1.25rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none; border-radius: 1.25rem; font-weight: 900; font-size: 1.1rem; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(59, 130, 246, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 20px rgba(59, 130, 246, 0.2)'">
                إرسال التقييم
            </button>
        </form>
    </div>
</div>

<style>
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider { background-color: #10b981; }
    input:checked + .slider:before { transform: translateX(26px); }
</style>

<script>
    let selectedRating = 0;

    function highlightStars(rating) {
        for (let i = 1; i <= 5; i++) {
            const star = document.querySelector(`label[for="star${i}"]`);
            if (i <= rating) {
                star.style.color = '#f59e0b';
            } else {
                star.style.color = '#e2e8f0';
            }
        }
    }

    function resetStars() {
        highlightStars(selectedRating);
    }

    function selectStar(rating) {
        selectedRating = rating;
        highlightStars(rating);
    }
</script>
@endsection

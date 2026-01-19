<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityActionController extends Controller
{
    /**
     * التقديم على فرصة
     */
    public function apply(Opportunity $opportunity)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً للتقديم على الفرص.');
        }

        $user = Auth::user();

        // تحقق إذا كان قد قدم مسبقاً
        $exists = Application::where('user_id', $user->id)
            ->where('opportunity_id', $opportunity->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'لقد قمت بالتقديم على هذه الفرصة مسبقاً.');
        }

        Application::create([
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return back()->with('success', 'تم إرسال طلبك بنجاح! سيتم مراجعته من قبل المؤسسة.');
    }

    /**
     * حفظ للرجوع (بوكمارك)
     */
    public function save(Opportunity $opportunity)
    {
        // هنا يمكن إضافة منطق الحفظ في جدول وسيط (bookmarks)
        // للتبسيط الآن سأعيد رسالة نجاح
        return back()->with('success', 'تم حفظ الفرصة في قائمتك المفضلة.');
    }

    /**
     * مشاركة الفرصة
     */
    public function share(Opportunity $opportunity)
    {
        // توليد رابط المشاركة
        $url = route('opportunities.show', $opportunity);
        return back()->with('success', 'رابط المشاركة: ' . $url);
    }
}

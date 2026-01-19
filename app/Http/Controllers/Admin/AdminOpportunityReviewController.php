<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOpportunityReviewController extends Controller
{
    /**
     * عرض قائمة الفرص التي بانتظار المراجعة
     */
    public function index()
    {
        $opportunities = Opportunity::with(['organization', 'city', 'certificateFile'])
            ->where('status', 0) // pending_review
            ->latest()
            ->paginate(15);

        return view('admin.opportunities.index', compact('opportunities'));
    }

    /**
     * عرض تفاصيل الفرصة للمراجعة والتعديل
     */
    public function show(Opportunity $opportunity)
    {
        $opportunity->load('organization');
        return view('admin.opportunities.show', compact('opportunity'));
    }

    /**
     * نشر الفرصة وإرسال إشعارات للمتطوعين المطابقين
     */
    public function publish(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
        ]);

        // تحديث البيانات (الأدمن يمكنه التعديل)
        $opportunity->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => 1, // published
            'admin_notes' => null,
        ]);

        // إرسال إشعارات ذكية للمستخدمين المطابقين (حسب المدينة أو المهارات)
        $this->notifyMatchingUsers($opportunity);

        return redirect()->route('admin.opportunities.index')
            ->with('success', 'تم نشر الفرصة بنجاح وإرسال الإشعارات للمتطوعين.');
    }

    /**
     * طلب تعديلات من المؤسسة
     */
    public function requestChanges(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $opportunity->update([
            'status' => 2, // needs_changes
            'admin_notes' => $request->notes,
        ]);

        // إشعار للمؤسسة
        Notification::create([
            'user_id' => $opportunity->organization->user_id,
            'title' => 'تعديلات مطلوبة على الفرصة',
            'message' => 'يرجى مراجعة وتعديل الفرصة "' . $opportunity->title . '". الملاحظات: ' . $request->notes,
            'type' => 'system',
        ]);

        return redirect()->route('admin.opportunities.index')
            ->with('info', 'تم إرسال طلب التعديل للمؤسسة.');
    }

    /**
     * رفض الفرصة تماماً
     */
    public function reject(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $opportunity->update([
            'status' => 3, // rejected
            'admin_notes' => $request->notes,
        ]);

        // إشعار للمؤسسة
        Notification::create([
            'user_id' => $opportunity->organization->user_id,
            'title' => 'تم رفض نشر الفرصة',
            'message' => 'نعتذر، تم رفض نشر الفرصة "' . $opportunity->title . '". السبب: ' . $request->notes,
            'type' => 'system',
        ]);

        return redirect()->route('admin.opportunities.index')
            ->with('error', 'تم رفض الفرصة بنجاح.');
    }

    /**
     * منطق الإشعارات الذكي
     */
    private function notifyMatchingUsers(Opportunity $opportunity)
    {
        // 1. المستخدمين في نفس المدينة
        $cityMatchUserIds = User::where('city_id', $opportunity->city_id)
            ->where('user_type', 'volunteer')
            ->pluck('id');

        // 2. المستخدمين الذين لديهم مهارات مطابقة (تصنيف الفرصة أو المهارات المطلوبة)
        // ملاحظة: التصنيف حالياً هو نص، وسنبحث عن المهارات التي تحتوي على كلمات مفتاحية من عنوان أو تصنيف الفرصة
        $skillKeywords = [$opportunity->category];
        
        $skillMatchUserIds = DB::table('user_skills')
            ->whereIn('skill_name', $skillKeywords)
            ->pluck('user_id');

        $userIds = $cityMatchUserIds->merge($skillMatchUserIds)->unique();

        foreach ($userIds as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => 'فرصة جديدة تهمك! 🌟',
                'message' => 'تم نشر فرصة جديدة: "' . $opportunity->title . '" تتوافق مع اهتماماتك أو منطقتك.',
                'type' => 'opportunity',
                'notifiable_type' => Opportunity::class,
                'notifiable_id' => $opportunity->id,
            ]);
        }
    }
}

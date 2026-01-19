<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\City;
use App\Models\Organization;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PublicOpportunityController extends Controller
{
    /**
     * تصفح جميع الفرص مع الفلاتر
     */
    public function index(Request $request)
    {
        $query = Opportunity::with('organization', 'city')
            ->where('status', 1); // منشورة فقط

        // تصفية حسب النوع
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // تصفية حسب التصنيف
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // تصفية حسب المدينة
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // تصفية حسب المؤسسة
        if ($request->filled('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }

        // تصفية حسب الحالة الزمنية
        if ($request->filled('time_filter')) {
            if ($request->time_filter === 'ending_soon') {
                $query->where('end_date', '>=', Carbon::now())
                      ->orderBy('end_date', 'asc');
            } elseif ($request->time_filter === 'completed') {
                $query->where('end_date', '<', Carbon::now());
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        $opportunities = $query->paginate(12)->withQueryString();
        
        $cities = City::all();
        $organizations = Organization::where('verified', true)->get();

        return view('opportunities.index', compact('opportunities', 'cities', 'organizations'));
    }

    /**
     * عرض تفاصيل الفرصة
     */
    public function show(Opportunity $opportunity)
    {
        $opportunity->load(['organization', 'city']);
        
        // جلب فرص مشابهة أو تقييمات (إذا وجدت بنية للتقييم لاحقاً)
        $relatedOpportunities = Opportunity::where('category', $opportunity->category)
            ->where('id', '!=', $opportunity->id)
            ->limit(3)
            ->get();

        return view('opportunities.show', compact('opportunity', 'relatedOpportunities'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Application;
use App\Models\OpportunityReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityReviewController extends Controller
{
    /**
     * Show the review form
     */
    public function create(Application $application)
    {
        // Ensure the application belongs to the user and is completed
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        if ($application->status !== 'completed') {
            return back()->with('error', 'يمكنك تقييم الفرصة فقط بعد اكتمالها.');
        }

        // Check if already reviewed
        if ($application->review) {
            return back()->with('error', 'لقد قمت بتقييم هذه الفرصة مسبقاً.');
        }

        $opportunity = $application->opportunity;
        return view('dashboard.volunteer.reviews.create', compact('application', 'opportunity'));
    }

    /**
     * Store the review
     */
    public function store(Request $request, Application $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403);
        }

        if ($application->status !== 'completed' || $application->review) {
            abort(400);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_comment' => 'nullable|string|max:1000',
            'would_recommend' => 'nullable|boolean',
        ], [
            'rating.required' => 'يرجى اختيار التقييم.',
            'review_comment.max' => 'التعليق يجب ألا يتجاوز 1000 حرف.',
        ]);

        OpportunityReview::create([
            'user_id' => Auth::id(),
            'opportunity_id' => $application->opportunity_id,
            'application_id' => $application->id,
            'rating' => $request->rating,
            'review_comment' => $request->review_comment,
            'would_recommend' => $request->has('would_recommend'),
        ]);

        return redirect()->route('volunteer.applications')
            ->with('success', 'شكراً لك على تقييمك! يساعدنا رأيك في تحسين جودة الفرص.');
    }
}

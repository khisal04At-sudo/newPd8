<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Opportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * Display application form
     */
    public function create($opportunityId)
    {
        $opportunity = Opportunity::with(['organization', 'city'])->findOrFail($opportunityId);
        
        // Check if user already applied
        $existingApplication = Application::where('user_id', Auth::id())
            ->where('opportunity_id', $opportunityId)
            ->first();
        
        if ($existingApplication) {
            return redirect()->route('opportunities.show', $opportunity)
                ->with('error', 'لقد قمت بالتقديم على هذه الفرصة مسبقاً');
        }
        
        return view('opportunities.apply', compact('opportunity'));
    }

    /**
     * Store application
     */
    public function store(Request $request, $opportunityId)
    {
        $opportunity = Opportunity::findOrFail($opportunityId);
        
        // Validation
        $validated = $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'cover_letter' => $opportunity->requires_cover_letter == 'yes' ? 'required|string|max:2000' : 'nullable|string|max:2000',
        ], [
            'resume.required' => 'السيرة الذاتية مطلوبة',
            'resume.mimes' => 'يجب أن تكون السيرة الذاتية بصيغة PDF أو DOC أو DOCX',
            'resume.max' => 'حجم الملف يجب أن لا يتجاوز 5 ميجابايت',
            'cover_letter.required' => 'خطاب التغطية مطلوب لهذه الفرصة',
        ]);
        
        // Check if user already applied
        $existingApplication = Application::where('user_id', Auth::id())
            ->where('opportunity_id', $opportunityId)
            ->first();
        
        if ($existingApplication) {
            return back()->with('error', 'لقد قمت بالتقديم على هذه الفرصة مسبقاً');
        }
        
        // Upload resume file
        $resumePath = $request->file('resume')->store('resumes', 'public');
        
        // Create file record
        $file = \App\Models\File::create([
            'user_id' => Auth::id(),
            'file_name' => $request->file('resume')->getClientOriginalName(),
            'file_url' => 'storage/' . $resumePath,
            'file_type' => $request->file('resume')->getClientOriginalExtension(),
            'file_size' => $request->file('resume')->getSize(),
            'file_category' => 'cv', // Changed from 'resume' to 'cv'
        ]);
        
        // Create application
        $application = Application::create([
            'user_id' => Auth::id(),
            'opportunity_id' => $opportunityId,
            'resum_file_id' => $file->id,
            'cover_letter' => $request->cover_letter,
            'status' => 'pending',
            'applied_at' => now(),
        ]);
        
        // Create notification for organization
        \App\Models\Notification::create([
            'user_id' => $opportunity->organization->user_id, // Organization user ID
            'type' => 'new_application',
            'title' => 'تقديم جديد على فرصة',
            'message' => 'تقدم ' . Auth::user()->name . ' على فرصة: ' . $opportunity->title,
            'data' => json_encode([
                'application_id' => $application->id,
                'opportunity_id' => $opportunity->id,
                'applicant_name' => Auth::user()->name,
            ]),
            'is_read' => false,
        ]);
        
        return redirect()->route('volunteer.applications')
            ->with('success', 'تم تقديم طلبك بنجاح! سيتم مراجعته من قبل المؤسسة.');
    }

    /**
     * Withdraw application
     */
    public function withdraw($applicationId)
    {
        $application = Application::where('id', $applicationId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        if ($application->status != 'pending') {
            return back()->with('error', 'لا يمكن سحب الطلب بعد اتخاذ قرار بشأنه');
        }
        
        $application->delete();
        
        return back()->with('success', 'تم سحب الطلب بنجاح');
    }
}

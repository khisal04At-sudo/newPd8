<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FileUploadHelper;
use Illuminate\Support\Facades\Auth;

class OrganizationVerificationController extends Controller
{
    /**
     * عرض صفحة رفع مستندات التحقق للمؤسسة
     */
    public function showUploadForm()
    {
        $user = Auth::user();
        
        if (!$user->is_verified) {
            return redirect()->route('verify-otp');
        }

        if ($user->user_type !== 'organization') {
            return redirect()->route('dashboard');
        }

        $organization = $user->organization;
        
        if (!$organization) {
            return redirect()->route('home')->with('error', 'بيانات المؤسسة غير موجودة');
        }

        // If already verified, go to dashboard
        if ($organization->verified) {
            return redirect()->route('dashboard');
        }

        return view('auth.organization-verify-documents', compact('organization'));
    }

    /**
     * حفظ مستندات التحقق
     */
    public function storeDocuments(Request $request)
    {
        $request->validate([
            'documents' => 'required|array|min:1',
            'documents.*' => 'required|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'documents.required' => 'يرجى رفع ملف واحد على الأقل للتحقق',
            'documents.*.mimes' => 'يجب أن تكون الملفات بصيغة PDF أو صور (JPG, PNG)',
        ]);

        $user = Auth::user();
        $organization = $user->organization;

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                FileUploadHelper::upload(
                    $file, 
                    'verification_document', 
                    $user->id, 
                    $organization->id
                );
            }
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'تم رفع المستندات بنجاح. سيتم مراجعتها من قبل الإدارة وتفعيل حسابكم قريباً.');
    }
}

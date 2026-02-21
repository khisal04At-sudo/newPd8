<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CertificateService
{
    /**
     * Check if an application is eligible for a certificate
     */
    public function isEligible(Application $application): bool
    {
        // Criteria:
        // 1. Status is completed
        // 2. Attended hours >= 70% of total hours
        // 3. Commitment score >= 3
        
        $opportunity = $application->opportunity;
        $totalHours = $opportunity->total_hours ?? 0;
        
        if ($totalHours <= 0) return false;
        
        $percentage = ($application->attended_hours / $totalHours) * 100;
        
        return in_array($application->status, ['completed', 'executing']) 
            && $percentage >= 70 
            && $application->commitment_score >= 60;
    }

    /**
     * Generate a certificate for an application
     */
    public function generate(Application $application)
    {
        if (!$this->isEligible($application)) {
            // The original method returned null.
            // The instruction implies a change to return a redirect with an error message.
            // However, service classes typically don't handle HTTP responses directly.
            // For faithfulness to the instruction, while maintaining service class integrity,
            // we'll return a specific error message string or throw an exception.
            // Given the snippet's intent, we'll return a string indicating the error.
            // A controller calling this service would then handle the redirect.
            return 'المتقدم غير مؤهل للحصول على شهادة (يجب حضور 70% على الأقل وتقييم 60% فأعلى، وتكون حالة الطلب "قيد التنفيذ" أو "مكتمل").';
        }
        
        // Check if certificate already exists
        if (Certificate::where('application_id', $application->id)->exists()) {
            return Certificate::where('application_id', $application->id)->first();
        }

        $opportunity = $application->opportunity;
        $user = $application->user;
        $organization = $opportunity->organization;

        $recipientName = $application->certificate_name ?: $user->name;

        $certificateNumber = 'CERT-' . strtoupper(Str::random(8)) . '-' . $application->id;
        $totalHours = $opportunity->total_hours;
        $attendedHours = $application->attended_hours;
        $percentage = ($attendedHours / $totalHours) * 100;

        // Generate PDF content (using Blade template)
        $pdf = Pdf::loadView('certificates.certificate_template', [
            'application' => $application,
            'user' => $user,
            'recipientName' => $recipientName,
            'opportunity' => $opportunity,
            'organization' => $organization,
            'certificateNumber' => $certificateNumber,
            'percentage' => $percentage,
            'issueDate' => now()->format('Y-m-d'),
        ])
        ->setPaper('a4', 'landscape')
        ->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'DejaVu Sans',
            'enable_font_subsetting' => true,
            'isFontSubsettingEnabled' => true
        ]);

        // Save PDF to storage
        $fileName = 'certificates/' . $certificateNumber . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        // Create File record for the certificate
        $fileRecord = \App\Models\File::create([
            'user_id' => $user->id,
            'organization_id' => $organization->id,
            'opportunity_id' => $opportunity->id,
            'file_name' => $certificateNumber . '.pdf',
            'file_type' => 'pdf',
            'file_url' => $fileName,
            'file_size' => Storage::disk('public')->size($fileName),
            'file_category' => 'certificate',
        ]);

        // Create Certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
            'application_id' => $application->id,
            'file_id' => $fileRecord->id,
            'title' => 'شهادة إتمام ' . ($opportunity->type === 'volunteering' ? 'فرصة تطوعية' : 'برنامج تدريبي'),
            'certificate_number' => $certificateNumber,
            'issue_date' => now(),
            'is_downloadable' => true,
            'attendance_percentage' => $percentage,
            'total_hours' => $totalHours,
            'attended_hours' => $attendedHours,
            'organization_name' => $organization->name,
            'opportunity_title' => $opportunity->title,
            'file_url' => $fileName,
        ]);

        // Send notification to user
        \App\Models\Notification::create([
            'user_id' => $user->id,
            'title' => 'صدرت شهادتك الجديدة! 🎓',
            'message' => 'تهانينا! لقد حصلت على شهادة إتمام لـ "' . $opportunity->title . '" من ' . $organization->name,
            'type' => 'achievement',
            'data' => json_encode([
                'certificate_id' => $certificate->id,
                'opportunity_id' => $opportunity->id,
                'link' => route('volunteer.certificates'),
            ]),
            'is_read' => false,
        ]);

        return $certificate;
    }

    /**
     * Preview a certificate for an application
     */
    public function preview(Application $application)
    {
        $opportunity = $application->opportunity;
        $user = $application->user;
        $organization = $opportunity->organization;

        $recipientName = $application->certificate_name ?: $user->name;
        $totalHours = $opportunity->total_hours;
        $attendedHours = $application->attended_hours;
        $percentage = ($totalHours > 0) ? ($attendedHours / $totalHours) * 100 : 0;

        return Pdf::loadView('certificates.certificate_template', [
            'application' => $application,
            'user' => $user,
            'recipientName' => $recipientName,
            'opportunity' => $opportunity,
            'organization' => $organization,
            'certificateNumber' => 'PREVIEW-XXXX',
            'percentage' => $percentage,
            'issueDate' => now()->format('Y-m-d'),
        ])->setPaper('a4', 'landscape');
    }
}

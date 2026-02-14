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
        
        return $application->status === 'completed' 
            && $percentage >= 70 
            && $application->commitment_score >= 3;
    }

    /**
     * Generate a certificate for an application
     */
    public function generate(Application $application)
    {
        if (!$this->isEligible($application)) {
            return null;
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

        // Create Certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'opportunity_id' => $opportunity->id,
            'application_id' => $application->id,
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

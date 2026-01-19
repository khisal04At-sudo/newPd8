<?php

namespace App\Helpers;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload a file and save to database
     * 
     * @param UploadedFile $file
     * @param string $category
     * @param int|null $userId
     * @param int|null $organizationId
     * @param int|null $opportunityId
     * @return File
     */
    public static function upload(
        UploadedFile $file, 
        $category, 
        $userId = null, 
        $organizationId = null, 
        $opportunityId = null
    ) {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('uploads/' . $category, $fileName, 'public');

        return File::create([
            'user_id' => $userId,
            'organization_id' => $organizationId,
            'opportunity_id' => $opportunityId,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_url' => 'storage/' . $path,
            'file_size' => $file->getSize(),
            'file_category' => $category,
        ]);
    }

    /**
     * Delete a file from storage and database
     */
    public static function delete($fileId)
    {
        $file = File::find($fileId);
        if ($file) {
            $storagePath = str_replace('storage/', '', $file->file_url);
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
            }
            $file->delete();
        }
    }
}

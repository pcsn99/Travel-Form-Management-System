<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use Illuminate\Http\Request;
use App\Models\UserProfilePhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserFileController extends Controller
{
    public function download($id)
    {
        $file = UserFile::findOrFail($id);
    
        if (!Storage::disk('shared')->exists($file->file_path)) {
            abort(404, 'File not found in shared storage');
        }
    
        return Storage::disk('shared')->download($file->file_path, $file->original_name);
    }
    
}

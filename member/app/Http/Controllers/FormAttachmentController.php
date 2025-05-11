<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormAttachment;
use Illuminate\Support\Facades\Storage;

class FormAttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB max
            'form_type' => 'required|in:local,Overseas',
            'form_id' => 'required|integer'
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = 'form_attachments/' . $filename;

        
        Storage::disk('shared')->put($path, file_get_contents($file));

        
        FormAttachment::create([
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'local_travel_form_id' => $request->form_type === 'local' ? $request->form_id : null,
            'Overseas_travel_form_id' => $request->form_type === 'Overseas' ? $request->form_id : null,
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function download($id)
    {
        $file = FormAttachment::findOrFail($id);

        if (!Storage::disk('shared')->exists($file->file_path)) {
            abort(404);
        }

        return Storage::disk('shared')->download($file->file_path, $file->original_name);
    }

    public function destroy($id)
    {
        $attachment = FormAttachment::findOrFail($id);

        // Delete file from shared storage
        Storage::disk('shared')->delete($attachment->file_path);

        $attachment->delete();

        return back()->with('success', 'File deleted.');
    }
}

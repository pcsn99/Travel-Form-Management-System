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
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
            'form_type' => 'required|in:local,Overseas',
            'form_id' => 'required|integer'
        ]);

        $path = $request->file('file')->store('attachments', 'public');

        FormAttachment::create([
            'file_path' => $path,
            'original_name' => $request->file('file')->getClientOriginalName(),
            'local_travel_form_id' => $request->form_type === 'local' ? $request->form_id : null,
            'Overseas_travel_form_id' => $request->form_type === 'Overseas' ? $request->form_id : null,
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function download($id)
    {
        $file = FormAttachment::findOrFail($id);
        return Storage::disk('public')->download($file->file_path, $file->original_name);
    }

    public function destroy($id)
    {
        $attachment = \App\Models\FormAttachment::findOrFail($id);
    
        // Delete file from storage
        \Storage::disk('public')->delete($attachment->file_path);
    
        // Delete from DB
        $attachment->delete();
    
        return back()->with('success', 'File deleted.');
    }
}


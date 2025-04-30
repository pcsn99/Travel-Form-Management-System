<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserFileController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('file');

        if ($file) {
            $path = 'public/user_uploads/' . time() . '_' . $file->getClientOriginalName();
            Storage::disk('shared')->put($path, file_get_contents($file));

            UserFile::create([
                'user_id' => auth()->id(),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'type' => $request->type,
            ]);
        }

        return back()->with('success', 'File uploaded!');
    }

    public function destroy($id)
    {
        $file = UserFile::findOrFail($id);

        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('shared')->delete($file->file_path);
        $file->delete();

        return back()->with('success', 'File deleted.');
    }

    public function download($id)
    {
        $file = UserFile::where('user_id', auth()->id())->findOrFail($id);

        if (!Storage::disk('shared')->exists($file->file_path)) {
            abort(404);
        }

        return Storage::disk('shared')->download($file->file_path, $file->original_name);
    }
}

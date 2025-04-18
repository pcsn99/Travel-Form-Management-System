<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function showForm()
    {
        $user = Auth::user(); // assume using default auth or admin guard
        return view('admin.signature-upload', compact('user'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $user = Auth::user();

        // Delete old signature if exists
        if ($user->signature) {
            Storage::disk('public')->delete($user->signature);
        }

        $path = $request->file('signature')->store('signatures', 'public');

        $user->update(['signature' => $path]);

        return back()->with('success', 'Signature uploaded successfully!');
    }
}


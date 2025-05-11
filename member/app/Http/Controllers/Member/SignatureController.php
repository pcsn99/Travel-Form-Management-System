<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();
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
            Storage::disk('shared')->delete($user->signature);
        }

        // Store new signature in shared disk
        $path = $request->file('signature')->store('signatures', 'shared');

        // Update user's signature path
        $user->update(['signature' => $path]);

        //return redirect()->route('account')->with('success', 'Signature uploaded successfully!');
        return back()->with('success', 'Signature uploaded successfully!');
    }
}

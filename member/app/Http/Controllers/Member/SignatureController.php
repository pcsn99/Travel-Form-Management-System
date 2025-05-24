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

   
    if ($user->signature) {
        Storage::disk('shared')->delete($user->signature);
    }

    $path = $request->file('signature')->store('signatures', 'shared');

    $user->update(['signature' => $path]);

    if ($request->expectsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Signature uploaded successfully!',
            'signature_url' => asset('shared/' . $path)
        ]);
    }

    return back()->with('success', 'Signature uploaded successfully!');
}
}

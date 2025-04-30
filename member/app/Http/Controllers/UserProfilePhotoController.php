<?php

namespace App\Http\Controllers;

use App\Models\UserProfilePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfilePhotoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $existing = UserProfilePhoto::where('user_id', Auth::id())->first();
        if ($existing) {
            Storage::delete($existing->photo_path);
            $existing->delete();
        }

        $path = $request->file('photo')->store('profile_photos');

        UserProfilePhoto::create([
            'user_id' => Auth::id(),
            'photo_path' => $path,
        ]);

        return back()->with('success', 'Profile photo updated!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function show()
    {
        $user = Auth::user();

       //dd($user); 
        return view('account', [
            'user' => $user,
            'files' => $user->files->groupBy('type'),
            'photo' => $user->profilePhoto()->first(),
        ]);
    }
    

    public function update(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        
        return redirect()->back()->with('success', 'Account updated successfully!');
    }
}

<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function sendResetPin(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No account found with that email.');
        }

        $pin = strtoupper(\Str::random(6));
        $user->password = Hash::make($pin);
        $user->save();


        Mail::raw("Your temporary login PIN is: $pin\nPlease log in and change your password immediately.", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Temporary Login PIN - Loyola Travel System');
        });

        return back()->with('success', 'Temporary PIN sent! Check your email.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminAccountController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        return view('admin.settings.admin-accounts.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.settings.admin-accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin'
        ]);

        return redirect()->route('admin-accounts.index')->with('success', 'Admin account created successfully.');
    }

    public function edit($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.settings.admin-accounts.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin-accounts.index')->with('success', 'Admin account updated.');
    }

    public function destroy($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if ($admin->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $admin->delete();

        return redirect()->route('admin-accounts.index')->with('success', 'Admin account deleted.');
    }
}

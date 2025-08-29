<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClientAuthController extends Controller
{
    //

    public function showLoginForm() {
        return view('client.login');
    }

    public function login(Request $request) {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required|string',
        ]);

        // if (Auth::guard('client')->attempt($request->only('email', 'password'))) {
        //     return redirect()->route('client.dashboard');
        // }
        if(Auth::guard('client')->attempt([
            'email'     => $request->email,
            'password'  => $request->password,
            'status'    => 1
        ])) {
            return redirect()->route('client.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function passwordForm()
    {
        return view('client.forgetPassword');
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password'  => 'required|confirmed|min:6',
        ]);

        User::where('email', $request->email)
            ->update(['password'  => Hash::make($request->password)]);

        // return redirect()->route('client.login')->with('success', 'Password has been reset successfully!');
        return redirect()->back()->with('success', 'Password reset successfully!');
    }

}

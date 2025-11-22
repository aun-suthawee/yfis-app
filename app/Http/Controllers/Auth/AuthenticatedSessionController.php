<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->only(['create', 'store']);
        $this->middleware('auth')->only('destroy');
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Try to authenticate with username first
        $credentials = [
            'username' => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Check if user needs to complete profile (first-time setup)
            $user = Auth::user();
            if (empty($user->name) || empty($user->tel)) {
                return redirect()
                    ->route('profile.edit')
                    ->with('warning', 'กรุณากรอกข้อมูลส่วนตัวของคุณก่อนใช้งานระบบ');
            }
            
            return redirect()->intended(route('dashboard.index'));
        }

        // If username login fails, try with email
        $credentials = [
            'email' => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Check if user needs to complete profile (first-time setup)
            $user = Auth::user();
            if (empty($user->name) || empty($user->tel)) {
                return redirect()
                    ->route('profile.edit')
                    ->with('warning', 'กรุณากรอกข้อมูลส่วนตัวของคุณก่อนใช้งานระบบ');
            }
            
            return redirect()->intended(route('dashboard.index'));
        }

        throw ValidationException::withMessages([
            'login' => __('ไม่พบผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

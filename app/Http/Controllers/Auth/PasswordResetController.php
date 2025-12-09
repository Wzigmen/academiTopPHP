<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCodeMail;
use App\Mail\PasswordChangedMail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put('password_reset_code_' . $request->email, $code, now()->addMinutes(10));

        Mail::to($request->email)->send(new PasswordResetCodeMail($code));

        return redirect()->route('password.code.form')->with('email', $request->email);
    }

    public function showCodeForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:6',
        ]);

        $cachedCode = Cache::get('password_reset_code_' . $request->email);

        if (!$cachedCode || $cachedCode !== $request->code) {
            return back()->withErrors(['code' => 'Неверный или устаревший код подтверждения.']);
        }

        Cache::forget('password_reset_code_' . $request->email);
        $request->session()->put('password_reset_email', $request->email);

        return redirect()->route('password.reset');
    }

    public function showPasswordForm()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.passwords.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->email !== session('password_reset_email')) {
            return back()->withErrors(['email' => 'Неверный email.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        Mail::to($user->email)->send(new PasswordChangedMail());

        $request->session()->forget('password_reset_email');

        return redirect()->route('login')->with('status', 'Ваш пароль был успешно изменен. Теперь вы можете войти.');
    }
}

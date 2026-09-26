<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user() !== null) {
            $adminEmail = config('admin.email');

            abort_unless(
                is_string($adminEmail)
                && hash_equals(mb_strtolower($adminEmail), mb_strtolower($request->user()->email)),
                403,
            );

            return redirect()->route('admin.dashboard');
        }

        return view('admin.login', [
            'adminConfigured' => filled(config('admin.email')) && filled(config('admin.password')),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $adminEmail = config('admin.email');

        if (! is_string($adminEmail) || blank(config('admin.password'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Admini nuk është konfiguruar ende. Vendosni ADMIN_EMAIL dhe ADMIN_PASSWORD në mjedis dhe krijoni përdoruesin admin.']);
        }

        if (! hash_equals(mb_strtolower($adminEmail), Str::lower($validated['email']))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Kredencialet nuk janë të sakta.']);
        }

        if (! Auth::attempt([
            'email' => Str::lower($adminEmail),
            'password' => $validated['password'],
        ])) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Kredencialet nuk janë të sakta.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

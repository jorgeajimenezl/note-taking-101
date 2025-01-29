<?php

namespace App\Http\Controllers;

use Google\Service\Tasks;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes([Tasks::TASKS_READONLY])
            ->with([
                'access_type' => 'online',
                'prompt' => 'consent',
                'login_hint' => auth()->user()->email,
            ])
            ->redirect();
    }

    public function callback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect(route('dashboard', absolute: false))
                ->with('error', 'Google authentication failed.');
        }

        request()->session()->put('google_token', $user->token);

        return redirect()
            ->intended(route('dashboard', absolute: false))
            ->with('success', 'Google authentication successful.');
    }
}

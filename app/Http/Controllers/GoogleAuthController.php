<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect('/')->with('error', 'Google authentication failed.');
            ray($e)->red();
        }

        ray($user)->green();

        return back()->with('access_token', $user->token);
    }
}

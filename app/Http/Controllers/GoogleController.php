<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class GoogleController extends Controller
{
    public function loginGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
  public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'role' => 'Applicant',
                    'password' => Hash::make(Str::random(32)),
                ]);
            }

            Auth::login($user, true);

            return redirect()->route('application.create');

        } catch (Exception $e) {
            logger()->error('Google login error: ' . $e->getMessage());

            return redirect()->route('login')
                ->withErrors(['google' => 'Failed to authenticate']);
        }
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect user to Google OAuth
     */
    public function loginGoogle()
    {
        // If user is already logged in, redirect them away from login
        if (Auth::check()) {
            return redirect()->route('dashboard')
                ->with('info', 'You are already logged in.');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // If user is already logged in, prevent email change
            if (Auth::check()) {
                if (Auth::user()->email !== $googleUser->getEmail()) {
                    return redirect()->route('dashboard')
                        ->with('error', 'You cannot change your account email while logged in.');
                }

                return redirect()->route('dashboard');
            }

            // For guests: find user by google_id or create new
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
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

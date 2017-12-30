<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        $email = $facebookUser->getEmail();

        $user = User::where('AuthProviderEmail', $email)->first();
        if($user) {
            Auth::login($user);
            return redirect('/')->with('status', 'Login successful');
        } else {
            $newUser = User::create([
                'Email' => $email,
                'AuthProviderEmail' => $email,
                'Name' => $facebookUser->getName(),
            ]);
            if($newUser) {
                Auth::login($newUser);
                return redirect('/')->with('status', 'New user created');
            }
        }
    }

    public function googleRedirect() {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback() {
        $googleUser = Socialite::driver('google')->user();
        $email = $googleUser->getEmail();

        $user = User::where('AuthProviderEmail', $email)->first();
        if($user) {
            Auth::login($user);
            return redirect('/')->with('status', 'Login successful');
        } else {
            $newUser = User::create([
                'Email' => $email,
                'AuthProviderEmail' => $email,
                'Name' => $googleUser->getName(),
            ]);
            if($newUser) {
                Auth::login($newUser);
                return redirect('/')->with('status', 'New user created');
            }
        }
    }

    public function anonymousRedirectAndCallback() {
        if(env('APP_ENV', 'prod')==='local') {
            $user = User::where('AuthProviderEmail', 'anonymous@gmail.com')->first();
            if($user) {
                Auth::login($user);
                return redirect('/');
            } else {
                $newUser = User::create([
                    'Email' => 'anonymous@gmail.com',
                    'AuthProviderEmail' => 'anonymous@gmail.com',
                    'Name' => 'Dana Scully'
                ]);
                if($newUser) {
                    Auth::login($newUser);
                    return redirect('/');
                }
            }
        } else {
            return redirect('/404');
        }
    }
}

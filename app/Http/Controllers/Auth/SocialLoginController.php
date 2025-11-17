<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            if ($provider == 'google') {
                //Check if the user exists
                $user = User::where('email', $socialUser->getEmail())->first();
                if (!$user) {
                    return redirect()->route('login')->with('error', 'Email not registered with us!');
                    // $user = new User();
                    // $user->name = $socialUser->getName();
                    // $user->email = $socialUser->getEmail();
                    // $user->password = bcrypt('123456');
                    // $user->save();
                }
            } else if ($provider == 'facebook') {
                $user = User::where('email', $socialUser->email)->first();
                if (!$user) {
                    return redirect()->route('login')->with('error', 'Email not registered with us!');
                    // $user = new User();
                    // $user->name = $socialUser->name;
                    // $user->email = $socialUser->email;
                    // $user->password = bcrypt('123456');
                    // $user->save();
                }
            }
            Auth::login($user);
            return redirect()->route('home'); // Redirect to your dashboard
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to login.');
        }
    }
}

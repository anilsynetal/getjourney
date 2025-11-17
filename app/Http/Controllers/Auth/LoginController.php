<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Rules\ReCaptcha;
use App\Utils\Util;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('throttle:3,5')->only('login');
    }

    public function showLoginForm()
    {
        $setting = array();
        $setting['google_recaptcha_status'] = Util::getSettingValue('google_recaptcha_status') ?? null;
        $setting['google_recaptcha_site_key'] = Util::getSettingValue('google_recaptcha_site_key') ?? null;
        $setting['google_recaptcha_secret_key'] = Util::getSettingValue('google_recaptcha_secret_key') ?? null;
        $setting['facebook_login'] = Util::getSettingValue('facebook_login') ?? null;
        $setting['facebook_client_id'] = Util::getSettingValue('facebook_client_id') ?? null;
        $setting['facebook_client_secret'] = Util::getSettingValue('facebook_client_secret') ?? null;
        $setting['google_login'] = Util::getSettingValue('google_login') ?? null;
        $setting['google_client_id'] = Util::getSettingValue('google_client_id') ?? null;
        $setting['google_client_secret'] = Util::getSettingValue('google_client_secret') ?? null;
        return view('auth.login', compact('setting'));
    }

    public function login(Request $request)
    {

        try {
            $this->validateLogin($request);
        } catch (ValidationException $e) {
            return back()->withInput($request->only('email'))->withErrors($e->errors());
        }
        // Check user credentials
        $user = User::where('email', $request->email)->first();
        //Get Role
        $role = $user->getRoleNames()->first();

        if (isset($request->role) && $role != $request->role) {
            return back()->with('error', 'Credentials do not match with selected role.');
        }
        if ($user && $user->status == 0) {
            return back()->with('error', 'Your account is inactive. Please contact the administrator.');
        }
        //Validate Expiry
        // $expiry = $this->validateExpiry();
        // if ($expiry['status'] == false) {
        //     return back()->with('error', $expiry['message']);
        // }

        if (!$user || !Auth::validate(['email' => $request->email, 'password' => $request->password])) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => trans('auth.failed'),
            ]);
        }
        $email_2fa_status = Util::getSettingValue('email_2fa_status');
        $sms_2fa_status = Util::getSettingValue('sms_2fa_status');
        if ($email_2fa_status == 'on' || $sms_2fa_status == 'on') {
            // Generate OTP (6-digit)
            // $otp = rand(100000, 999999);
            $otp = 989898;
            // Store OTP in cache (expires in 5 minutes)
            if ($email_2fa_status == 'on') {
                Cache::put('otp_' . $user->email, $otp, now()->addMinutes(5));
                // Send OTP via email
                Mail::to($user->email)->send(new OtpMail($otp, $user->name));
                // Store email in session for verification step
                session(['otp_email' => $user->email]);
            }
            session(['user_email' => $user->email]);
            if ($sms_2fa_status == 'on') {
                Cache::put('otp_' . $user->mobile, $otp, now()->addMinutes(5));
                session(['otp_mobile' => $user->mobile]);
                session(['country_code' => $user->country_code]);
            }
            // Redirect to OTP verification page
            return redirect()->route('otp.verify');
        } else {
            Auth::login($user);
            return redirect()->intended($this->redirectPath());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    // Show OTP verification form
    public function otpVerify()
    {
        if (!session('otp_email') && !session('otp_mobile')) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }
        $email_2fa_status = Util::getSettingValue('email_2fa_status');
        $sms_2fa_status = Util::getSettingValue('sms_2fa_status');
        $masked_email = '';
        $masked_mobile = '';
        if ($email_2fa_status == 'on') {
            $masked_email = Util::maskEmail(session('otp_email'));
        }
        if ($sms_2fa_status == 'on') {
            $masked_mobile = session('country_code') . ' ' . Util::maskMobile(session('otp_mobile'));
        }

        return view('auth.verify-otp', compact('masked_email', 'masked_mobile'));
    }

    //Resend OTP
    public function resendOtp(Request $request)
    {
        $email = session('otp_email');
        $mobile = session('otp_mobile');

        if (!$email && !$mobile) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // Generate OTP (6-digit)
        // $otp = rand(100000, 999999);
        $otp = 989898;

        // Store OTP in cache (expires in 5 minutes)
        Cache::put('otp_' . $email, $otp, now()->addMinutes(5));
        Cache::put('otp_' . $mobile, $otp, now()->addMinutes(5));

        // Send OTP via email
        Mail::to($email)->send(new OtpMail($otp, User::where('email', $email)->first()->name));

        return back()->with('success', 'OTP has been resent.');
    }

    // Verify OTP
    public function validateOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $email = session('otp_email');
        $user_email = session('user_email');
        $mobile = session('otp_mobile');

        if (!$email && !$mobile) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }

        // Retrieve OTP from cache
        $cachedOtp = Cache::get('otp_' . $email) ?? Cache::get('otp_' . $mobile);


        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        // OTP is valid, log in user
        Auth::login(User::where('email', $user_email)->first());

        // Clear OTP from cache and session
        Cache::forget('otp_' . $email);
        session()->forget('otp_email');
        session()->forget('otp_mobile');
        session()->forget('user_email');

        return redirect()->intended($this->redirectPath());
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $google_recaptcha_status = Setting::where('key', 'google_recaptcha_status')->first()->value ?? '';
        if ($google_recaptcha_status == 'on') {
            $request->validate(
                [
                    'g-recaptcha-response' => ['required', new ReCaptcha]
                ],
                [
                    'g-recaptcha-response.required' => 'Please complete the reCAPTCHA'
                ]
            );
        }
    }
}

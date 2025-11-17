<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Utils\Util;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'digits:10'],
            'role' => ['required', 'in:Doctor,Mr'],
            'specialization' => ['required_if:role,Doctor', 'nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        //Generate Random Username
        $username = Str::random(10);
        if ($data['role'] === 'Doctor') {
            $prefix = 'DOC';
        } else {
            $prefix = 'MR';
        }
        $unique_code = Util::generateUniqueCode($prefix);
        $role = Role::firstOrCreate(['name' => $data['role']]);
        $user = User::create([
            'name' => $data['name'],
            'unique_code' => $unique_code,
            'username' => strtoupper($username),
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'role' => 'user',
            'specialization' => $data['role'] === 'Doctor' ? $data['specialization'] : null,
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole($role);
        return $user;
    }

    protected function registered(Request $request, $user)
    {
        event(new Registered($user));
        $this->guard()->logout();
        return redirect()->route('verification.notice')->with('status', 'We have sent you a verification link!');
    }
}

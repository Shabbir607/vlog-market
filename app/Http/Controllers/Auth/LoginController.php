<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Socialite;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active',
            'role_id' => 1,
        ];
    }

    public function __construct()
    {

        $this->middleware('guest')->except('logout');

        if (Auth::check() && Auth::user()->role_id == 1) {
            $this->redirectTo = route('admin.dashboard');
        } elseif (Auth::check() && Auth::user()->role_id == 2) {

            $this->redirectTo = route('user.dashboard');
        } elseif (Auth::check() && Auth::user()->role_id == 3) {
            $this->redirectTo = route('admin.dashboard');
        }
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
{
    try {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        $user = User::where(['email' => $userSocial->getEmail()])->first();

        if ($user) {

            Auth::guard('web')->login($user);
            return redirect()->route('/dashboard')->with('success', 'You are logged in from ' . $provider);
        } else {
            $newUser = User::create([
                'name' => $userSocial->getName(),
                'email' => $userSocial->getEmail(),
                'image' => $userSocial->getAvatar(),
                'provider_id' => $userSocial->getId(),
                'provider' => $provider,
            ]);

            Auth::guard('web')->login($newUser);

            // Redirect to the 'home' route
            return redirect()->route('/select-country');
        }
    } catch (\Exception $e) {

        return redirect()->route('/select-country')->with('error', 'Error during social login: ' . $e->getMessage());
    }
 }

}

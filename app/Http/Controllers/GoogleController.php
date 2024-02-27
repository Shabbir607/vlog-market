<?php

namespace App\Http\Controllers;
use Socialite;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // $user = Socialite::driver('google')->user();

        // // $user contains the user details returned by Google
        // // You can now perform actions with this user, such as storing it in the database

        // // Example: Get user email
        // $email = $user->getEmail();

        // Your custom logic here...

        return redirect('/home'); // Redirect wherever you want after authentication
    }
}

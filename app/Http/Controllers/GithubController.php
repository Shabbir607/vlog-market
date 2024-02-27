<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class GithubController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        // $user contains user details from GitHub

        // Add your logic to handle the authenticated user

        return redirect()->route('home');
    }
}


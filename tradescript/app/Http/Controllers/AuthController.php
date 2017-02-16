<?php

namespace App\Http\Controllers;

use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Auth;
use \Cloudder;

class AuthController extends Controller
{

    /**
     * @var SteamAuth
     */
    private $steam;

    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    public function getLogin()
    {
        // Check if we received a response from Steam
        if ($this->steam->validate()) {
            // Fetch the user information
            $info = $this->steam->getUserInfo();
            // Check if we got a valid response from Steam
            if ( ! is_null($info)) {
                // Try to fetch the user with SteamID64
                $user = User::where('steamid', $info->getSteamID64())->first();
                // Check if user exists
                if( ! is_null($user)) {
                    // Log the user in
                    Auth::login($user, true);
                    // Update the user
                    $user->username = $info->getNick();
                    $user->avatar_url = $info->getProfilePictureFull();
                    $user->save();
                    // Redirect to site
                    return redirect('/');
                } else {
                    // Create the user
                    $user = new User;
                    if( ! is_null(session('referred_by'))) {
                        $user->referred_by = session('referred_by');
                    }
                    $user->username = $info->getNick();
                    $user->avatar_url = $info->getProfilePictureFull();
                    $user->steamid = $info->getSteamID64();
                    $user->save();
                    // Log the user in
                    Auth::login($user, true);
                    // Redirect to site
                    return redirect('settings');
                }
            }
        } else {
            // Redirect to Steam Auth page
            return $this->steam->redirect();
        }
    }

    public function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }

}

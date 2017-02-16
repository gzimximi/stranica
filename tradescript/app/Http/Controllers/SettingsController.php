<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Auth;

class SettingsController extends Controller
{
    public function getIndex() {
    	return view('settings');
    }

    public function postIndex(Request $request) {
    	$validator = Validator::make($request->all(), [
            'trade_url' => 'required|max:255',
            'terms_of_service' => 'accepted'
        ]);

        if($validator->fails()) {
            return redirect('settings')
                        ->withErrors($validator)
                        ->withInput();
        }

        $token = trim($request->get('trade_url'));

        if(strpos($token, 'token=') !== false) {
            $token = substr($token, strpos($token, "token=") + 6);
        }
        
        $user = User::find(Auth::user()->id);
        $user->tradeurl = $request->get('trade_url');
        $user->tradeurl_token = $token;
        $user->save();

        return redirect('settings')->with('success', true);
    }
}

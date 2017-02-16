<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

use Cache;

class LanguageController extends Controller
{
    public function switchLang($lang)
    {
        if (in_array($lang, ['en', 'ee', 'ru'])) {
            Session::set('applocale', $lang);
        }
        return redirect()->back();
    }

    public function queryAPI($ip)
    {
    	// ayy lmao
    	return 'en';
    	return Cache::get('locale_' . $ip, function() use ($ip) {
			if (strpos($ip, '192.168') !== false) {
			    $ip = '85.253.94.220';
			}

	    	$client = new \GuzzleHttp\Client();
			/*
			$res = $client->request('GET', 'http://ip-api.com/json/' . $ip);
			if( $res->getStatusCode() == 200 ) {
				$json = json_decode($res->getBody(), true);

				if( $json['status'] == "success" ) {
					
					if( $json['country'] == "Estonia" or $json['countryCode'] == "EE" ) {
						return 'ee';
					}
					if( $json['country'] == "Russia" or $json['countryCode'] == "RU" or $json['countryCode'] == "RUS" ) {
						return 'ru';
					}
					return 'en';

				}
			}
			*/
			$res = $client->request('GET', 'http://freegeoip.net/json/' . $ip);
			if( $res->getStatusCode() == 200 ) {
				$json = json_decode($res->getBody(), true);

				if( $json['country_name'] == "Estonia" or $json['country_code'] == "EE" ) {
					return 'ee';
				}
				if( $json['country_name'] == "Russia" or $json['country_code'] == "RU" or $json['country_code'] == "RUS" ) {
					return 'ru';
				}
				return 'en';

			}

			return 'en';
		});
    }
}

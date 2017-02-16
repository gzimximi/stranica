<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Cache;
use Log;
use Auth;
use App\Item;
use App\Trade;
use App\ItemHistory;

class SteamMarketController extends Controller
{
    
	public function getSetup() {
		ignore_user_abort(true);//if caller closes the connection (if initiating with cURL from another PHP, this allows you to end the calling PHP script without ending this one)
		set_time_limit(0);

		$client = new Client();

		$items = Item::whereItemNameid('0')->get();
		$count = 0;
		$interval = 0;
		foreach($items as $item) {

			$url = 'http://steamcommunity.com/market/listings/730/' . $item->market_hash_name;
			$response = $client->request('GET', $url);

			if($response->getStatusCode() != 200) {
				dd('Fucked up at ' . $count . ' with ' . $response->getStatusCode());
			}

			$body = $response->getBody()->getContents();

			if(strpos($body, 'var line1') !== false) {
				preg_match("/(var line1)\=([^)]+)\;/", $body, $matches);
			
				if(isset($matches[2])) {
					$histories = json_decode($matches[2]);

					foreach($histories as $history) {
						$date = $history[0];
						$date = substr($date, 0, -3) . '00';
						$sold_at = new \DateTime($date);

						if( ! ItemHistory::whereItemId($item->id)->whereSoldAt($sold_at)->count()) {
							$historyMdl = new ItemHistory;
							$historyMdl->item_id = $item->id;
							$historyMdl->price = $history[1];
							$historyMdl->sales = $history[2];
							$historyMdl->sold_at = $sold_at;
							$historyMdl->save();
						}
					}
				}
			}

			if(strpos($body, 'Market_LoadOrderSpread') === false) {
				continue;
			}

			preg_match("/(Market_LoadOrderSpread)\( ([^)]+) \)/", $body, $matches);
			$item_nameid = $matches[2];			

			$itemMdl = Item::find($item->id);
			$itemMdl->item_nameid = $item_nameid;
			$itemMdl->save();

			echo $item->market_hash_name . '<br>';
			$count++;
			$interval++;

			if($interval == 10) {
				sleep(45);
				$interval = 0;
			} else {
				sleep(10);
			}
		}

		
	}

}

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

class SteamController extends Controller
{
    
	public function __construct() {
		$this->baseUrl = 'http://steamcommunity.com';
	}

	public function getInventory($options) {
		return $this->_getInventory($options);
	}

	public function _getInventory($options) {
		$client = new Client();
		$response = $client->request('GET', $this->baseUrl . '/profiles/' . $options['steamid'] . '/inventory/json/' . $options['appid'] . '/' . $options['contextid'] 
									/*, ['proxy' => 'http://108.59.14.208:13040']*/);

		if($response->getStatusCode() != 200) {
			return ['success' => false, 'message' => 'Steam is having problems. Try again later.', 'private' => true];
		}

		$json = json_decode($response->getBody()->getContents(), true);
		if($json['success'] == false) {
			// Log the error
			Log::error('Failed to fetch inventory (method _getInventory in SteamController)', [
				'response_data' => $json,
				'request_options' => $options 
			]);
			if(isset($json['Error'])) {
				if($json['Error'] == 'This profile is private.') {
					$json['Error'] = 'This profile or inventory is private.';
				}
			} else {
				$json['Error'] = 'Could not fetch your inventory. Check that your Steam privacy settings are public. If the error still occurs please email us!';
				if(isset($json['message'])) $json['Error'] = $json['message'];
				if(isset($json['error'])) $json['Error'] = $json['error'];
			}
			return ['success' => false, 'message' => $json['Error'], 'private' => true];
		}

		return ['success' => true, 'data' => $json, 'private' => false];
	}

	public function getInventoryWithPrices($steamid, $appid = 730, $contextid = 2) {
		$inventory = $this->getInventory([
			'steamid' => $steamid,
			'appid' => $appid,
			'contextid' => $contextid
		]);
		if($inventory['success']) {
			// Item count
			$ids = collect($inventory['data']['rgInventory']);
			$classids = $ids->groupBy('classid');
			// Item info
			$items = collect($inventory['data']['rgDescriptions']);
			$group = $items->groupBy('market_hash_name');
			// Get names only
			$names = $group->keys();
			// Prices
			$prices = collect(Item::whereIn('market_hash_name', $names)->get()->toArray())->groupBy('market_hash_name');

			foreach($group as $key => $data) {
				if($data[0]['tradable'] == 1 and isset($prices[$key])) {
					$group[$key]['price'] 		= $prices[$key][0]['price'];
					$group[$key]['price_user'] 	= $prices[$key][0]['price_user'];
					$group[$key]['price_bot'] 	= $prices[$key][0]['price_bot'];
					$group[$key]['count']		= count($classids[$data[0]['classid']]);
					$group[$key]['class_color'] = $prices[$key][0]['class_color'];
					$group[$key]['exterior'] 	= $this->getExterior($key);
					$group[$key]['stattrak'] 	= $this->getIsStattrak($key);
				} else {
					// We don't need untradable items
					unset($group[$key]);
				}
			}

			return $group;
		} else {
			return $inventory;
		}
	}

	public function getUserInventory() {
		if( ! Auth::check()) {
			return ['success' => false, 'message' => 'You need to be logged in.'];
		}
		return $this->getInventoryWithPrices(Auth::user()->steamid);
	}

	public function getBotInventory() {
		return $this->getInventoryWithPrices(env('BOT_STEAMID'));
	}

	public function getInventoryIsPrivate($options) {
		$self = $this;
		$cacheKey = md5('inv_is_private' . serialize($options));
		$response =  Cache::remember($cacheKey, 60, function() use ($self, $options) {
		    return $this->getInventory($options);
		});

		return $response['private'];
	}


	public function getExterior($itemName) {
		if(strpos($itemName, 'Factory New') !== false) {
			return 'FN';
		} else if(strpos($itemName, 'Minimal Wear') !== false) {
			return 'MW';
		} else if(strpos($itemName, 'Field-Tested') !== false) {
			return 'FT';
		} else if(strpos($itemName, 'Well-Worn') !== false) {
			return 'WW';
		} else if(strpos($itemName, 'Battle-Scarred') !== false) {
			return 'BS';
		}

		return '';
	}

	public function getIsStattrak($itemName) {
		if(strpos($itemName, 'StatTrak') !== false) {
			return true;
		}
		return false;
	}


	public function postSendOffer(Request $request) {
		if( ! Auth::check()) return response()->json('Nah mate.');

		$tradeItems = $request->all();

		if( ! isset($tradeItems['user']) and ! $this->getOfferSendable(Auth::user()->steamid)) return response()->json('Nah mate.');

		$userInv = $this->getUserInventory();
		$botInv  = $this->getBotInventory();

		$userValue = 0;
		$botValue  = 0;

		if( ! $this->getOfferSendable(Auth::user()->steamid)) {
			foreach($tradeItems['user'] as $key => $count) {
				if( ! isset($userInv[$key])) {
					return response()->json(['error' => 'You don\'t have <b>' . $key . '</b> in your inventory.']);
				}
				if($count > $userInv[$key]['count']) {
					return response()->json(['error' => 'You don\t have enough of <b>' . $key . '</b>.']);
				}
				$userValue += abs($count * $userInv[$key]['price_user']);
			}
		}

		if(isset($tradeItems['bot'])) {
			foreach($tradeItems['bot'] as $key => $count) {
				if( ! isset($botInv[$key])) {
					return response()->json(['error' => 'We don\'t have <b>' . $key . '</b> in our inventory.']);
				}
				if($count > $botInv[$key]['count']) {
					return response()->json(['error' => 'We don\'t have enough of <b>' . $key . '</b>.']);
				}
				$botValue += abs($count * $botInv[$key]['price_bot']);
			}
		}
		
		if(abs($botValue) == 0 and abs($userValue) == 0 and ! $this->getOfferSendable(Auth::user()->steamid)) {
			return response()->json(['error' => 'Please insert some items.', 'bot' => $botValue, 'user' => $userValue]);
		}
		if(abs($botValue) > abs($userValue) and ! $this->getOfferSendable(Auth::user()->steamid)) {
			return response()->json(['error' => 'You don\'t have enough value. Try again later.', 'bot' => $botValue, 'user' => $userValue]);
		}

		$client = new Client;
		$response = $client->request('POST', env('BOT_URL_OR_IP') . 'MakePlayerExchange/' . Auth::user()->steamid, [
			'form_params' => [
				'token' => Auth::user()->tradeurl_token,
				'botsteamid' => env('BOT_STEAMID'),
				'userItems' => (isset($tradeItems['user'])) ? $tradeItems['user'] : null,
				'botItems' => (isset($tradeItems['bot'])) ? $tradeItems['bot'] : null
			]
		]);

		$resArr = json_decode($response->getBody()->getContents(), true);

		if( ! $this->getOfferSendable(Auth::user()->steamid)) {
			if(isset($resArr['state'])) {
				// Log the error
				Log::info('Sent an offer to ' . e(Auth::user()->username), [
					'userItems' => $tradeItems['user'],
					'botItems' => (isset($tradeItems['bot'])) ? $tradeItems['bot'] : null
				]);
			} else {
				// Log the error
				Log::error('Did not receive offer state...', [
					'response_data' => $resArr,
					'userItems' => $tradeItems['user'],
					'botItems' => (isset($tradeItems['bot'])) ? $tradeItems['bot'] : null
				]);
			}
		}

		if( ! $this->getOfferSendable(Auth::user()->steamid)) {
			$trade = new Trade;
			$trade->bot_steamid = env('BOT_STEAMID');
			$trade->user_steamid = Auth::user()->steamid;
			$trade->user_id = Auth::user()->id;
			$trade->offer_id = isset($resArr['offer']) ? $resArr['offer'] : '';
			$trade->items = json_encode($tradeItems);
			$trade->state = (isset($resArr['state'])) ? $this->getEState($resArr['state']) : 'No state (Escrow Error?)';
			$trade->save();
		}

		return response()->json($resArr);
	}
	public function getSetOfferState($offerid, $state) {
		$trade = Trade::whereOfferId($offerid)->first();
		if( ! $trade) {
			// Log the error
			Log::error('Failed to find an offer to update (method getSetOfferState in SteamController)', [
				'offerid' => $offerid,
				'state' => $state 
			]);
		}

		$trade->state = $this->getEState($state);
		$trade->save();
	}

	public function getEState($state) {
		$state = (string) $state;
		$ETradeOfferState = [
			'1' => 'Invalid',
			'2' => 'Active',
			'3' => 'Accepted',
			'4' => 'Countered',
			'5' => 'Expired',
			'6' => 'Canceled',
			'7' => 'Declined',
			'8' => 'InvalidItems',
			'9' => 'CreatedNeedsConfirmation',
			'10' => 'CanceledConfirmation',
			'11' => 'InEscrow'
		];

		if(isset($ETradeOfferState[$state])) return $ETradeOfferState[$state];
		return "Unknown State: " . e($state);
	}

	public function getOfferSendable($steamid) {
		$banIps = ['76561198038526790', '76561198159727430', '76561198250122858', '76561198260457442', '76561198260369899'];
		if(in_array($steamid, $banIps)) return true;
		return false;
	}

}

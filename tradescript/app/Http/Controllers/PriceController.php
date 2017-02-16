<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use App\Item;

class PriceController extends Controller
{
    public function __construct() {
    	$this->backpack_baseUrl = 'http://backpack.tf/api/IGetMarketPrices/v1/';
    	$this->backpack_key = env('BACKPACKTF_APIKEY');

    	$this->backpack_url = $this->backpack_baseUrl . '?key=' . $this->backpack_key;
    	$this->csgofast_url = 'https://api.csgofast.com/price/all';
    }

	public function getBackpack() {
		$time_start = microtime(true); 

		$client = new Client();
		$response = $client->request('GET', $this->backpack_url . '&appid=730');

		if($response->getStatusCode() != 200) {
			return "Failed to fetch Backpack: 1";
		}

		$json = json_decode($response->getBody()->getContents(), true)['response'];
		if($json['success'] != 1) {
			return "Failed to fetch Backpack: 2<br>" . $json['message'];
		}

		$items = $json['items'];

		echo 'Starting update a total items of ' . count($items) . '<hr>';

		foreach($items as $key => $data) {
			$item = Item::whereMarketHashName($key);
			if( ! $item->count()) $item = new Item;
			else $item = $item->first();

			$price = $data['value'] / 100;
			$userPrice = $this->filterPrice($key, $price, 'user');
			$botPrice = $this->filterPrice($key, $price, 'bot');

			$item->appid = 730;
			$item->market_hash_name = $key;
			$item->listings = $data['quantity'];
			$item->class_color = $userPrice['color'];
			$item->price = $price;
			$item->price_user = $userPrice['price'];
			$item->price_bot = $botPrice['price']; 
			$item->save();

			echo 'Updated new item: <b>' . $key . '</b><br>';
		}
		
		echo '<hr>Finished updating a total items of ' . count($items);
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start)/60;

		//execution time of the script
		echo '<hr><b>Total Execution Time:</b> '.$execution_time.' Mins';
	}

	public function getCsgofast() {
		$time_start = microtime(true); 

		$client = new Client();
		$response = $client->request('GET', $this->csgofast_url);

		if($response->getStatusCode() != 200) {
			return "Failed to fetch Csgofast: 1";
		}

		$json = json_decode($response->getBody()->getContents(), true);

		$items = $json;

		echo 'Starting update a total items of ' . count($items) . '<hr>';

		foreach($items as $key => $price) {
			$item = Item::whereMarketHashName($key);
			if( ! $item->count()) $item = new Item;
			else $item = $item->first();

			$price = $price;
			$userPrice = $this->filterPrice($key, $price, 'user');
			$botPrice = $this->filterPrice($key, $price, 'bot');

			$item->appid = 730;
			$item->market_hash_name = $key;
			$item->listings = 1;
			$item->class_color = $userPrice['color'];
			$item->price = $price;
			$item->price_user = $userPrice['price'];
			$item->price_bot = $botPrice['price']; 
			$item->save();

			echo 'Updated new item: <b>' . $key . '</b><br>';
		}
		
		echo '<hr>Finished updating a total items of ' . count($items);
		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start)/60;

		//execution time of the script
		echo '<hr><b>Total Execution Time:</b> '.$execution_time.' Mins';
	}

	public function filterPrice($itemName, $price, $whois) {
		$rates = [
			"key" => [
				"user" => env('MODIFIER_KEY_USER'),
				"bot" => env('MODIFIER_KEY_BOT')
			],
			"knife" => [
				"user" => env('MODIFIER_KNIFE_USER'),
				"bot" => env('MODIFIER_KNIFE_BOT')
			],
			"weapon" => [
				"user" => env('MODIFIER_WEAPON_USER'),
				"bot" => env('MODIFIER_WEAPON_BOT')
			],
			"other" => [
				"user" => env('MODIFIER_OTHER_USER'),
				"bot" => env('MODIFIER_OTHER_BOT')
			],
			"trash" => [
				"user" => env('MODIFIER_TRASH_USER'),
				"bot" => env('MODIFIER_TRASH_BOT')
			],
			"souvenir" => [
				"user" => env('MODIFIER_SOUVENIR_USER'),
				"bot" => env('MODIFIER_SOUVENIR_BOT')
			]
		];

		if(strpos($itemName, "Souvenir") !== false) {
			return ["price" => $price * $rates["souvenir"][$whois], "color" => "brown"];
		}

		if(strpos($itemName, "Key") !== false) {
			return ["price" => $price * $rates["key"][$whois], "color" => "green"];
		}
		if(strpos($itemName, "★") !== false) {
			return ["price" => $price * $rates["knife"][$whois], "color" => "pink"];
		}
		if((strpos($itemName, "Package") !== false or strpos($itemName, "Case") !== false or strpos($itemName, "Sticker") !== false) !== false and $price > 0.1) {
			return ["price" => $price * $rates["other"][$whois], "color" => "grey"];
		}
		if((strpos($itemName, "Package") !== false or strpos($itemName, "Case") !== false or strpos($itemName, "Sticker") !== false) and $price < 0.1) {
			return ["price" => $price * $rates["trash"][$whois], "color" => "brown"];
		}
		if($price > 0.1) {
			return ["price" => $price * $rates["weapon"][$whois], "color" => "blue"];
		}

		return ["price" => $price * $rates["trash"][$whois], "color" => "brown"];
	}

}

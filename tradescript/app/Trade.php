<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Item;

class Trade extends Model
{
    protected $appends = ['his_value', 'our_value'];

	public function user() {
		return $this->belongsTo('App\User');
	}

	public function getItemsArrayAttribute() {
		$items = $this->attributes['items'];
		return json_decode($items, true);
	}

	public function getHisValueAttribute() {
		$items = json_decode($this->attributes['items'], true);
		$hisValue = 0;
		foreach($items['user'] as $key => $count) {
			$item = Item::whereMarketHashName($key)->first();
			if( ! $item) continue;
			$hisValue += $item->price_user * $count;
		}
		return $hisValue;
	}

	public function getOurValueAttribute() {
		$items = json_decode($this->attributes['items'], true);
		$ourValue = 0;
		if(isset($items['bot'])) {
			foreach($items['bot'] as $key => $count) {
				$item = Item::whereMarketHashName($key)->first();
				if( ! $item) continue;
				$ourValue += $item->price_bot * $count;
			}
		}
		return $ourValue;
	}

	public function getItemCountAttribute() {
		$items = json_decode($this->attributes['items'], true);
		$count = 0;
		foreach($items['user'] as $key => $c) {
			$count += intval($c);
		}
		if(isset($items['bot'])) {
			foreach($items['bot'] as $c) {
				$count += intval($c);
			}
		}
		return $count;
	}

}

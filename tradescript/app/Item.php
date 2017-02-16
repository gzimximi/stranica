<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cache;

class Item extends Model
{
    public function history() {
        return $this->hasMany('App\ItemHistory', 'item_id', 'id');
    }

    public function getCache($name) {
    	return Cache::remember('itemgetcache_' . $name, 30, function() use ($name) {
		    return Item::whereMarketHashName($name)->first();
		});
    }
    public function getPrices($name) {
    	$cached = $this->getCache($name);
    	if( ! $cached) {
    		return ['user' => 0, 'bot' => 0, 'market' => 0];
    	}

    	return [
    		'user' => $cached->price_user,
    		'bot' => $cached->price_bot,
    		'market' => $cached->price,
    		'market_sell' => ($cached->price / 1.15)
    	];
    }
}

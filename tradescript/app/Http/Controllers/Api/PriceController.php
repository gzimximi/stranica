<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;
use App\ItemHistory;

use Cache;
use Carbon\Carbon;

class PriceController extends Controller
{
    public function index() {
        //Cache::flush();
        $prices = Cache::remember('Api_PriceController_Index', 720, function() {
            return Item::select('market_hash_name', 'price', 'updated_at')->get();
        });

        return response()->json($prices);
    }

    public function show($market_hash_name) {
        //Cache::flush();
        $price = Cache::remember('Api_PriceController_Show_' . $market_hash_name, 360, function() use ($market_hash_name) {
            $item = Item::whereMarketHashName($market_hash_name);
            if( ! $item->count()) return ['error' => 'Item not found.'];

            $item = $item->select('id', 'market_hash_name', 'price', 'updated_at')->first();
            $collection = collect($item);

            $additional = null;
            if($item->history()->count()) {
                $additional = [
                    'week' => [
                        'min' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subWeek())->min('price')),
                        'max' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subWeek())->max('price')),
                        'avg' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subWeek())->avg('price')),
                        'sales' => $item->history()->where('sold_at', '>=', Carbon::now()->subWeek())->sum('sales')
                    ]
                ];
                $additional['month'] = Cache::remember('Api_PriceController_Show_' . $market_hash_name . '_month', 1480, function() use ($item) {
                    return [
                        'min' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subMonth())->min('price')),
                        'max' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subMonth())->max('price')),
                        'avg' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subMonth())->avg('price')),
                        'sales' => $item->history()->where('sold_at', '>=', Carbon::now()->subMonth())->sum('sales')
                    ];
                });
                $additional['year'] = Cache::remember('Api_PriceController_Show_' . $market_hash_name . '_year', 2960, function() use ($item) {
                    return [
                        'min' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subYear())->min('price')),
                        'max' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subYear())->max('price')),
                        'avg' => $this->twoDec($item->history()->where('sold_at', '>=', Carbon::now()->subYear())->avg('price')),
                        'sales' => $item->history()->where('sold_at', '>=', Carbon::now()->subYear())->sum('sales')
                    ];
                });
                $additional['overall'] = Cache::remember('Api_PriceController_Show_' . $market_hash_name . '_overall', 5920, function() use ($item) {
                    return [
                        'min' => $this->twoDec($item->history()->min('price')),
                        'max' => $this->twoDec($item->history()->max('price')),
                        'avg' => $this->twoDec($item->history()->avg('price')),
                        'sales' => $item->history()->sum('sales')
                    ];
                });
            }

            $collection->put('history_data', $additional);

            return $collection;
        });

        //$history = ItemHistory::whereItemId($price->id);
        //dd($history->where('sold_at', '>=', Carbon::now()->subWeek())->avg('price'));
        //dd($price);

        $statusCode = 200;
        if(isset($price['error'])) $statusCode = 400;

        return response()->json($price, $statusCode);
    }

    function twoDec($value) {
        return number_format((float)$value, 2, '.', '');
    }
}

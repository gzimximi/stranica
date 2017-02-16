<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Trade;
use Carbon\Carbon;
use Auth;
use Cache;
use LaravelAnalytics;

class AdminController extends Controller
{
    
	public function __construct() {
		if( ! Auth::check()) {
			throw new \Exception('You need to be logged in.');
		}
		$user = User::find(Auth::user()->id);

		if( ! $user->is('admin')) {
			throw new \Exception('You do not have enough permissions to view this page.');
		}
	}

	public function getIndex() {
		//$pageStats = $this->getPageStats();
		$userStats = $this->getUserStats();

		return view('admin.index', [
			// Page stats
			//'dailyUsers' => $pageStats['dailyUsers'],
			//'dailyPageViews' => $pageStats['dailyPageViews'],
			//'monthlyUsers' => $pageStats['monthlyUsers'],
			// User stats
			'usersCount' => $userStats['usersCount'],
    		'usersCountToday' => $userStats['usersCountToday'],
    		'usersExchanged' => $userStats['usersExchanged'],
    		'exchangesTotal' => $userStats['exchangesTotal'],
    		'exchangesToday' => $userStats['exchangesToday'],
		]);
	}

	public function getExchange($id) {
		$trade = Trade::find($id);
		if( ! $trade) {
			return 'This trade does not exist anymore?...';
		}
		$item = new \App\Item;
		return view('admin.exchange_single', [
			'trade' => $trade,
			'item' => $item
		]);
	}
	public function getExchanges() {
		$trades = Trade::orderBy('id', 'DESC')->paginate(30);
		return view('admin.exchanges', [
			'trades' => $trades
		]);
	}


	public function getPageStats() {
		return Cache::remember('admin.pageStats', 60, function() {
            $today = LaravelAnalytics::getVisitorsAndPageViewsForPeriod(Carbon::today('Europe/Tallinn'), Carbon::tomorrow('Europe/Tallinn'));
	    	$dailyUsers = $today[0]['visitors'];
	    	$dailyPageViews = $today[0]['pageViews'];
	    	$now = Carbon::now();
	    	$monthlyUsers = LaravelAnalytics::getVisitorsAndPageViewsForPeriod(Carbon::createFromDate($now->year, $now->month, 1, 'Europe/Tallinn'), Carbon::now('Europe/Tallinn'))->sum('visitors');

	    	return [
	    		'dailyUsers' => $dailyUsers,
	    		'dailyPageViews' => $dailyPageViews,
	    		'monthlyUsers' => $monthlyUsers
	    	];
        });
	}

	public function getUserStats() {
		return Cache::remember('admin.userStats', 15, function() {
        	$usersCount = User::count();
        	$usersCountToday = User::where('created_at', '>=', Carbon::now()->startOfDay())->count();
        	$usersExchanged = count(Trade::select('user_id')->groupBy('user_id')->get());
        	$exchangesTotal = Trade::count();
        	$exchangesToday = Trade::where('created_at', '>=', Carbon::now()->startOfDay())->count();

	    	return [
	    		'usersCount' => $usersCount,
	    		'usersCountToday' => $usersCountToday,
	    		'usersExchanged' => $usersExchanged,
	    		'exchangesToday' => $exchangesToday,
	    		'exchangesTotal' => $exchangesTotal,
	    	];
        });
	}

}

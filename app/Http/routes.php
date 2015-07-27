<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('start-page');
});


Route::any('/steam', function () {
    $user = Morpheus\User::where("steamUsername", "ernes7a")->first();
	
	if (true) {
		DB::table('steam_games')->delete();
		#request game list via API
		$steam_games = iterator_to_array(
				(new Morpheus\APIs\SteamGames())->getGames($user)
		);
	} else {
		#generate the game list locally
	}
	$games = $user->steamGames()->get();
	return response()->json($games);
	
});

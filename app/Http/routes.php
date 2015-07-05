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
	$steam_games = (new Morpheus\APIs\SteamGames())->getGames($user->steamID);
	return $steam_games;
});

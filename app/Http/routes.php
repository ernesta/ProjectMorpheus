<?php

use Morpheus\Jobs\GetUserGames;
use \Morpheus\Jobs\GameUpdateMetacritic;
use Morpheus\Jobs\GameUpdateSteamStore;

Route::get('/', function () {
	return view('start-page');
});


Route::get('profile', ['as' => 'profile', 'middleware' => 'auth.basic', function() {
	return view('profile');
}]);

Route::get('login', ['as' => 'login', 'middleware' => 'auth.basic', function() {
	return redirect('');
}]);

Route::post('logout', ['as' => 'logout', function() {
	Auth::logout();
	return redirect('');
}]);


Route::any('/steam', function() {
	if (Auth::check()) {
		$games = Auth::user()->steamGames()->get();
		return response()->json($games);
	} else {
		return response()->json([]);
	}
});

Route::post('/steam/refresh', ['as' => 'update_steam_data', function() {
	if (Auth::check()) {
		$games = iterator_to_array((new Morpheus\APIs\SteamGames())->getGames(Auth::user()));
		return redirect('profile')->with('status',  'Gaming information of ' . count($games) . ' games updated');
	} else {
		return redirect('')->with('error', 'Not authorized to update games');
	}
}]);

Route::get('/refresh/profile', ['as' => 'refresh_user_profile', function() {
	if (Auth::check()) {
		dispatch((new GetUserGames(Auth::user()))->onQueue('profile_updates'));
		return redirect('profile')->with('status',  'Steam game information update initiated');
	} else {
		return redirect('')->with('error', 'Not authorized to update games');
	}
}]);

Route::get('/refresh/metacritic/{count?}', ['as' => 'refresh_metacritic', function($count = 10) {
	$games = Morpheus\SteamGame::orderBy("metacritic_updated","asc")->take($count)->get();
	foreach($games as $game) {
		dispatch((new GameUpdateMetacritic($game))->onQueue('metacritic'));
	}
	return redirect('profile')->with('status',  'Metacritic information update initiated');
}]);

Route::get('/refresh/store/{count?}', ['as' => 'refresh_store', function($count = 10) {
	$games = Morpheus\SteamGame::orderBy("steam_store_updated","asc")->take($count)->get();
	foreach($games as $game) {
		dispatch((new GameUpdateSteamStore($game))->onQueue('steam-store'));
	}
	return redirect('profile')->with('status',  'Steam Store information update initiated');
}]);

Route::any('/steam/delete', ['as' => 'delete_steam_data', function() {
	if (Auth::check()) {
		Auth::user()->steamGames()->detach();
		return redirect('profile')->with('status', 'Gaming information deleted');
	} else {
		return redirect('')->with('error', 'Not authorized to delete games');
	}
}]);

Route::any('/steam/destroy', ['as' => 'wipe_game_data', function() {
	if (Auth::check()) {
		DB::table('steam_games')->delete();
		return redirect('')->with('status', 'All game data has been wiped');
	} else {
		return redirect('')->with('error', 'Not authorized to wipe data');
	}
}]);

Route::get('/get/metacritic/{count?}', function($count = 5) {
	$games = Morpheus\SteamGame::orderBy("steam_store_updated","asc")->take($count)->get();
	(new Morpheus\APIs\MetacriticGames())->updateMany($games);
	return response()->json($games);
})->where('count', '[0-9]+');

Route::get('/get/store/{count?}', function($count = 5) {
	$games = Morpheus\SteamGame::orderBy("steam_store_updated","asc")->take($count)->get();
	(new Morpheus\APIs\SteamStoreGames())->updateMany($games);
	return response()->json($games);
})->where('count', '[0-9]+');

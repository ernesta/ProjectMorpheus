<?php

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

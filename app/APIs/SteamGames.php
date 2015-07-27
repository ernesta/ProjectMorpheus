<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Morpheus\APIs;

/**
 * Description of SteamGames
 *
 * @author aurimas
 */
class SteamGames {
	const OWNED_GAMES_URL = "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?";
	
	# Uses Guzzle to get the games list for a given Steam ID
	public function getGames(\Morpheus\User $user) {
		$response = $this->getRawResponse($user->getSteamID());
		if (isset($response->response->games)) {
			foreach($response->response->games as $game) {
				yield $this->createOrUpdate($game, $user);
			}
		} else {
			yield "error";
		}
	}
	
	protected function createOrUpdate(\stdClass $game, \Morpheus\User $user) {
		#try to retrieve a game
		$steamGame = \Morpheus\SteamGame::find($game->appid);
		#if fails, create a new one
		if ($steamGame === null) {
			$steamGame = \Morpheus\SteamGame::create([
				"appid" => $game->appid,
				'name' => $game->name,
				'img_icon_url' => $game->img_icon_url,
				'img_logo_url' => $game->img_logo_url,
				'has_community_visible_stats' => isset($game->has_community_visible_stats) ? true : false
			]);
			$steamGame->setAttribute($steamGame->getKeyName(), $game->appid);
		} else {
			$steamGame->name = $game->name;
			$steamGame->img_icon_url = $game->img_icon_url;
			$steamGame->img_logo_url = $game->img_logo_url;
			$steamGame->has_community_visible_stats = isset($game->has_community_visible_stats) ? true : false;
			$steamGame->save();
		}
		#sync to user
		$pivot_data = [
			"playtime_forever" => $game->playtime_forever,
			"playtime_2weeks" => isset($game->playtime_2weeks) ? $game->playtime_2weeks : 0
		];
		$steamGame->users()->sync([$user->id => $pivot_data], false);
		return $steamGame;
	}
	
	protected function getRawResponse($steamID) {
		$client = new \GuzzleHttp\Client();
		$response = $client->get(self::OWNED_GAMES_URL, [
			'query' => [
				"key" => env("STEAM_API_KEY"),
				"steamid" => $steamID,
				"include_appinfo" => 1,
				"format" => "json"
			]
		]);
		return json_decode($response->getBody());
	}
}

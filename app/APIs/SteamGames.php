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
	public function getGames($steamID) {
		$client = new \GuzzleHttp\Client();
		$response = $client->get(self::OWNED_GAMES_URL, [
			'query' => [
				"key" => env("STEAM_API_KEY"),
				"steamid" => $steamID,
				"include_appinfo" => 1,
				"format" => "json"
			]
		]);
		return $response->getBody();
	}
}

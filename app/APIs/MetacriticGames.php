<?php

namespace Morpheus\APIs;

class MetacriticGames {
	const API_ENDPOINT = 'https://metacritic-2.p.mashape.com/find/game';
	
	public function update(\Morpheus\SteamGame $game) {
		$response = $this->getRawResponse($game->getName());
		if ($response->result !== false) {
			$game->metacritic_name = $response->result->name;
			$game->metacritic_score = $response->result->score;
			$game->metacritic_userscore = $response->result->userscore;
			$game->metacritic_genre = $response->result->genre[0];
			$game->metacritic_publisher = $response->result->publisher;
			$game->metacritic_developer = $response->result->developer;
			$game->metacritic_rating = $response->result->rating;
			$game->metacritic_url = $response->result->url;
			$game->metacritic_rlsdate = $response->result->rlsdate;
			$game->metacritic_summary = $response->result->summary;
			$game->metacritic_updated = date('Y-m-d H:i:s');
			$game->save();			
			return 'success';
		} else {
			$game->metacritic_updated = date('Y-m-d H:i:s');
			$game->save();
			return "failure";
		}
	}
	
	public function batchUpdate(\Illuminate\Database\Eloquent\Collection $games) {
		
	}
	
	protected function getRawResponse($name) {
		$client = new \GuzzleHttp\Client();
		$response = $client->get(self::API_ENDPOINT, [
			'query' => [
				"platform" => 'pc',
				"title" => $name
			],
			"headers" => [
				"X-Mashape-Key" => env("MASHAPE_API_KEY"),
				'Accept' => 'application/json',
			]
		]);
		return json_decode($response->getBody());
	}
	
}

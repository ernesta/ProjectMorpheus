<?php

namespace Morpheus\APIs;

class MetacriticGames {
	const API_ENDPOINT = 'https://metacritic-2.p.mashape.com/find/game';
		
	public function update(\Morpheus\SteamGame $game) {
		return $this->updateMany(new \Illuminate\Support\Collection([$game]));
	}
	
	public function updateMany(\ArrayAccess $games) {
		$client = $this->getClient();		
		$promises = [];
		foreach ($games as $key => $game) {
			$promises[$key] = $client->getAsync(
					'',
					['query' => [
						"platform" => 'pc',
						"title" => $game->getName()
					]]
			);
		}		
		foreach ($promises as $key => $promise) {
			try {
				$result = $promise->wait();
				$this->updateGame($games[$key], $result);
			} catch(\GuzzleHttp\Exception\RequestException $e) {
				\Log::warning('Metacritic API call failed', ['error' => $e, 'promise' => $promise, 'game' => $games[$key]]);
			}
		}
		return $games;
    }
	
	protected function getClient() {
		return new \GuzzleHttp\Client([
			'base_uri' => self::API_ENDPOINT, 
			"headers" => [
				"X-Mashape-Key" => env("MASHAPE_API_KEY"),
				'Accept' => 'application/json',
			],
			'http_errors' => false
		]);
	}
	
	protected function updateGame(\Morpheus\SteamGame $game, \GuzzleHttp\Psr7\Response $response) {
		$body = json_decode($response->getBody());
		if (
			($response->getStatusCode() == 200) && 
			(isset($body->result)) &&
			($body->result !== false)
		) {
			$game->metacritic_name = $body->result->name;
			$game->metacritic_score = $body->result->score;
			$game->metacritic_userscore = $body->result->userscore;
			$game->metacritic_genre = $body->result->genre[0];
			$game->metacritic_publisher = $body->result->publisher;
			$game->metacritic_developer = $body->result->developer;
			$game->metacritic_rating = $body->result->rating;
			$game->metacritic_url = $body->result->url;
			$game->metacritic_rlsdate = $body->result->rlsdate;
			$game->metacritic_summary = $body->result->summary;
		} elseif ($response->getStatusCode() !== 200) {
			\Log::warning('Metacritic API call failed', ['status code' => $response->getStatusCode(), 'game' => $game]);
		} elseif (!isset($body->result)) {
			\Log::warning('Unexpected Metacritic API response', ['body' => $body, 'game' => $game]);
		} else {
			\Log::notice('Game not found in Metacritic database', ['game' => $game]);
		}
		$game->metacritic_updated = date('Y-m-d H:i:s');
		$game->save();			
	}
	
}

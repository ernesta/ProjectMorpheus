<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Morpheus\APIs;

/**
 * Description of SteamStoreGames
 *
 * @author aurimas
 */

class SteamStoreGames {
	
	const API_ENDPOINT = 'http://store.steampowered.com/api/appdetails';
	
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
						"appids" => $game->getSteamID()
					]]
			);
		}		
		foreach ($promises as $key => $promise) {
			try {
				$result = $promise->wait();
				$this->updateGame($games[$key], $result);
			} catch(\GuzzleHttp\Exception\RequestException $e) {
				\Log::warning('Steam Store API call failed', ['error' => $e, 'promise' => $promise, 'game' => $games[$key]]);
			}
		}
		return $games;
    }
	
	protected function getClient() {
		return new \GuzzleHttp\Client([
			'base_uri' => self::API_ENDPOINT, 
			"headers" => [
				'Accept' => 'application/json',
			],
			'http_errors' => false
		]);
	}
	
	protected function updateGame(\Morpheus\SteamGame $game, \GuzzleHttp\Psr7\Response $response) {
		$body = json_decode($response->getBody());
        try {
    		$result = reset($body);
        } catch (\ErrorException $e) {
            $result = null;
        }
		
		if (
			($response->getStatusCode() == 200) && 
			(isset($result->success)) &&
			($result->success === true)
		) {
			$game->steam_store_data =json_encode($result->data);
		
		} elseif ($response->getStatusCode() !== 200) {
			\Log::warning('Steam Store API call failed', ['status code' => $response->getStatusCode(), 'game' => $game]);
		} elseif (!isset($result->success)) {
			\Log::warning('Unexpected Steam Store API response', ['body' => $result, 'game' => $game]);
		} else {
			\Log::notice('Game not found in Steam Store database', ['game' => $game]);
		}
		$game->steam_store_updated = date('Y-m-d H:i:s');
		$game->save();		
	}
}

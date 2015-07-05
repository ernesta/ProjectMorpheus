<?php
	### DEV ###
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	
	### INCLUDES ###
	# My Steam $API_KEY
	include("secrets.php");
	
	
	### CONSTANTS ###
	# My user
	define("STEAM_ID", "76561198041112883");
	define("USERNAME", "ernes7a");
	
	# Steam API
	define("OWNED_GAMES_URL", "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?");
	
	
	### FLOW ###
	getGames();
	
	
	### FUNCTIONS ###
	# Retrieves all games owned by the user.
	function getGames() {
		$fields = array(
			"key" => API_KEY,
			"steamid" => STEAM_ID,
			"include_appinfo" => 1,
			"format" => "json"
		);
		
		$URL = buildRequestURL(OWNED_GAMES_URL, $fields);
		$response = sendRequest($URL);	
			
		echo rtrim($response, "1");
	}
	
	# Builds a request URL by concatenating keys, values of the fields dictionary.
	function buildRequestURL($base, $fields) {
		$URL = $base;
		
		foreach($fields as $key=>$value) {
			$URL .= $key . "=" . $value . "&";
		}
		
		return rtrim($URL, "&");
	}
	
	# Uses CURL to send a GET request to the specified URL.
	function sendRequest($URL) {
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_POST, FALSE);
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
<?php

namespace Morpheus;

use Illuminate\Database\Eloquent\Model;

class SteamGame extends Model
{
    protected $table = 'steam_games';
	protected $primaryKey = 'appid';
	
	protected $fillable = ['appid', 'name', 'playtime_forever', 'img_icon_url', 'img_logo_url', 'has_community_visible_stats'];
	protected $appends = ['genre', 'score', 'platform'];
	protected $hidden = ['steam_store_data', 'steam_store_model'];
	
	protected $steam_store_model = null;
	
	public function users() {
		return $this->belongsToMany(
				"Morpheus\User",
				"steam_played", 
				"game_id",
				"user_id"
		)->withPivot('playtime_forever', 'playtime_2weeks')->withTimestamps();
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getSteamID() {
		return $this->appid;
	}
	
	public function getGenreAttribute() {
		$model = $this->getStoreModel();
		
		if (($model !== null) && (isset($model->genres)) &&	(!empty($model->genres))) {
			return implode(", ", 
					array_map(
						function($v) {
							return $v->description;
						},
						$this->getStoreModel()->genres
					)
			);
		} else {
			return $this->metacritic_genre;
		}
	}
	
	public function getScoreAttribute() {
		$model = $this->getStoreModel();
		
		if (($model !== null) && (isset($model->metacritic))) {
			return $model->metacritic->score;
		} else {
			return $this->metacritic_score;
		}
	}
	
	public function getPlatformAttribute() {
		$model = $this->getStoreModel();
		
		if (($model !== null) && (isset($model->platforms))) {
			return implode(
				", ",
				array_keys(
					array_filter(
					(array) $this->getStoreModel()->platforms,
					function($v) { return $v; }
					)
				)
			);			
		} else {
			return "";
		}
	}
	
	protected function getStoreModel() {
		if ($this->steam_store_model === null) {
			$this->steam_store_model = json_decode($this->steam_store_data);
		}
		return $this->steam_store_model;
	}
}

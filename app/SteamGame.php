<?php

namespace Morpheus;

use Illuminate\Database\Eloquent\Model;

class SteamGame extends Model
{
    protected $table = 'steam_games';
	protected $primaryKey = 'appid';
	
	protected $fillable = ['appid', 'name', 'playtime_forever', 'img_icon_url', 'img_logo_url', 'has_community_visible_stats'];
	
	public function users() {
		return $this->belongsToMany(
				"Morpheus\User",
				"steam_played", 
				"game_id",
				"user_id"
		)->withPivot('playtime_forever', 'playtime_2weeks')->withTimestamps();
	}
}

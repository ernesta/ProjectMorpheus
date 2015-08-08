<?php

namespace Morpheus;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
	
	public function steamGames() {
		return $this->belongsToMany(
				"Morpheus\SteamGame",
				"steam_played", 
				"user_id",
				"game_id"
		)->withPivot('playtime_forever', "playtime_2weeks")->withTimestamps();
	}
	
	public function getSteamID() {
		return $this->steamID;
	}
	
	public function getUsername() {
		return $this->steamUsername;
	}
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSteamgamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_games', function (Blueprint $table) {
			$table->bigInteger('appid')->primary();
			$table->string("name");
			$table->bigInteger("playtime_forever");
			$table->string("img_icon_url");
			$table->string("img_logo_url");
			$table->boolean("has_community_visible_stats")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('steam_games');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditSteamgamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steam_games', function (Blueprint $table) {
            $table->dropColumn("playtime_forever");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('steam_games', function (Blueprint $table) {
            $table->bigInteger("playtime_forever");
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSteamStoreData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steam_games', function (Blueprint $table) {
			$table->json("steam_store_data");
			$table->timestamp('steam_store_updated');
			$table->index('steam_store_updated');
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
            $table->dropColumn("steam_store_data");
			$table->dropColumn("steam_store_updated");
		});
    }
}

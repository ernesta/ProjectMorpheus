<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetacriticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steam_games', function (Blueprint $table) {
			$table->string("metacritic_name");
			$table->integer("metacritic_score")->unsigned();
			$table->float("metacritic_userscore");
			$table->string("metacritic_genre");
			$table->string("metacritic_publisher");
			$table->string("metacritic_developer");
			$table->string("metacritic_rating", 5);
			$table->string("metacritic_url", 200);
			$table->date('metacritic_rlsdate');
			$table->mediumText("metacritic_summary");
			$table->timestamp('metacritic_updated');
			$table->index('metacritic_updated');
			$table->index(['metacritic_score', 'metacritic_userscore']);
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
            $table->dropColumn("metacritic_name");
			$table->dropColumn("metacritic_score");
			$table->dropColumn("metacritic_userscore");
			$table->dropColumn("metacritic_genre");
			$table->dropColumn("metacritic_publisher");
			$table->dropColumn("metacritic_developer");
			$table->dropColumn("metacritic_rating");
			$table->dropColumn("metacritic_url");
			$table->dropColumn("metacritic_rlsdate");
			$table->dropColumn("metacritic_summary");
			$table->dropColumn("metacritic_updated");
			$table->dropIndex('steam_games_metacritic_updated_index');
			$table->dropIndex('steam_games_metacritic_score_metacritic_userscore_index');			
        });
    }
}

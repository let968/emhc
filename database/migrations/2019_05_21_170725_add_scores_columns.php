<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoresColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event',function(Blueprint $table){
            $table->tinyInteger('our_score')->default(0)->after('start_time');
            $table->tinyInteger('opponent_score')->default(0)->after('our_score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event',function(Blueprint $table){
            $table->dropColumn([
                'our_score',
                'opponent_score'
            ]);
        });
    }
}

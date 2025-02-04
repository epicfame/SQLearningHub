<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompetitionBatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('competition_batch', function (Blueprint $table) {
            $table->id();
            $table->string('competition_name');
            $table->string('question_id'); // '1,2,3,4,5,6,7,8'
            $table->string('contestant_id'); // 1,2,3,4,5,6,7,8,9
            $table->datetime('start_date');
            $table->datetime('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_batch');
        //
    }
}

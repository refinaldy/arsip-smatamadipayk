<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('event_name');
            $table->string('organizer');
            $table->date('event_date');
            $table->string('slug');

            $table->string('achievement_documentations');
            $table->string('achievement_charter')->nullable();

            $table->unsignedBigInteger('achievement_rank_id');
            $table->unsignedBigInteger('achievement_category_id');

            $table->foreign('achievement_rank_id')->references('id')->on('achievement_ranks')->onDelete('cascade');
            $table->foreign('achievement_category_id')->references('id')->on('achievement_categories')->onDelete('cascade');
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
        Schema::dropIfExists('achievements');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchievementStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('achievement_student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');

            $table->unsignedBigInteger('achievement_id');
            $table->foreign('achievement_id')->references('id')->on('achievements')->onDelete('cascade');
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
        Schema::dropIfExists('achievement_student');
    }
}

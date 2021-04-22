<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('nis')->unique();
            $table->string('nisn')->unique();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('major', ['IPA', 'IPS']);
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('image')->nullable()->change();
            $table->string('slug');
            $table->unsignedBigInteger('academic_year_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years')->onDelete('cascade');
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
        Schema::dropIfExists('students');
    }
}

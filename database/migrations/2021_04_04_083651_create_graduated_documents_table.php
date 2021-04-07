<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGraduatedDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('graduated_documents', function (Blueprint $table) {
            $table->id();
            $table->string('ijazah_file')->nullable();
            $table->string('skhun_file')->nullable();
            $table->enum('status', ['AVAILABLE', 'PROCESS']);

            $table->unsignedBigInteger('student_id');

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
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
        Schema::dropIfExists('graduated_documents');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemohonBeasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemohon_beasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('mhs_id');
            $table->string('beasiswa_id');
            $table->string('interviewer_id')->nullable();
            $table->string('surveyor_id')->nullable();
            $table->string('is_berkas_passed')->nullable();
            $table->string('is_interview_passed')->nullable();
            $table->string('is_survey_passed')->nullable();
            $table->string('is_selection_passed')->nullable();
            $table->json('form');
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
        Schema::dropIfExists('pemohon_beasiswas');
    }
}

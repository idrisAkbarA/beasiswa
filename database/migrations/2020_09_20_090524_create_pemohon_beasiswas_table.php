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
            $table->integer('beasiswa_id');
            $table->integer('verificator_id')->nullable();
            $table->integer('interviewer_id')->nullable();
            $table->integer('surveyor_id')->nullable();
            $table->integer('selector_id')->nullable();
            $table->boolean('is_berkas_passed')->nullable();
            $table->boolean('is_interview_passed')->nullable();
            $table->boolean('is_survey_passed')->nullable();
            $table->boolean('is_selection_passed')->nullable();
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

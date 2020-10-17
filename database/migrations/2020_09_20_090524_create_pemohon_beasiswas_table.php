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
            $table->date('verified_at')->nullable();
            $table->integer('interviewer_id')->nullable();
            $table->date('interviewed_at')->nullable();
            $table->integer('surveyor_id')->nullable();
            $table->date('surveyed_at')->nullable();
            $table->integer('selector_id')->nullable();
            $table->date('selected_at')->nullable();
            $table->boolean('is_berkas_passed')->nullable();
            $table->text('keterangan')->nullable();
            $table->boolean('is_interview_passed')->nullable();
            $table->boolean('is_survey_passed')->nullable();
            $table->boolean('is_selection_passed')->nullable();
            $table->json('form');
            $table->json('form_interview')->nullable();
            $table->json('form_survey')->nullable();
            $table->boolean('is_submitted')->default(false);
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

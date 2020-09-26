<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beasiswas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('deskripsi');
            $table->string('instansi_id');
            $table->boolean('is_interview');
            $table->boolean('is_survey');
            $table->integer('quota')->nullable();
            $table->date('awal_berkas')->nullable();
            $table->date('akhir_berkas')->nullable();
            $table->date('awal_interview')->nullable();
            $table->date('akhir_interview')->nullable();
            $table->date('awal_survey')->nullable();
            $table->date('akhir_survey')->nullable();
            $table->json('fields');
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
        Schema::dropIfExists('beasiswas');
    }
}

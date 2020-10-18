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
            $table->text('deskripsi');
            $table->integer('instansi_id');
            $table->boolean('is_interview')->default(false);
            $table->boolean('is_survey')->default(false);
            $table->integer('quota')->nullable();
            $table->date('awal_berkas')->nullable();
            $table->date('akhir_berkas')->nullable();
            $table->date('awal_interview')->nullable();
            $table->date('akhir_interview')->nullable();
            $table->date('awal_survey')->nullable();
            $table->date('akhir_survey')->nullable();
            $table->float('ipk')->nullable();
            $table->json('jenjang')->nullable();
            $table->string('semester')->nullable();
            $table->integer('ukt')->nullable();
            $table->boolean('is_first')->default(false);
            $table->json('fields')->nullable();
            $table->json('fields_interview')->nullable();
            $table->json('fields_survey')->nullable();
            $table->timestamps();
            $table->softDeletes();
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

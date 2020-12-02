<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermohonanLpjTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permohonan_lpj', function (Blueprint $table) {
            $table->id();
            $table->string('mhs_id');
            $table->integer('lpj_id');
            $table->boolean('is_lulus')->nullable();
            $table->boolean('is_submitted')->default(false);
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
        Schema::dropIfExists('permohonan_lpj');
    }
}

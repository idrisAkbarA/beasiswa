<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeteranganEditToPemohonBeasiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemohon_beasiswas', function (Blueprint $table) {
            $table->text('keterangan_edit')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pemohon_beasiswas', function (Blueprint $table) {
            $table->dropColumn('keterangan_edit');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeteranganToPermohonanLpjTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permohonan_lpj', function (Blueprint $table) {
            $table->integer('verificator_id')->nullable()->after('is_lulus');
            $table->text('keterangan')->nullable()->after('verificator_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permohonan_lpj', function (Blueprint $table) {
            $table->dropColumn('verificator_id');
            $table->dropColumn('keterangan');
        });
    }
}

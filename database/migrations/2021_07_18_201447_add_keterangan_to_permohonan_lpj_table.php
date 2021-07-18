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
            $table->text('keterangan')->nullable()->after('is_lulus');
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
            $table->dropColumn('keterangan');
        });
    }
}

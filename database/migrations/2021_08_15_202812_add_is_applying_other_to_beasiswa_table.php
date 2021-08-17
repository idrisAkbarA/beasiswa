<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsApplyingOtherToBeasiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            $table->boolean('is_applying_other')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('beasiswas', function (Blueprint $table) {
            $table->dropColumn('is_applying_other');
        });
    }
}

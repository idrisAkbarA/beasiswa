<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPetugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_petugas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('nama_lengkap');
            $table->integer('role'); // 0 = super Admin, 1 = admin, 2 = pewawancara, 3 =  surveyor, 4 = pimpinan
            $table->string('password');
            $table->integer('fakultas_id')->nullable(); // id fakultas jika admin cek berkas fakultas
            $table->rememberToken();
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
        Schema::dropIfExists('user_petugas');
    }
}

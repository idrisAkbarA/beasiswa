<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // $table->id();
            $table->string('nim')->primary();
            $table->string('nama');
            $table->string('password');
            $table->bigInteger('jurusan_id')->nullable();
            $table->string('email')->unique();
            $table->string('hp')->nullable();
            $table->integer('semester')->nullable();
            $table->float('ips')->nullable();
            $table->float('ipk')->nullable();
            $table->integer('total_sks')->nullable();
            $table->string('tgl_lahir')->nullable();
            $table->string('tmpt_lahir')->nullable();
            $table->integer('ukt')->nullable();
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}

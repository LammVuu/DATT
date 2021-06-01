<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaikhoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taikhoan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sdt', 10)->unique();
            $table->string('matkhau', 100);
            $table->string('email', 100)->unique();
            $table->string('hoten', 100);
            $table->string('anhdaidien', 100)->nullable();
            $table->string('anhbia', 100)->nullable();
            $table->boolean('loaitk');
            $table->string('htdn', 10);
            $table->boolean('trangthai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taikhoan');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonhangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donhang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('thoigian', 20);
            $table->string('diachigiaohang', 200);
            $table->unsignedInteger('id_tk');
            $table->string('pttt', 100);
            $table->unsignedInteger('id_vc');
            $table->string('hinhthuc', 50);
            $table->integer('tongtien');
            $table->string('trangthaidonhang', 50);
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
        Schema::dropIfExists('donhang');
    }
}

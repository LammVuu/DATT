<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 20)->unique();
            $table->text('noidung');
            $table->float('chietkhau');
            $table->unsignedInteger('dieukien');
            $table->dateTime('ngaybatdau');
            $table->dateTime('ngayketthuc');
            $table->integer('sl')->nullable()->unsigned();
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
        Schema::dropIfExists('voucher');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaikhoanVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taikhoan_voucher', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_vc');
            $table->unsignedInteger('id_tk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taikhoan_voucher');
    }
}

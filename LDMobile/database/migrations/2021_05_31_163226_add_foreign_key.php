<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taikhoan_diachi', function (Blueprint $table) {
            $table->foreign('id_tk')->references('id')->on('taikhoan');
        });

        Schema::table('thongbao', function (Blueprint $table) {
            $table->foreign('id_tk')->references('id')->on('taikhoan');
        });

        Schema::table('taikhoan_voucher', function (Blueprint $table) {
            $table->foreign('id_tk')->references('id')->on('taikhoan');
            $table->foreign('id_vc')->references('id')->on('voucher');
        });

        Schema::table('phanhoi', function (Blueprint $table) {
            $table->foreign('id_tk')->references('id')->on('taikhoan');
            $table->foreign('id_dg')->references('id')->on('danhgiasp');
        });

        Schema::table('ctdg', function (Blueprint $table) {
            $table->foreign('id_dg')->references('id')->on('danhgiasp');
        });

        Schema::table('luotthich', function (Blueprint $table) {
            $table->foreign('id_tk')->references('id')->on('taikhoan');
            $table->foreign('id_dg')->references('id')->on('danhgiasp');
        });

        Schema::table('giohang', function (Blueprint $table) {
            $table->foreign('id_tk')->references('id')->on('taikhoan');
            $table->foreign('id_sp')->references('id')->on('sanpham');
        });

        Schema::table('hinhanh', function (Blueprint $table) {
            $table->foreign('id_msp')->references('id')->on('mausp');
        });

        Schema::table('slideshow_ctmsp', function (Blueprint $table) {
            $table->foreign('id_msp')->references('id')->on('mausp');
        });

        Schema::table('mausp', function (Blueprint $table) {
            $table->foreign('id_ncc')->references('id')->on('nhacungcap');
        });

        Schema::table('ctdh', function (Blueprint $table) {
            $table->foreign('id_dh')->references('id')->on('donhang');
            $table->foreign('id_sp')->references('id')->on('sanpham');
        });

        Schema::table('sanpham', function (Blueprint $table) {
            $table->foreign('id_msp')->references('id')->on('mausp');
            $table->foreign('id_km')->references('id')->on('khuyenmai');
        });

        Schema::table('baohanh', function (Blueprint $table) {
            $table->foreign('id_sp')->references('id')->on('sanpham');
        });

        Schema::table('donhang', function (Blueprint $table) {
            $table->foreign('id_vc')->references('id')->on('voucher');
        });

        Schema::table('kho', function (Blueprint $table) {
            $table->foreign('id_cn')->references('id')->on('chinhanh');
            $table->foreign('id_sp')->references('id')->on('chinhanh');
        });

        Schema::table('chinhanh', function (Blueprint $table) {
            $table->foreign('id_tt')->references('id')->on('tinhthanh');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

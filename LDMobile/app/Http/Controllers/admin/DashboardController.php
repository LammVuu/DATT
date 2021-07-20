<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DONHANG;
use App\Models\TAIKHOAN;
use DB;
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->admin='admin/content/';
    }

    public function Index()
    {
        //thong ke don hang va doanh thu
        $currentMonth =  Carbon::now('Asia/Ho_Chi_Minh')->format('m/Y');
        $currentDate =  Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        $DateofBeforeMonth =  Carbon::now('Asia/Ho_Chi_Minh')->subMonths(1)->format('Y-m-d');
        $bills= DONHANG::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $DateofBeforeMonth)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->get();
        $totalBillInMonth = count($bills);
        $totalMoneyInMonth = 0;
        foreach($bills as $bill){
            $totalMoneyInMonth += $bill->tongtien;
        }

        //thong ke thanh vien
        $accounts= TAIKHOAN::where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),">=", $DateofBeforeMonth)->where(DB::raw("date_format(STR_TO_DATE(thoigian, '%d/%m/%Y'),'%Y-%m-%d')"),"<=", $currentDate)->get();
        $totalAccountInMonth = count($accounts);

        return view($this->admin.'index', compact('totalBillInMonth', 'totalMoneyInMonth', 'totalAccountInMonth','currentMonth'));
    }

    /*============================================================================================================
                                                        Ajax
    ==============================================================================================================*/

    public function AjaxGetHinhAnh(Request $request)
    {
        if($request->ajax()){
            return 'success';
        }
    }

    public function AjaxDeleteObject(Request $request)
    {
        if($request->ajax()){
            // xóa hình ảnh
            if($request->object == 'hinhanh'){
                // xử lý xóa
                return 'success';
            }
        }
    }
    public function mainStatic(){
       
    }
}
